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

    .directive('test', function ($compile) {
        console.log("++ TEST DIRECTIVE !!!!!!!! ++");
        var linker = function(scope, element, attrs) {
            attrs.$set('ng-click', "showDetails()");
            element.append($compile('<a href="#" class="pseudo-btn" ng-click="ui.showDetails(\''+attrs.rel+'\')" onclick="return false;"><span class="label">Afficher la fiche détaillée</span></a>')(scope));
        };
        return{
            restrict:'E',
            replace: true,
            transclude: true,
            //template: '<a href="#" class="pseudo-btn" ng-click="showDetails({{rel}})" onclick="return false;"><span class="label">Afficher le test</span></a>'
            template: '<p></p>',
            link: linker
        }
    })

    .directive('oneway', function($compile){

        var linker = function(scope, elm, attrs) {
            scope.$watch('places.features', function(newValue) {
                //console.log("WATCHER places");
                //console.log(scope);
                //console.log(newValue);
                //console.log(scope.places.length);
                //console.log("=============");

                elm.text(scope.places.features.length);
                var partials ="";
                if(scope.places.features.length>0){
                    _.each(newValue, function(item){
                        var tmpl = '<div class="box">';
                        tmpl    += '    <div class="left-space">';
                        tmpl    += '     <div>';
                        tmpl    += '         <img src="./public/img/icons/mapiconscollection-health-education-cccccc-default/hospital-building.png" alt="icon">';
                        tmpl    += '         </div>';
                        tmpl    += '     </div>';
                        tmpl    += '     <div class="right-space">';
                        tmpl    += '     <section>';
                        tmpl    += '         <a href="#" class="pseudo-btn pull-right" ng-click="place.get('+item.id+')" onclick="return false;">';
                        tmpl    += '             <span class="badge" style="padding-left: 4px;">';
                        tmpl    += '                 <i class="icon-info-sign icon-white" style="margin-top:1px;"></i>';
                        tmpl    += '             </span>';
                        tmpl    += '         </a>';
                        tmpl    += '         <div class="title">'+item.properties.name+'</div>';
                        tmpl    += '         <div class="title">Nom:'+item.properties.name+'</div>';
                        tmpl    += '         <div class="desc">Desc.: '+item.properties.description+'</div>';
                        tmpl    += '         <div class="bar">';
                        tmpl    += '             <span class="label-soft" ng-repeat="category in selectedCategorie">{{category.fr}}</span>';
                        tmpl    += '         </div>';
                        tmpl    += '     </section>';
                        tmpl    += '     </div>';
                        tmpl    += ' </div>';
                        partials += tmpl;
                    });
                    elm.append($compile(partials)(scope));

                }

                //$(elm).applySelectUISliderUpdateForNewValues();
            },true);
        };
        return{
            restrict:'E',
            //replace: true,
            //transclude: true,
            //template: '<p></p>',
            link: linker
        }
    })

    .directive('map', function () {

        var linker = function(scope, element, attrs) {
            //console.log(scope);
            scope.map = new L.Map(attrs.id, {'scrollWheelZoom':false, left:"340px",maxZoom:17});
            scope.map.attributionControl.setPrefix('');
            scope.markersLayer = new L.LayerGroup();
            //scope.markers = new L.MarkerClusterGroup({showCoverageOnHover:false});
            scope.markers = new L.MarkerClusterGroup({ showCoverageOnHover: false, animateAddingMarkers : true });
            var stamenAttributions ='Map tiles by <a href="http://stamen.com">Stamen Design</a>, under <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a>. Data by <a href="http://openstreetmap.org">OpenStreetMap</a>, under <a href="http://creativecommons.org/licenses/by-sa/3.0">CC BY SA</a>.';
            var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                osmAttrib = 'Map data © openstreetmap contributors',
                //osm = new L.TileLayer(osmUrl, {minZoom:8, maxZoom:18, attribution:osmAttrib}),
                //stamenTonerHybrid = new L.TileLayer('http://{s}.tile.stamen.com/toner-hybrid/{z}/{x}/{y}.png', {attribution: 'Stamen'});
                //stamenTonerLines = new L.TileLayer('http://{s}.tile.stamen.com/toner-lines/{z}/{x}/{y}.png', {attribution: 'Stamen'});
                //stamenTonerBackground = new L.TileLayer('http://{s}.tile.stamen.com/toner-background/{z}/{x}/{y}.png', {attribution: 'Stamen'});
                //stamenTonerLite = new L.TileLayer('https://stamen-tiles.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}.png', {attribution: 'Stamen'});
                  stamenTonerLite = new L.TileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner-lite/{z}/{x}/{y}.png', {attribution: stamenAttributions});
            mapCenter = new L.LatLng(origin.lat, origin.lon);

            scope.map.setView(mapCenter, 13)
                .addLayer(stamenTonerLite);

            //jQuery stuff
            // Todo Convert to Angular Model, DOM oriented right now :(
/*            var mapStretch = {
                min:function () {
                    $(".mapColumn").removeClass("maximazer");
                    $(".contentColumns").removeClass("minimazer");
                    $(".map-pull").find("i").removeClass("icon-chevron-up").addClass("icon-chevron-down");

                    self.map.panBy(new L.Point(0, 100));
                    var innerself = this;
                    $(".map-pull").unbind("click").click(function () {
                        innerself.max();
                    });
                },
                max:function () {
                    $(".mapColumn").addClass("maximazer");
                    $(".contentColumns").addClass("minimazer");
                    $(".map-pull").find("i").removeClass("icon-chevron-down").addClass("icon-chevron-up");

                    self.map.panBy(new L.Point(0, -100));
                    var innerself = this;
                    $(".map-pull").unbind("click").click(function () {
                        innerself.min();
                    });
                }
            };

            $(".map-pull").click(function () {
                mapStretch.max();
            });*/
        };

        return{
            restrict:'A',
            link:linker
        }
    })

    .directive('updatemodelonblur', function() {
        return {
            restrict: 'A',
            require: 'ngModel',
            link: function(scope, elm, attr, ngModelCtrl)
            {
                if(attr.type === 'radio' || attr.type === 'checkbox')
                {
                    return;
                }

                // Update model on blur only
                elm.unbind('input').unbind('keydown').unbind('change');
                var updateModel = function()
                {
                    scope.$apply(function()
                    {
                        ngModelCtrl.$setViewValue(elm.val());
                    });
                };
                elm.bind('blur', updateModel);

                // Not a textarea
                if(elm[0].nodeName.toLowerCase() !== 'textarea')
                {
                    // Update model on ENTER
                    elm.bind('keydown', function(e)
                    {
                        e.which == 13 && updateModel();
                    });
                }
            }
        };
    })

    .directive('chosenRao', function () {
        var linker = function(scope, element, attrs) {
            console.log("-- ATTRS");
            console.log(attrs);
            var updateList = function(){
                console.log("++++ TEST CHOSEN");
                //if(scope.cat.hash.lenght !== 0 ){
                  if(scope.categories[attrs.ngModel].lenght !== 0 ){
                    var codesList = _.pluck(scope.categories.options, 'id');
                    var indexes = [];
                    //_.each(scope.cat.hash, function (code) {
                    _.each(scope.categories[attrs.ngModel], function (code) {
                        indexes.push(_.indexOf(codesList, code));
                    });

                    $(element).val(indexes).trigger("liszt:updated");
                }else{
                    $(element).trigger('liszt:updated');
                }
            }

            scope.$watch('categories', updateList, true);

            _scope = scope;
            $(element).chosen().change( function(event, item){
                scope.$apply(function(){
                    //var hash = _scope.cat.hash;
                    var hash = _scope.categories[attrs.ngModel];

                    if( item.selected !== undefined ){
                        hash.push(_scope.categories.options[item.selected].id);
                    }
                    if( item.deselected !== undefined ){
                        var id= _scope.categories.options[item.deselected].id;
                        hash.splice(_.indexOf(hash,id),1);
                    }
                })
            });
        };

        return{
            restrict:'A',
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
    .controller('CollectCtrl', function($rootScope, $scope, $http,$route, $routeParams, $location) {

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
        self.datasets = {
            features:[],
            selectedDataset:{},
            getFeatures: function(){
                return this.features;
            },
            select:function(id){
                this.selectedDataset = _.find(this.getFeatures(), function(item){ return item.id == id; });
                return this.getSelected();
            },
            getSelected: function(){
              return this.selectedDataset;
            },
            findById: function(id){
                return _.find(this.getFeatures(), function(item){ return id == item.id; });
            },
            init:function(data){
                this.features = data;
            },
            get: function(){

                self.$broadcast("hideMsg");
                self.$broadcast("showMsg",{title:"Chargement",text:"Jeux de données"});

                $http.get("./datasets").
                    success(function(data, status) {

                        if(data.status == 200){
                            $scope.$broadcast('newDatasets', data);
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
                    newDatasets: function(scope, oData){

                        __self.init(oData.results.geoJson.features);

                        if(__self.getFeatures().length < 1){
                            console.error('No Dataset!');
                            self.$broadcast("showMsg",{title:"Attention",text:"Aucun jeu de données disponible"});
                        }else{
                            self.$broadcast("hideMsg");
                            self.categories.get();
                        }
                    }
                }
            }
        };
        self.places = {
            features:[],
            getFeatures:function(){
                return this.features;
            },
            addFeature:function(Place){
                var newPoint = this.tmpl();
                newPoint.id = newPoint.properties.id = '_'+this.getFeatures().length;
                newPoint.geometry = Place.geometry;
                newPoint.properties.name = Place.properties.name;
                newPoint.properties.description = Place.properties.description;
                newPoint.properties.primary_category_id = Place.properties.primary_category_id;
                this.features.push(newPoint);
                return newPoint;
            },
            removeFeature:function(id){
                var newFeatures = _.reject(this.getFeatures(), function(item){ return item.id == id; });
                this.features = newFeatures;
                $rootScope.$broadcast("setMarkers", {Places:this.getFeatures(),zoomToBounds:false});
            },
            init:function(data){
                this.features = data;
            },
            get: function(datasetId){

                self.datasets.select(datasetId);
                self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Chargement des lieux en cours"});
                console.log("Get Places!! Dataset("+datasetId+")");

                $http.get("./datasets/"+datasetId +"/places").
                    success(function(data) {

                        if(data.status == 200){
                            self.$broadcast('newPlaces', data);
                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("showMsg",{title:"Attention",text:"Status: "+status});
                    });
            },
            tmpl:function(){
                return {
                    id:0,
                        type:'Point',
                    geometry:{
                        coordinates:[]
                    },
                    properties:{
                        id:0,
                        description:'',
                        name:'',
                        primary_category_id:0
                    }
                }
            },
            listeners: function(){
                var __self = this;
                return {
                    newPlaces: function($scope, oData){

                        self.ui.hideDatasets();
                        __self.init(oData.results.geoJson.features);
                        $rootScope.$broadcast("setMarkers", {Places:__self.getFeatures(),zoomToBounds:true});
                        self.$broadcast("hideMsg");
                    }
                }
            }
        };
        self.place = {
            features:[],
            featuresCache:{},
            feature:{},
            newFeature:{},
            getFeature:function(){
                return this.feature;
            },
            getNewFeature:function(){
                return this.newFeature;
            },
            getCoordinates:function(){
                return this.getFeature().geometry.coordinates;
            },
            getNewCoordinates:function(){
                return this.getNewFeature().geometry.coordinates;
            },
            updateCategories : function(type){
                switch (type){
                    case 'new':
                        cats = self.categories.newHash;
                        this.getNewFeature().properties.primary_category_id = cats[0] || 0;
                        this.getNewFeature().properties.secondary_category_id = cats[1] || 0;
                        break;
                    case 'edit':

                        cats = self.categories.editHash;
                        this.getFeature().properties.primary_category_id = cats[0] || 0;
                        this.getFeature().properties.secondary_category_id = cats[1] || 0;
                        break;
                }
            },
            init:function(data){
                this.feature = data;
            },
            // new Point Location (press button) -- After point creation
            setLocation: function () {
                __self = this;
                self.$broadcast("showMsg",{title:"Calcul en cours",text:"Position du lieu"});
                setTimeout(function(){
                    //$rootScope.$broadcast("getMapCenter", {id:__self.getFeature().id},'newLocation');
                    $rootScope.$broadcast("getMapCenter", {id:__self.getNewFeature().id},'newLocation');
                },100);
            },
            tmpl:function(){
                return {
                    id:"temp",
                    geometry:{
                        coordinates:[-73.59246,45.528293]
                    },
                    properties:{
                        name:'',
                        description:'',
                        dataset_id:0,
                        primary_category_id:0,
                        secondary_category_id:0,
                        address: '',
                        city:'',
                        postal_code:'',
                        service: '',
                        website:'',
                        tel_number:'',
                        attributes:[]
                    }
                }
            },
            cancel:function(){
                //delete temp Places Point
                // todo: createFunction

                this.newFeature = {};
            },
            get: function(placeId){

                this.feature = {};
                   var __self = this;
                if(this.featuresCache['_'+placeId]){
                    console.info("FROM CACHE");
                    self.$broadcast('newPlace', this.featuresCache['_'+placeId] );
                    self.ui.showDetails();
                }else{
                    self.ui.showDetails();
                    $http.get("./places/"+placeId ).
                        success(function(data) {
                            if(data.status == 200){
                                __self.featuresCache['_'+placeId] = data;
                                var data = data;
                                setTimeout(
                                    function(){
                                        console.log('TRIGGER');
                                        self.$broadcast('newPlace', data);
                                    }
                                    ,600);
                            }
                        }).
                        error(function(data, status) {
                            console.eror(status);
                            console.log(data);
                            self.$broadcast("hideMsg");
                            self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Le serveur distant n'est pas disponible en ce moment."},true);
                            self.ui.showList();
                        });
                }
            },
            put: function(){
                console.log("PPPPPPPUUT");
                this.updateCategories('edit');
                if(self.ui.states.quietUpdate){
                    self.ui.states.quietUpdateMsg = "Sauvegarde en cours ...";
                }else{
                    self.$broadcast("showMsg",{title:"Mise à jour en cours",text:"En attente du serveur ... "});
                }
                console.log("BEFORE HTTP CALL");
                self.safeApply();

                $http.put("./places",this.getFeature()).
                    success(function(data) {
                        self.$broadcast("hideMsg");
                        console.log(status);
                        console.log(data);
                        if(data.status == 200){
                            console.info('QUIET UPDATE');
                            // todo: update Place with new infos
                            if(self.ui.states.quietUpdate){
                                self.ui.states.quietUpdateMsg = "Sauvegarde automatique effectuée";
                            }else{
                                self.$broadcast("showMsg",{title:"Mise à jour réussie",text:"Confirmation du serveur."},true);
                            }
                            self.safeApply();
                        }

                    }).
                    error(function(data, status) {
                        console.info('QUIET UPDATE GONE BAAAAADDDDDD');
                        self.$broadcast("hideMsg");
                        console.error(status);
                        console.log(data);
                        if(self.ui.states.quietUpdate){
                            self.ui.states.quietUpdateMsg = "Échec de La suavegarde automatique. Serveur distant non-disponible.";
                        }else{
                            self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Le serveur distant n'est pas disponible en ce moment."},true);
                        }
                        self.safeApply();
                    });
                self.safeApply();
            },
            post: function(){
                console.log("CREATE New Places > POST");
                self.$broadcast("showMsg",{title:"Création en cours",text:"En attente du serveur ... "});

                //add Categories
                this.updateCategories('new');

                $http.post("./places",this.getNewFeature()).
                    success(function(data) {
                        self.$broadcast("hideMsg");
                        console.log(status);
                        console.log(data);
                        if(data.status == 200){
                            self.$broadcast("showMsg",{title:"Création réussie",text:"Confirmé par le serveur"},true);

                            __self.newFeature = {};
                            self.ui.showList();
                        }
                    }).
                    error(function(data, status) {
                        self.$broadcast("hideMsg");
                        console.error(status);
                        console.log(data);
                        self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Le serveur distant n'est pas disponible en ce moment."},true);
                    });
            },
            delete:function(){

                // todo: showConfirm
                self.$broadcast("showMsg",{title:"Suppression en cours",text:"En attente du serveur ... "});
                var __self = this;

                $http.delete("./places/"+this.getFeature().id, {dataset:self.datasets.getSelected().id}).
                    success(function(data) {
                        self.$broadcast("hideMsg");
                        console.log(status);
                        console.log(data);
                        if(data.status == 200){

                            self.places.removeFeature(__self.getFeature().id);
                            __self.feature = {};
                            self.ui.showList();
                            self.$broadcast("showMsg",{title:"Suppression définitive",text:"Confirmée par le serveur."},true);
                        }
                    }).
                    error(function(data, status) {
                        console.error(status);
                        console.log(data);
                        self.$broadcast("hideMsg");
                        self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Le serveur distant n'est pas disponible en ce moment."},true);
                    });
            },
            listeners: function(){
                var __self = this;
                return {
                    newPlace: function ($scope, oData) {

                        __self.init( oData.results.geoJson.features[0] );

                        //Categories
                        self.categories.editHash = [
                            __self.feature.properties.primary_category_id,
                            __self.feature.properties.secondary_category_id
                        ];
                        self.safeApply();
                    },
                    // ADD Tempory Point. Called from ui.showForm
                    addLocation: function(){

                        self.categories.newHash = [];
                        self.ui.states.editStep = 1;

                        var newFeature = __self.tmpl();
                        newFeature.properties.dataset_id = self.datasets.getSelected().id;

                        var attributes = self.datasets.getSelected().properties.attributes;
                        _.each(attributes, function(item){
                            var att = item;
                            att.data='';
                            newFeature.properties.attributes.push(att);
                        });

                        //__self.feature = newFeature;
                        __self.newFeature = newFeature;

                        // Add a feature to Places
                        placesFeature = self.places.addFeature(__self.newFeature);
                        //placesFeature = self.places.addFeature(__self.feature);

                        // Update id
                        __self.newFeature.id = placesFeature.id;
                        //__self.feature.id = placesFeature.id;

                        self.safeApply();
                    },
                    newLocation: function (event, Point) {
                        console.log('-- New Location');

                       // __self.getFeature().geometry.coordinates[1] = Point.LatLng.lat;
                       // __self.getFeature().geometry.coordinates[0] = Point.LatLng.lng;
                        __self.getNewFeature().geometry.coordinates[1] = Point.LatLng.lat;
                        __self.getNewFeature().geometry.coordinates[0] = Point.LatLng.lng;

                        var tempPlace = _.find(self.places.getFeatures(), function(item){ return item.id == Point.id; });
                        tempPlace.geometry.coordinates = __self.getNewCoordinates();
                        $rootScope.$broadcast("setMarkers", {Places:self.places.getFeatures()});

                        //self.setMapCenter();
                        $rootScope.$broadcast("setMapCenter", {lon:self.place.getNewCoordinates()[0],lat:self.place.getNewCoordinates()[1]});

                        self.ui.states.editStep = 2;

                        if(typeof(google) != "undefined"){
                            self.reverseGeocoding();
                        }else{
                            self.$broadcast("hideMsg");
                            self.safeApply();
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
        self.inverseGeocode = {};

        $scope.init = function(){
            console.info('++ INIT ++');
            $scope.$broadcast("showMsg",{title:"Initialisation",text:"Connexion au serveur"});
            self.datasets.get();
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

        $scope.$on('newDatasets', self.datasets.listeners().newDatasets);
        $scope.$on('newPlaces', self.places.listeners().newPlaces);
        $scope.$on('newPlace', self.place.listeners().newPlace);
        $scope.$on('showMsg', self.ui.listeners().showMsg);
        $scope.$on('hideMsg', self.ui.listeners().hideMsg);
        $scope.$on('newLocation', self.place.listeners().newLocation);
        $scope.$on('newMapCenter', function ($scope, Point) {
            self.place.getCoordinates()[1]= Point.LatLng.lat;
            self.place.getCoordinates()[0] = Point.LatLng.lng;
            //console.log(Point);
            //var tempPlace = _.find(self.places.features, function(item){ return item.id == Point.id; });
            var tempPlace = _.find(self.places.features, function(item){ return item.id == self.place.getFeature().id; });
            //console.log(tempPlace);
            console.log(self);
            tempPlace.geometry.coordinates[1] = Point.LatLng.lat;
            tempPlace.geometry.coordinates[0] = Point.LatLng.lng;

            $rootScope.$broadcast("setMarkers", {Places:self.places.features});

            if(typeof (google) != 'undefined'){
                self.reverseGeocoding();
            }else{
                self.$broadcast("hideMsg");
            }
        });
        $scope.$on('markerShowDetails', function ($scope, oData) {
            self.place.get(oData.id);
        });

        // GEO FUNC
        $scope.setMapCenter = function(){
            $rootScope.$broadcast("setMapCenter", {lon:self.place.getCoordinates()[0],lat:self.place.getCoordinates()[1]});
        }

        $scope.getMapCenter = function(){
            self.$broadcast("showMsg",{title:"Calcul en cours",text:"Position du lieu"});
            setTimeout(function(){
                $rootScope.$broadcast("getMapCenter", {id:self.place.id});
            },100);
        }

        $scope.reverseGeocoding = function(){
            // reverse geocode with Google
            console.log("Reverse Geocoding");
            if (typeof self.geocoder === 'undefined') {
                self.geocoder = new google.maps.Geocoder();
            }

            if(self.ui.states.locationPanel == ''){
                var location = new google.maps.LatLng(self.place.getCoordinates()[1], self.place.getCoordinates()[0]);
            }else{
                var location = new google.maps.LatLng(self.place.getNewCoordinates()[1], self.place.getNewCoordinates()[0]);
            }

            self.geocoder.geocode( {'latLng': location}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    var formatted_address = results[0].formatted_address;
                    self.inverseGeocode = results[0];

                    self.modal.setTitle('Géocodage');
                    var cHead = 'Géocodage Inverse disponible';
                    self.modal.setContent({head:cHead,content:[formatted_address]});
                    //self.modal.target = self.montest;
                    var btn = {
                        target: self.processInverseGeododing,
                        display: true,
                        text: "Utiliser ces informations"
                    };
                    self.modal.btn = btn;
                    self.modal.show();

                    console.log(self.modal);
                    console.log(self);
                    self.$broadcast("hideMsg");
                    self.safeApply();

                } else {
                    console.log(status);
                }
            });
        }

        $scope.geoLocation = function() {
            $rootScope.$broadcast("geoLocation");
        }

        $scope.processInverseGeododing = function () {

            var invGeo = self.inverseGeocode;
            var location_type = invGeo.geometry.location_type;
            var address_components = invGeo.address_components;

            var street_number = "";
            var route = "";

            if(self.ui.states.locationPanel == ''){
                _.each(address_components, function(item){

                    if( item.types[0] === "postal_code"){
                        self.place.feature.properties.postal_code = item.long_name;
                    }

                    if( item.types[0] === "locality"){
                        self.place.feature.properties.city = item.long_name;
                    }

                    if( item.types[0] === "street_number"){
                        street_number = item.long_name;
                    }

                    if( item.types[0] === "route"){
                        route = item.long_name;
                    }
                });

                self.place.feature.properties.address = street_number + " " + route;
                self.place.feature.properties.service = "Google";
                self.place.feature.properties.location_type = location_type;

            }else{
                _.each(address_components, function(item){

                    if( item.types[0] === "postal_code"){
                        self.place.newFeature.properties.postal_code = item.long_name;
                    }

                    if( item.types[0] === "locality"){
                        self.place.newFeature.properties.city = item.long_name;
                    }

                    if( item.types[0] === "street_number"){
                        street_number = item.long_name;
                    }

                    if( item.types[0] === "route"){
                        route = item.long_name;
                    }
                });

                self.place.newFeature.properties.address = street_number + " " + route;
                self.place.newFeature.properties.service = "Google";
                self.place.newFeature.properties.location_type = location_type;

            }

            self.modal.hide();
            self.safeApply();
        };

        // DEBUG STUFF
        $scope.getScope = function(){
            console.log($scope);
        }
        //OBSERVERS
        $scope.$watch('place.feature', function(newValue){
            if($scope.ui.states.mode == "edit"){
                console.log(">> WATCHER > place ");
                //console.log(newValue);
                if(typeof($scope.ui.states.triggerUpdate) != 'undefined'){
                    clearTimeout($scope.ui.states.triggerUpdate);
                }

                $scope.ui.states.triggerUpdate = setTimeout(
                    function(){
                        __self = $scope;
                        if($scope.ui.states.mode == 'edit' && $scope.ui.states.quietUpdate){
                            console.log("+++ SOFT UPDATE +++");
                            __self.place.put();
                            console.log(__self);
                        }
                    },
                    2000
                );
            }
        },true);

/*        $scope.$watch( function(newV){
            console.log("WATCHER > DIGEST");
            console.log(newV);
        });*/
    });