/*var consoleHolder = console;
function debug(bool){
    if(!bool){
        consoleHolder = console;
        console = {};
        console.log = function(){};
    }else
        console = consoleHolder;
        console.log("Console activated")
}
debug(false);*/

angular.module('appMain', ['ngSanitize'])

    .config( function($routeProvider, $locationProvider){
        $locationProvider.html5Mode(false).hashPrefix('!');
        $routeProvider.when('/', {controller:'CollectCtrl'})
            .when("/test",
            {
                action: 'CollectCtrl.route',
                event: 'route'
            })
            .when("/test/:id",
                {
                    action: 'Mon action ....',
                    event: 'Ma route'
                }
        );
        }
    )

    .directive('toolbar', function () {
            var linker = function(scope) {
                scope.$broadcast('newMenu', {id:'tb'});
            };

            return{
                restrict:'E',
                replace: true,
                templateUrl:'./public/js/ng/tmpl/toolbar_partial.html',
                link:linker
            }
        })
    .filter('sub', function () {
        return function (text, from, to) {
            if (isNaN(from))
                from = 0;

            if (isNaN(to))
                to = 1;

            if (text.length < to) {
                return text;
            }
            else {
                return String(text).substring(from, to);
            }
        }
    })
    .controller('UserCtrl', function($rootScope, $scope, $http,$route, $routeParams, $location) {

        $scope.safeApply = function(fn) {
            var phase = this.$root.$$phase;
            if(phase == '$apply' || phase == '$digest') {
                if(fn && (typeof(fn) === 'function')) {
                    fn();
                }
            } else {
                this.$apply(fn);
            }
        };

        var self = $scope;

        self.isMobile = {
            Android: function() {
                return navigator.userAgent.match(/Android/i);
            },
            BlackBerry: function() {
                return navigator.userAgent.match(/BlackBerry/i);
            },
            iOS: function() {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
            },
            Opera: function() {
                return navigator.userAgent.match(/Opera Mini/i);
            },
            Windows: function() {
                return navigator.userAgent.match(/IEMobile/i);
            },
            any: function() {
                return (self.isMobile.Android() || self.isMobile.BlackBerry() || self.isMobile.iOS() || self.isMobile.Opera() || self.isMobile.Windows());
            }
        };
        self.users = {
            data:[],
            test:[],
            selectedUser:{},
            newUser:{},
            tmpl:function(){
                return {}
            },
            getData: function(){
                return this.data;
            },
            select:function(id){
                this.selectedUser = _.find(this.getData(), function(item){ return item.id == id; });
                return this.getSelected();
            },
            getSelected: function(){
              return this.selectedUser;
            },
            findById: function(id){
                return _.find(this.getData(), function(item){ return id == item.id; });
            },
            createUser:function(){
                this.newUser = {};
            },
            removeUser:function(id){
                var newUsersList = _.reject(this.getData(), function(item){ return item.id == id; });
                this.data = newUsersList;
            },
            init:function(data){
                this.data = data;
            },
            create:function(){
                var newUser = {
                    id:0,
                    username:'',
                    email: '',
                    role: 0
                };
                this.data.push(newUser);
            },
            get: function(){

                self.$broadcast("hideMsg");
                self.$broadcast("showMsg",{title:"Chargement",text:"Acquisition des Informations en cours"});

                $http.get("./users").
                    success(function(data, status) {

                        if(data.status == 200){
                            $scope.$broadcast('newUsers', data);
                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("hideMsg");
                        self.$broadcast("showMsg",{title:"Base de données inaccessible.",text:"Nouvelle tentative dans 5 secondes. Status: "+status} );
                    });
            },
            put: function(id){

                if( id === 0 ){
                    this._post();
                    return  true;
                }

                self.$broadcast("hideMsg");
                self.$broadcast("showMsg",{title:"Chargement",text:"Acquisition des Informations en cours"});

                $http.put("./users/"+id, this.findById(id)).
                    success(function(data, status) {
                        self.$broadcast("hideMsg");
                        if(data.status == 200){
                            console.log(data);
                            //$scope.$broadcast('newUsers', data);
                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("hideMsg");
                        self.$broadcast("showMsg",{title:"Base de données inaccessible.",text:"Nouvelle tentative dans 5 secondes. Status: "+status} );
                    });
            },
            _post: function(){

                self.$broadcast("hideMsg");
                self.$broadcast("showMsg",{title:"Chargement",text:"Acquisition des Informations en cours"});
                var __self = this;
                $http.post("./users", this.findById(0)).
                    success(function(data, status) {
                        self.$broadcast("hideMsg");
                        if(data.status == 200){

                            if(data.results == false){
                                self.$broadcast("showMsg",{title:"Mmmm ...",text:"Duplicat ou problématique au niveau de la base de données"},true);
                            }else{
                                self.$broadcast("showMsg",{title:"Création réussie",text:"Confirmé par le serveur"},true);
                                __self.findById(0).id = data.results;
                                //console.log(self);
                            }
                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("hideMsg");
                        self.$broadcast("showMsg",{title:"Base de données inaccessible.",text:"Nouvelle tentative dans 5 secondes. Status: "+status} );
                    });
            },
            delete: function(id){

                self.$broadcast("hideMsg");
                self.$broadcast("showMsg",{title:"Connexion",text:"Suppression en cours"});
                var __self = this;
                var __id = id;

                $http.delete("./users/"+id).
                    success(function(data, status) {
                        self.$broadcast("hideMsg");

                        if(data.status == 200){

                            __self.removeUser(__id);

                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("hideMsg");
                        self.$broadcast("showMsg",{title:"Base de données inaccessible.",text:"Nouvelle tentative dans 5 secondes. Status: "+status} );
                    });
            },
            listeners: function(){
                var __self = this;
                return {
                    newUsers: function(scope, oData){
                        console.log(oData);
                        __self.init(oData.users);

                        if(__self.getData().length < 1){
                            console.error('No Dataset!');
                            self.$broadcast("showMsg",{title:"Attention",text:"Aucun utilisateur disponible"});
                        }else{
                            self.$broadcast("hideMsg");
                        }
                    }
                }
            }
        };

        self.categories = {
            obj:{},
            options:[],
            hash:[],
            editHash:[],
            newHash:[],
            get: function(){
                var __self = this;
                self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Chargement des catégories en cours"});
                $http.get("./categories").
                    success(function(data) {
                        __self.obj = data.results;
                        __self.options = _.values(__self.obj);
                        self.$broadcast("hideMsg");
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                    });
            }
        };
        self.ui ={
            states:{
                datasetsPanel: "",
                placePanel: "",
                locationPanel: "",
                slider: "",
                mode: "read",
                editMode: false,
                editStep: 1,
                quietUpdateMsg: 'Mode Sauvegarde Automatique',
                quietUpdate: false,
                overlay: null,
                overlayMsg: null
            },
            showDatasets: function(){
                this.states.datasetsPanel = "easeIn";
            },
            hideDatasets: function(){
                this.states.datasetsPanel = "easeOut";
            },
            toggleMode: function(){
                this.states.mode = (this.states.mode == "edit")?"read":"edit";
                this.states.editMode = (this.states.mode == "edit")?true:false;
            },
            showMsg: function(msg){

                if(this.states.overlayMsg){
                    return false;
                }

                var target = document.createElement("div");
                target.id = "overlay";

                var uiOverlay = document.createElement("div");
                uiOverlay.className = "ui-overlay";

                var msgBox = document.createElement("div");
                msgBox.className = "ui-msgbox";

                var msgTitle = document.createElement("div");
                msgTitle.className = "title lead";
                var newContent = document.createTextNode(msg.title);

                var p = document.createElement("p");
                var pContent = document.createTextNode(msg.text);
                p.appendChild(pContent);

                msgTitle.appendChild(newContent);
                msgTitle.appendChild(p);

                msgBox.appendChild(msgTitle);

                target.appendChild(msgBox);
                target.appendChild(uiOverlay);
                document.body.appendChild(target);

                this.states.overlayMsg = target;
            },
            hideMsg: function(){
                if(!this.states.overlayMsg){
                    return false;
                }
                var handle = this.states.overlayMsg;
                handle.parentNode.removeChild(handle);
                this.states.overlayMsg = null;
                return true;
            },
            showList: function(){
                this.states.slider = "";
                this.states.mode = "read";
            },
            showDetails: function(){
                this.states.slider = "details";
            },
            toStepTwo: function () {
                console.log("+++ toStepTwo +++");
                this.states.editStep = 2;
            },
            showForm: function () {
                if(this.states.locationPanel == "show-form"){
                    self.place.listeners().addLocation();
                }else{
                    this.states.locationPanel = "show-form";
                    setTimeout(self.place.listeners().addLocation,500);
                }
            },
            hideForm: function () {
                this.states.locationPanel = "hide-form";
            },

            listeners: function(){
                var __self = this;
                return {
                    showMsg: function(){
                        var msg = arguments[1] || {title:"",text:""};
                        var flash = arguments[2] || false;
                        __self.showMsg(msg);

                        if(flash){
                            setTimeout(function(){
                                    __self.hideMsg();
                                },3000
                            );
                        }
                    },
                    hideMsg: function(){
                        __self.hideMsg();
                    }
                }
            }
        };
        self.modal = {
            display: false,
            header: {
                title: "Modal Title"
            },
            body: {
                head:"",
                content:[]
            },
            footer: {
                btnLeft:"",
                btnRight:""
            },
            btn:{
                target: function(){},
                display: false,
                text: ""
            },
            show: function(){
                this.display = true;
            },
            hide: function(){
                this.display = false;
            },
            setContent: function(content){
                this.body = content;
            },
            setTitle: function(content){
                this.header.title = content;
            }
        };


        $scope.init = function(){
            console.info('++ INIT ++');
            // $scope.$broadcast("showMsg",{title:"Initialisation",text:"Connexion au serveur"});
            self.users.get();
        };

        // LISTENERS
        $scope.$on(
            "$routeChangeSuccess",
            function( $currentRoute, $previousRoute ){

                console.log("ON $routeChangeSuccess");
                console.log($currentRoute);
                console.log($previousRoute);
                console.log($route);
                console.log($location);
                if($route.current){
                    console.log("Event:" + $route.current.$$route.event);
                    console.log("Action:" + $route.current.$$route.action);
                    console.log($route.current.params);
                }
            }
        );

        $scope.$on('newUsers', self.users.listeners().newUsers);
        $scope.$on('showMsg', self.ui.listeners().showMsg);
        $scope.$on('hideMsg', self.ui.listeners().hideMsg);


        // DEBUG STUFF
        $scope.getScope = function(){
            console.log($scope);
        }

    });