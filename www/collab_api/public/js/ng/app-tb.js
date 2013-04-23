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

        var linker = function(scope, element, attrs) {
            attrs.$set('ng-click', "showDetails()");
            element.append($compile('<a href="#" class="pseudo-btn" ng-click="showDetails(\''+attrs.rel+'\')" onclick="return false;"><span class="label">Afficher la fiche détaillée</span></a>')(scope));
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
            scope.$watch('places', function(newValue) {
                //console.log("WATCHER places");
                //console.log(scope);
                //console.log(newValue);
                //console.log(scope.places.length);
                //console.log("=============");

                elm.text(scope.places.length);
                var partials ="";
                if(scope.places.length>0){
                    _.each(newValue, function(item){
                        var tmpl = '<div class="box">';
                        tmpl    += '    <div class="left-space">';
                        tmpl    += '     <div>';
                        tmpl    += '         <img src="./public/img/icons/mapiconscollection-health-education-cccccc-default/hospital-building.png" alt="icon">';
                        tmpl    += '         </div>';
                        tmpl    += '     </div>';
                        tmpl    += '     <div class="right-space">';
                        tmpl    += '     <section>';
                        tmpl    += '         <a href="#" class="pseudo-btn pull-right" ng-click="getPlace('+item.id+')" onclick="return false;">';
                        tmpl    += '             <span class="badge" style="padding-left: 4px;">';
                        tmpl    += '                 <i class="icon-info-sign icon-white" style="margin-top:1px;"></i>';
                        tmpl    += '             </span>';
                        tmpl    += '         </a>';
                        tmpl    += '         <div class="title">'+item.label+'</div>';
                        tmpl    += '         <div class="title">Nom:'+item.name_fr+'</div>';
                        tmpl    += '         <div class="desc">Desc.: '+item.description+'</div>';
                        tmpl    += '         <div class="bar">';
                        tmpl    += '             <span class="label-soft">'+item.categories.primary_category.fr+'</span>';
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
            scope.map = new L.Map(attrs.id, {'scrollWheelZoom':false, left:"340px"});
            scope.map.attributionControl.setPrefix('');
            scope.markersLayer = new L.LayerGroup();
            //scope.markers = new L.MarkerClusterGroup({showCoverageOnHover:false});
            scope.markers = new L.MarkerClusterGroup({ showCoverageOnHover: false, animateAddingMarkers : true });
            var osmUrl = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
                osmAttrib = 'Map data © openstreetmap contributors',
                osm = new L.TileLayer(osmUrl, {minZoom:8, maxZoom:18, attribution:osmAttrib}),
                mapCenter = new L.LatLng(origin.lat, origin.lon);

            scope.map.setView(mapCenter, 13)
                .addLayer(osm);

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
            scope.$watch('cat.options', function(item){

                if(scope.cat.hash.lenght !== 0 ){
                    var codesList = _.pluck(scope.cat.options, 'id');
                    var indexes = [];
                    _.each(scope.cat.options, function (code) {
                        indexes.push(_.indexOf(codesList, code));
                    });
                    $(element).val(indexes).trigger("liszt:updated");
                }else{
                    $(element).trigger('liszt:updated');
                }
            },true);

            _scope = scope;
            $(element).chosen().change( function(event, item){
                //console.log("CHOSEN CHANGE !");
                //console.log(item);
                //console.log(_.keys(item));

                scope.$apply(function(){
                    var hash = _scope.cat.hash;

                    if( item.selected !== undefined ){
                        hash.push(_scope.cat.options[item.selected].id);
                    }
                    if( item.deselected !== undefined ){
                        var id= _scope.cat.options[item.deselected].id;
                        hash.splice(_.indexOf(hash,id),1);
                    }
                    console.log(_scope.cat.options);
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

        $scope.isMobile = {
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
        $scope.datasets = [];
        $scope.places = [];
        $scope.lieux = [];

        $scope.place = {};
        $scope.placeTmpl = {
            name:"",
            desc:"",
            label:"",
            tags:{},
            lon:"",
            lat:"",
            tel:"",
            address:"",
            web:"",
            city:"",
            postal_code:"",
            cat1:"",
            cat2:""
        };
        $scope.insertPlace = {};
        $scope.cat = {
            hash:[]
        };
        $scope.selectedDataset = "";
        $scope.datasets_panel = "easeIn";
        $scope.place_panel = "";
        $scope.slider = "";

        $scope.modal = {
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
        $scope.inverseGeocode = {};
        $scope.mode = "read";
        $scope.locationPanel = "hide-form";
        $scope.editMode = false;

        $scope.placeDetailsCache = {};

        $scope.init = function(){
            console.log('INIT');
            //$scope.$broadcast("load","Connexion");
            $scope.$broadcast("showMsg",{title:"Initialisation",text:"Connexion au serveur"});
            //$scope.$broadcast("showMsg",{title:"Chargement",text:"test ..."});
            //console.log( self.isMobile.any());
            $scope.getDatasets();
        };

        $scope.showDatasets = function(){
            $scope.datasets_panel = "easeIn";
        }

        $scope.hideDatasets = function(){
            $scope.datasets_panel = "easeOut";
        }

        $scope.showLoader = function(text){
            if($scope.overlay){
                return false;
            }

            var opts = {
                lines: 13, // The number of lines to draw
                length: 11, // The length of each line
                width: 5, // The line thickness
                radius: 17, // The radius of the inner circle
                corners: 1, // Corner roundness (0..1)
                rotate: 0, // The rotation offset
                color: '#FFF', // #rgb or #rrggbb
                speed: 1, // Rounds per second
                trail: 60, // Afterglow percentage
                shadow: false, // Whether to render a shadow
                hwaccel: false, // Whether to use hardware acceleration
                className: 'spinner', // The CSS class to assign to the spinner
                zIndex: 2e9, // The z-index (defaults to 2000000000)
                top: 'auto', // Top position relative to parent in px
                left: 'auto' // Left position relative to parent in px
            };

            var target = document.createElement("div");
            target.id = "overlay";
            target.style.cssText = 'background-color:#fff;position:fixed;top:0;bottom:0;width:100%;z-index:2;opacity:.5;';
            document.body.appendChild(target);
            var spinner = new Spinner(opts).spin(target);
            $scope.backdrop = target;
            $scope.overlay = iosOverlay({
                text: text,
                duration: null,
                spinner: spinner
            });
            return false;
        };

        $scope.hideLoader = function(){
            if(!$scope.overlay){
                return false;
            }
            $scope.overlay.hide();
            $scope.overlay = null;

            var handle = $scope.backdrop;
            handle.parentNode.removeChild(handle);
            return true;
        };

        $scope.showMsg = function(msg){
            //console.log("showMsg");
            if($scope.overlayMsg){
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

            $scope.overlayMsg = target;
        };

        $scope.hideMsg = function(){
            console.log("HIDE!");
            if(!$scope.overlayMsg){
                return false;
            }
            //$scope.overlayMsg.hide();
            var handle = $scope.overlayMsg;
            handle.parentNode.removeChild(handle);
            $scope.overlayMsg = null;
            return true;
        };

        // LISTENERS
        $scope.$on('load', function () {
            var text = arguments[1] || "";
            self.showLoader(text);
        });

        $scope.$on('loadEnd', function () {
            self.hideLoader();
        });

        $scope.$on('showMsg', function () {
            var msg = arguments[1] || {title:"",text:""};
            var flash = arguments[2] || false;

            self.showMsg(msg);

            if(flash){
                setTimeout(function(){
                        self.hideMsg();
                    },3000
                );
            }
        });

        $scope.$on('hideMsg', function () {

            self.hideMsg();
        });

        $scope.$on('route', function () {

            console.log("NEW ROUTE EVENT");
        });

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

        $scope.$on('newMapCenter', function ($scope, Point) {
            self.place.location.latitude = Point.LatLng.lat;
            self.place.location.longitude = Point.LatLng.lng;

            var tempPlace = _.find(self.places, function(item){ return item.id == Point.id; });
            console.log(tempPlace);
            tempPlace.location.latitude = Point.LatLng.lat;
            tempPlace.location.longitude = Point.LatLng.lng;

            $rootScope.$broadcast("setMarkers", {Places:self.places});

            //self.$broadcast("hideMsg");
            self.reverseGeocoding();
        });

        $scope.$on('newDatasets', function ($scope, oData) {

            console.log('EVENT newDatasets');
            console.log(oData);
            self.datasets = oData.results;
            if(self.datasets.length < 1){
                console.log('No Dataset!');
                self.$broadcast("showMsg",{title:"Attention",text:"Aucun jeu de données disponible"});
            }else{
                //self.$broadcast("loadEnd");
                self.$broadcast("hideMsg");
                self.getCategories();
                //self.getPlaces( oData.results[0].id );
            }
        });

        $scope.$on('newPlaces', function ($scope, oData) {

            console.log('EVENT newPlaces');
            console.log(oData);

            self.hideDatasets();
            self.places = oData.results;
            //self.lieux = oData.results;
            $rootScope.$broadcast("setMarkers", {Places:self.places,zoomToBounds:true});
           // $rootScope.$broadcast("setMarkers", {Places:oData.results,zoomToBounds:true});
            self.$broadcast("hideMsg");

        });

        $scope.$on('marked', function () {
            console.log('MARKED');
        });

        $scope.$on('newPlace', function ($scope, oData) {
            console.log('EVENT newPlace');
            console.log(oData);
            self.place = oData.results;
            console.log(self.place.contacts);

            //Categories
            var selectedCategorie = [];
            var primaryId = self.place.categories.primary_category.id;
            var secondaryId = self.place.categories.secondary_category.id;
            _.each(self.cat.options, function(item){
                if(item.id == primaryId ){
                    selectedCategorie[0] = item;
                }
                if(item.id == secondaryId){
                    selectedCategorie[1] = item;
                }
            });

            self.selectedCategorie = selectedCategorie;

            self.safeApply();
        });

        $scope.$on('markerShowDetails', function ($scope, oData) {
            self.getPlace(oData.id);
        });

        // HTTP GET DATASET
        $scope.getDatasets = function(){

            var self = $scope;
            self.$broadcast("hideMsg");
            self.$broadcast("showMsg",{title:"Chargement",text:"Jeux de données"});

            $http.get("./datasets").
                success(function(data, status) {

                    if(data.status == 200){
                        $scope.$broadcast('newDatasets', data);
                    }
                }).
                error(function(data, status) {
                    console.log(status);
                    console.log(data);
                    //$scope.data = data || "Request failed";
                    //self.status = status;
                    //self.$broadcast("loadEnd");
                    self.$broadcast("hideMsg");
                    self.$broadcast("showMsg",{title:"Base de données inaccessible.",text:"Nouvelle tentative dans 5 secondes. Status: "+status} );
                });
        };

        // HTTP GET PLACES
        $scope.getPlaces = function(datasetId){

            var self = $scope;
            self.selectedDataset = datasetId;
            //$location.path("dataset/"+datasetId);
            self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Chargement des lieux en cours"});
            console.log("Get Places!! Dataset("+datasetId+")");

            $http.get("./datasets/"+datasetId +"/places").
                success(function(data) {

                    if(data.status == 200){
                        self.$broadcast('newPlaces', data);
                    }
                }).
                error(function(data, status) {
                    console.log(status);
                    console.log(data);
                    //$scope.data = data || "Request failed";
                    $scope.status = status;

                    self.$broadcast("showMsg",{title:"Attention",text:"Status: "+status});
                });
        };

        // Toggle Mode
        $scope.toggleMode = function(){
            self.mode = (self.mode == "edit")?"read":"edit";
            self.editMode = (self.mode == "edit")?true:false;
        };

        // HTTP GET SINGLE PLACE
        $scope.getPlace = function(placeId){
            console.log("Get Places #"+placeId);
            var self = $scope;

            //self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Chargement des informations en cours"});

            self.place = [];


            //console.log('TEST1');

            // CACHE CHECK
            //setTimeout(function(){
                //console.log("ok");
                if(self.placeDetailsCache['_'+placeId]){
                    console.log("FROM CACHE");
                    self.$broadcast('newPlace', self.placeDetailsCache['_'+placeId] );
                    self.showDetails();
                }else{
                    self.showDetails();
                    $http.get("./places/"+placeId ).
                        success(function(data) {
                            //console.log(status);
                            //console.log(data);
                            if(data.status == 200){
                                self.placeDetailsCache['_'+placeId] = data;
                                var data = data;
                                setTimeout(
                                    function(){
                                        console.log('TRIGGER');
                                        self.$broadcast('newPlace', data);

                                    }
                                    ,600);
                                //self.$broadcast('newPlace', data);
                            }
                        }).
                        error(function(data, status) {
                            console.log(status);
                            console.log(data);
                            //$scope.data = data || "Request failed";
                            //$scope.status = status;
                        });
                }

            //},1000);

        };

        $scope.getCategories = function(){
            console.log("Get Categories");
            var self = $scope;
            self.$broadcast("showMsg",{title:"Connexion au serveur",text:"Chargement des catégories en cours"});
            $http.get("./categories").
                success(function(data) {
                    $scope.cat.options = data.results;
                    self.$broadcast("hideMsg");
                }).
                error(function(data, status) {
                    console.log(status);
                    console.log(data);
                });
        }

        $scope.setMapCenter = function(){
            $rootScope.$broadcast("setMapCenter", {lon:self.place.location.longitude,lat:self.place.location.latitude});
        }

        $scope.getMapCenter = function(){
            self.$broadcast("showMsg",{title:"Calcul en cours",text:"Position du lieu"});
            setTimeout(function(){
                $rootScope.$broadcast("getMapCenter", {id:self.place.id});
            },100);
            //$rootScope.$broadcast("getMapCenter", {id:self.place.id});
        }

        $scope.setLocation = function () {
            self.$broadcast("showMsg",{title:"Calcul en cours",text:"Position du lieu"});
            setTimeout(function(){
                $rootScope.$broadcast("getMapCenter", {id:self.place.id},'newLocation');
            },100);
        };

        $scope.$on('newLocation', function (event, Point) {
            console.log('New Location');

            self.place.location.latitude = Point.LatLng.lat;
            self.place.location.longitude = Point.LatLng.lng;

            var tempPlace = _.find(self.places, function(item){ return item.id == Point.id; });
            console.log(tempPlace);
            tempPlace.location.latitude = Point.LatLng.lat;
            tempPlace.location.longitude = Point.LatLng.lng;
            self.editStep = 2;
            self.setMapCenter();
            $rootScope.$broadcast("setMarkers", {Places:self.places});
            self.reverseGeocoding();

        });

        $scope.reverseGeocoding = function(){
            // reverse geocode with Google
            if (typeof self.geocoder === 'undefined') {
                self.geocoder = new google.maps.Geocoder();
            }

            var location = new google.maps.LatLng(self.place.location.latitude, self.place.location.longitude);
            self.geocoder.geocode( {'latLng': location}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    var formatted_address = results[0].formatted_address;
                    self.inverseGeocode = results[0];

                    self.modal.setTitle('Géocodage');
                    var cHead = 'Géocodage Inverse disponible';
                    self.modal.setContent({head:cHead,content:[formatted_address]});
                    self.modal.target = self.montest;
                    var btn = {
                        target: self.processInverseGeododing,
                        display: true,
                        text: "Utiliser ces informations"
                    };
                    self.modal.btn = btn;
                    self.modal.show();
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

        $scope.showList = function(){
            //self.place_panel = "";
            self.slider = "";
        }

        $scope.showDetails = function(){
            //self.place_panel = "flipped";
            //console.log("SHOWDETAILS");
            $scope.slider = "details";
            //self.safeApply();
        }

        $scope.addLocation = function(){
            console.log("Add LOCATION !");
            self.editStep = 1;
            var place = {
                id:"temp",
                location:{
                    latitude:45.528293,
                    longitude:-73.59246
                },
                categories:{
                    primary_category:{},
                    secondary_category:{}
                },
                tags:{}
            };
            self.mode = "edit";
            self.locationPanel = "show-form";
            // Tags
            var dataset = _.find(self.datasets, function(item){ return self.selectedDataset == item.id; });
            _.each(dataset.dataset_extra_fields, function(item){
                place.tags[item.field] = "";
            });

            self.place = place;
            self.places.push(place);
            self.selectedCategorie = [];

            self.safeApply();
            //self.getMapCenter();
            //self.mode ="edit";
            //self.showDetails();

        }

        $scope.toStepTwo = function () {
            self.editStep = 2;
        };

        $scope.showForm = function () {
            if(self.locationPanel == "show-form"){
                self.addLocation();
            }else{
                self.locationPanel = "show-form";
                setTimeout(self.addLocation,500);
            }

        };
        $scope.hideForm = function () {
            self.locationPanel = "hide-form";
        };

        $scope.getScope = function(){
            console.log($scope);
        }

        $scope.processInverseGeododing = function () {
            console.log("YES!!!!!!");

            var invGeo = self.inverseGeocode;
            var location_type = invGeo.geometry.location_type;
            var address_components = invGeo.address_components;

            var street_number = "";
            var route = "";

            _.each(address_components, function(item){
                console.log(item.types[0] + " >> " + item.long_name);
                if( item.types[0] === "postal_code"){
                    self.place.location.postal_code = item.long_name;
                }

                if( item.types[0] === "locality"){
                    self.place.location.city = item.long_name;
                }

                if( item.types[0] === "street_number"){
                    street_number = item.long_name;
                }

                if( item.types[0] === "route"){
                    route = item.long_name;
                }
            });

            self.place.location.address = street_number + " " + route;
            self.place.location.service = "Google";
            self.place.location.location_type = location_type;
            self.modal.hide();
            self.safeApply();
        };

        //OBSERVERS
        $scope.$watch('place', function(newValue){
            if($scope.mode == "edit"){
                console.log(">> WATCHER > place ");
            }
        },true);

/*        $scope.$watch( function(newV){
            console.log("WATCHER > DIGEST");
            console.log(newV);
        });*/
    });