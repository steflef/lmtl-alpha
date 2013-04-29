<? require 'header_v1.php' ?>

    <div class="content w-scrollbar perspective">
    <section ng-controller="CollectCtrl" ng-init="init()">
    <div class="ui-overlay" style="display: none;" ng-show="modal.display"
         ng-animate="{show: 'panelIn', hide: 'fadeOut'}"></div>
    <!-- Modal -->
    <div id="myModal" ng-show="modal.display" ng-animate="{show: 'fadeIn', hide: 'fadeOut'}" class="modal"
         style="z-index: 3000;display: none;" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" ng-click="modal.hide()">×</button>
            <h4>{{modal.header.title}}</h4>
        </div>
        <div class="modal-body">
            <p><strong>{{modal.body.head}}</strong></p>

            <p ng-repeat="item in modal.body.content">{{item}}</p>
        </div>
        <div class="modal-footer">
            <button ng-click="modal.btn.target()" ng-show="modal.btn.display" class="btn btn-small">{{modal.btn.text}}
            </button>
        </div>
    </div>

    <!-- Datasets Panel -->
    <div ng-class="ui.states.datasetsPanel" class="datasets_ui">
        <div class="datasets_panel">
            <div class="datasets_list" ng-show="(datasets.getFeatures().length > 0)">
                <div class="row">
                    <div class="span9">
                        <p class="lead" style="margin-left: 4px;">Jeux de données</p>
                    </div>
                </div>
                <div class="row">
                    <div class="span5"
                         style="border: none;"
                         ng-repeat="dataset in datasets.getFeatures() | filter:query | orderBy:updated_at:reverseCheck"
                         ng-animate="{enter: 'placeIn'}">

                        <div class="{{dataset.id}} fbm watcher-well test-{{dataset.id|number}}">
                            <dl id="_{{dataset.id}}" class="event fs call" style="border: none;">
                                <dd>
                                    <a href="#"
                                       onclick="return false;"
                                       ng-click="places.getPlaces(dataset.id)"
                                       class="bold"
                                       style="font-size: 15px;">{{dataset.properties.name}} ({{dataset.id|number}})</a>
                                </dd>
                                <dd>
                                    <small>Dernière mise à jour {{dataset.properties.updated_at | date:'MM/dd/yyyy @ h:mma'}}</small>
                                </dd>
                                <dd ng-show="(dataset.count > 0)"><span
                                        class="bold">{{dataset.properties.count | number}} lieux</span></dd>
                                <dd ng-show="(dataset.count < 1)"><span class="bold">Aucun lieu pour l'instant</span>
                                </dd>
                                <dd class="c-desc">

                                    {{dataset.properties.description}}

                                </dd>
                                <dd>Catégories<br>
                                    <span style="margin-right: 8px;" class="label" ng-show="(dataset.properties.primary_category_id != 0)">{{categories.obj[dataset.properties.primary_category_id].fr}}</span>
                                    <span style="margin-right: 8px;" class="label" ng-show="(dataset.properties.secondary_category_id != 0)">{{categories.obj[dataset.properties.secondary_category_id].fr}}</span>
                                    <span style="margin-right: 8px;" class="label" ng-show="(dataset.properties.tertiary_category_id != 0)">{{categories.obj[dataset.properties.tertiary_category_id].fr}}</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- List/Details Panel -->
    <section>
        <div class="location-toolbar">
            <ul>
                <li style="float: right;">{{datasets[0].name}}</li>
                <li><a href="#" onclick="return false;" ng-click="ui.showDatasets()"><i class="icon-th-large icon-white"></i> Menu</a></li>
                <li><a href="#" onclick="return false;" ng-click="ui.showForm()"><i class="icon-plus icon-white"></i> Ajouter un lieu</a></li>
                <li ng-show="isMobile.any()"><a href="#" onclick="return false;" ng-click="geoLocation()"><i class="icon-map-marker icon-white"></i> Ma localisation</a></li>
                <li><a href="#" onclick="return false;" ng-show="(ui.states.mode=='read')" ng-click="ui.toggleMode()"><span class="badge badge-inverse">Mode Lecture</span></a></li>
                <li><a href="#" onclick="return false;" ng-show="(ui.states.mode=='edit')" ng-click="ui.toggleMode()"><span class="badge badge-info">Mode Edition</span></a></li>
                <li><a href="#" onclick="return false;"  ng-click="getScope()"><span class="badge badge-info">SCOPE</span></a></li>
            </ul>
        </div>

        <div class="panels">

            <!-- Places List -->
            <div class="places" ng-class="ui.states.slider">
                <div ng-show="(places.features.length == 0)" class="no-places">
                    <p>Aucun lieu dans ce jeu de données. <br>À vous de jouer!</p>
                    <p>
                        <a href="#" onclick="return false;"  ng-click="addLocation()" style="padding-top: 20px;">Ajouter un lieu</a>
                    </p>
                </div>
                <oneway>PLACES</oneway>
            </div>

            <!-- Details READ/EDIT -->
            <div class="flip-container">
                <div id="panel" ng-class="ui.states.mode">

                <!-- READ Details -->
                <div class="place-read face" >
                    <div class="hero-unit">
                        <div class="top-header">
                            MODE LECTURE
                            <button class="close" onclick="return false;" ng-click="ui.showList()">&times;</button>
                            <div class="flip-btn-edit">
                                <a href="#" onclick="return false;" ng-click="ui.toggleMode()">
                                    <span class="badge"><i class="icon-retweet icon-white"></i> MODE ÉDITION</span>
                                </a>
                            </div>
                        </div>
                        <div ng-hide="(place.feature.id)"
                             ng-animate="{show: 'fadeIn', hide: 'fadeOut'}"
                             class="place-load">
                            CHARGEMENT EN COURS<img src="./public/img/place-loader.gif" alt="loader-img"/>
                        </div>
                        <div class="read-panel">
                            <div ng-show="(place.feature.id)" ng-animate="{show: 'placeIn'}" class="place-details-content">
                                <h4>{{place.feature.properties.name}}</h4>
                                <p>{{place.feature.properties.description}}</p>
                                <ul>
                                    <li class="level2-list">
                                        <small>iD<br>{{place.feature.id}}</small>
                                    </li>
                                </ul>
                                <hr>
                                <div>
                                    <p>Localisation
                                        <button class="btn btn-mini btn-info pull-right" ng-click="setMapCenter()"><i
                                                class="icon-map-marker icon-white"></i></button>
                                    </p>
                                    <ul>
                                        <li class="level2-list">
                                            <small>Adresse<br>{{place.feature.properties.address}}</small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Ville<br>{{place.feature.properties.city}}</small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Latitude<br>{{place.feature.geometry.coordinates[1]}}</small>
                                        </li>
                                        <li class="level2-list" >
                                            <small>Longitude<br>{{place.feature.geometry.coordinates[0]}}</small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Code postal<br>{{place.feature.properties.postal_code}}</small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div>
                                    <p>Contacts</p>
                                    <ul>
                                        <li class="level2-list">
                                            <small>Téléphone<br>
                                                {{place.feature.properties.tel_number}}
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Site Web<br>
                                                {{place.feature.properties.website}}
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div>
                                    <p>Attributs</p>
                                    <ul>
                                        <li class="level2-list" ng-show="(val.length > 1)" ng-repeat="(tag, val) in place.feature.properties.tags track by $id($index)">
                                            <small>{{tag}}<br>{{val}} </small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="bar">
                                    <p>Catégories</p>
                                    <span class="label">{{categories.obj[place.feature.properties.primary_category_id].fr}}</span>
                                    <span class="label">{{categories.obj[place.feature.properties.secondary_category_id].fr}}</span>
                                </div>
                                <br>
                                <button class="btn btn-mini" ng-click="getScope()">SCOPE</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EDIT Details -->
                <div class="place-edit face">
                    <div class="hero-unit">
                        <div class="top-header">
                            MODE EDITION
                            <button class="close" onclick="return false;" ng-click="ui.showList()">&times;</button>
                            <div class="flip-btn">
                                <a href="#" onclick="return false;" ng-click="ui.toggleMode()">
                                    <span class="badge"><i class="icon-retweet icon-white"></i> MODE LECTURE</span>
                                </a>
                            </div>
                        </div>
                        <div ng-hide="(place.feature.id)" ng-animate="{show: 'fadeIn', hide: 'fadeOut'}" class="place-load">
                            CHARGEMENT EN COURS<img src="./public/img/place-loader.gif" alt="loader-img"/>
                        </div>
                        <div  class="edit-panel">
                            <div ng-show="((place.feature.id))" ng-animate="{show: 'placeIn'}" class="place-details-content ">

                                <div class="save-console">
                                    <div  class="save-console-container">
                                        <div class="save-console-container-sub">
                                            <label class="checkbox" >
                                                <input ng-model="autosave" ng-true-value="YES" ng-false-value="NO" type="checkbox" ng-checked="true">
                                                Sauvegarde automatique
                                            </label>
                                        </div>
                                        <div class="save-btn" ng-show="(autosave == 'NO')">
                                            <button class="btn btn-normal btn-block btn-success">Sauvegarde</button>
                                        </div>
                                    </div>
                                </div>

                                <ul>
                                    <li class="level2-list">
                                        <small>Nom
                                            <br>
                                            <input type="text" ng-model="place.feature.properties.name"/>
                                        </small>
                                    </li>
                                    <li class="level2-list">
                                        <small>Description<br>
                                            <textarea ng-model="place.feature.properties.description" name="" id="" cols="30" rows="5"></textarea>
                                        </small>
                                    </li>
                                </ul>
                                <hr>
                                <div>
                                    <p>Localisation
                                        <button class="btn btn-mini btn-info pull-right" ng-click="setMapCenter()"><i
                                                class="icon-map-marker icon-white"></i></button>
                                        <br>
                                        <button class="btn btn-mini btn-block btn-info"
                                                ng-click="getMapCenter()">Actualiser en fonction de la mire
                                        </button>
                                    </p>
                                    <ul>
                                        <li class="level2-list">
                                            <small>Adresse<br>
                                                <input type="text" ng-model="place.feature.properties.address" updateModelOnBlur/>
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Ville<br>
                                                <input type="text" ng-model="place.feature.properties.city" updateModelOnBlur/>
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Latitude<br>
                                                <input type="text" disabled="disabled" ng-model="place.feature.geometry.coordinates[0]"
                                                       updateModelOnBlur/>
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Longitude<br>
                                                <input type="text" disabled="disabled" ng-model="place.feature.geometry.coordinates[1]"
                                                       updateModelOnBlur/>
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Code postal<br>
                                                <input type="text" ng-model="place.feature.location.postal_code" updateModelOnBlur/>
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div>
                                    <p>Contacts</p>
                                    <ul>
                                        <li class="level2-list">
                                            <small>Téléphone<br>
                                                <input type="text" ng-model="place.feature.properties.tel_number" updateModelOnBlur/>
                                            </small>
                                        </li>
                                        <li class="level2-list">
                                            <small>Site Web<br>
                                                <input type="text" ng-model="place.feature.properties.website" updateModelOnBlur/>
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div>
                                    <p>Attributs</p>
                                    <ul>
                                        <li class="level2-list" ng-repeat="(tag, val) in place.feature.properties.tags track by $id($index)">
                                            <small>{{tag}}
                                                <br>
                                                <input type="text" ng-model="place.feature.properties.tags[tag]" updateModelOnBlur/>
                                            </small>
                                        </li>
                                    </ul>
                                </div>
                                <hr>
                                <div class="bar">
                                    <p>Catégories</p>
                                    <select id="test_chosen"
                                            multiple="multiple"
                                            tabindex="4"
                                            data-placeholder="Sélectionnez les catégories associées"
                                            class="chzn-select"
                                            style="width:100%;"
                                            ng-model="editHash"
                                            ng-options="d.fr group by d.group for d in categories.options"
                                            chosen-rao>
                                    </select>

                                    <button class="btn btn-mini" ng-click="getScope()">$SCOPE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </div>

            <!-- Add Location -->
            <div class="place-new" ng-class="ui.states.locationPanel">
                <div class="hero-unit">
                    <div class="top-header">
                        NOUVEAU LIEU
                        <button class="close" onclick="return false;" ng-click="ui.hideForm()">&times;</button>
                        <div class="step-btn step-btn-one" style="width: 180px;" ng-show="(ui.states.editStep != 2)">
                            <a href="#" onclick="return false;" ng-click="ui.toStepTwo()">
                                <span class="badge">LOCALISATION</span>
                            </a>
                        </div>
                        <div class="step-btn step-btn-one" style="width: 180px;" ng-show="(ui.states.editStep == 2)">
                            <a href="#" onclick="return false;">
                                <span class="badge">MÉTADONNÉES</span>
                            </a>
                        </div>
                    </div>
                    <div  class="edit-panel">
                        <div ng-animate="{show: 'placeIn'}" class="place-details-content">
                            <div class="metadata newLocation">
                                <form name="form" novalidate>
                                    <section ng-show="(ui.states.editStep != 2)">
                                        <button class="btn btn-small btn-block btn-info"
                                                ng-click="place.setLocation()"> <i class="icon-screenshot icon-white" style="margin-top: 1px;"></i> Positionner le lieu en fonction de la mire
                                        </button>
                                    </section>
                                    <section ng-show="(ui.states.editStep == 2)" ng-animate="{show: 'placeIn'}">
                                        <div>
                                            <p>Identification</p>
                                            <ul>
                                                <li class="level2-list">
                                                    <small><span class="warning" ng-show="form.uName.$error.required"><strong>*</strong></span> Nom<br>
                                                        <input type="text" name="uName" ng-model="place.feature.properties.name" required />
                                                    </small>
                                                </li>
                                                <li class="level2-list">
                                                    <small><span class="warning" ng-show="form.uDesc.$error.required"><strong>*</strong></span> Description<br>
                                                        <textarea ng-model="place.feature.properties.description" name="uDesc" id="" cols="30" rows="4" required ></textarea>
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>
                                        <hr>
                                        <div>
                                            <p>Localisation
                                                <button class="btn btn-mini btn-info pull-right hide" ng-click="setMapCenter()"><i
                                                        class="icon-map-marker icon-white"></i></button>
                                                <br>
                                                <button class="btn btn-mini btn-block btn-info"
                                                        ng-click="getMapCenter()"> <i class="icon-screenshot icon-white" style="margin-top: 1px;"></i> Positionner le lieu en fonction de la mire
                                                </button>
                                            </p>
                                            <ul>
                                                <li class="level2-list">
                                                    <small>Adresse<br>
                                                        <input type="text" ng-model="place.feature.properties.address" />
                                                    </small>
                                                </li>
                                                <li class="level2-list">
                                                    <small>Ville<br>
                                                        <input type="text" ng-model="place.feature.properties.city" />
                                                    </small>
                                                </li>
                                                <li class="level2-list">
                                                    <small><span class="warning" ng-show="form.uLat.$error.required"><strong>*</strong></span> Latitude<br>
                                                        <input type="text" name="uLat" ng-model="place.feature.geometry.coordinates[0]" required updateModelOnBlur/>

                                                    </small>
                                                </li>
                                                <li class="level2-list">
                                                    <small><span class="warning" ng-show="form.uLon.$error.required"><strong>*</strong></span> Longitude<br>
                                                        <input type="text" name="uLon" ng-model="place.feature.geometry.coordinates[0]" required updateModelOnBlur/>

                                                    </small>
                                                </li>
                                                <li class="level2-list">
                                                    <small>Code postal<br>
                                                        <input type="text" ng-model="place.feature.properties.postal_code" />
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>
                                        <hr>
                                        <div>
                                            <div>
                                                <p>Contacts</p>
                                                <ul>
                                                    <li class="level2-list">
                                                        <small>Téléphone<br>
                                                            <input type="text" ng-model="place.feature.properties.tel_number"/>
                                                        </small>
                                                    </li>
                                                    <li class="level2-list">
                                                        <small>Site Web<br>
                                                            <input type="text" ng-model="place.feature.properties.website"/>
                                                        </small>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div>
                                                <span class="label" ng-show="form.$valid">VALID</span>
                                            </div>
                                            <hr>


                                            <div class="bar">
                                                <p>Catégories</p>
                                                <select id="new_chosen"
                                                        multiple="multiple"
                                                        tabindex="4"
                                                        data-placeholder="Sélectionnez les catégories associées"
                                                        class="chzn-select"
                                                        style="width:100%;"
                                                            ng-model="newHash"
                                                        ng-options="d.fr group by d.group for d in categories.options"
                                                        chosen-rao>
                                                </select>

                                                <button class="btn btn-mini" ng-click="getScope()">$SCOPE</button>
                                            </div>
                                            <hr>
                                            <div>
                                                <p>Attributs</p>
                                                <ul>
                                                    <li class="level2-list" ng-repeat="tag in place.feature.properties.attributes track by $id($index)">
                                                        <small>{{tag.field}}
                                                            <br>
                                                            <input type="text" ng-model="tag.data" />
                                                        </small>
                                                    </li>
                                                </ul>
                                            </div>
                                            <button class="btn btn-mini" ng-click="postPlace()">POST</button>


                                        </div>
                                    </section>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="map-wrapper" ng-controller="MapCtrl">
            <div>
                <div map id="mapPort"></div>
            </div>
            <div ng-show="((ui.states.mode=='edit'))" class="crosshair"></div>
        </div>
    </section>

    </section>


    </div>
<? require 'footer_v1.php' ?>