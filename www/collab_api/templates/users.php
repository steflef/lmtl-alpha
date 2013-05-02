<? require 'usersHeader.php' ?>

    <div class="content w-scrollbar perspective">
    <section ng-controller="UserCtrl" ng-init="init()">
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


    <!-- List/Details Panel -->
    <section>

        <!-- Datasets Panel -->
        <div ng-class="ui.states.datasetsPanel" class="datasets_ui">
            <div class="datasets_panel">
                <div class="datasets_list">
                    <div class="row">
                        <div class="span9">
                            <p class="lead" style="margin-left: 4px;">Utilisateurs <a style="font-family: OpenSansBold;
text-decoration: none;" href="#" ng-click="users.create()" onclick="return false;">+</a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="span5"
                             style="border: none;"
                             ng-repeat="user in users.getData() | filter:query | orderBy:role:reverseCheck"
                             ng-animate="{enter: 'placeIn'}">

                            <div class="{{user.id}} fbm watcher-well test-{{user.id|number}}">
                                <div class="pull-right"><button ng-model="checked" class="btn btn-link" ng-hide="checked" ng-click="(checked=true)">Édition</button></div>
                                <div class="pull-right"><button ng-model="checked" class="btn btn-link" ng-show="checked" ng-click="(checked=false)">Lecture</button></div>
                                <dl id="_{{user.id}}" class="event fs call" style="border: none;" ng-hide="checked">
                                    <dd>
                                        <a href="#"
                                           onclick="return false;"
                                           ng-click="user.get(user.id)"
                                           class="bold"
                                           style="font-size: 15px;">{{user.username}}</a>
                                    </dd>
                                    <dd>
                                        <small>Membre depuis {{user.created_at | date:'MM/dd/yyyy @ h:mma'}}</small>
                                    </dd>
                                    <dd>Courriel: {{user.email}}</dd>
                                   <!-- <dd><input type="checkbox" ng-model="checked"></dd>-->

                                </dl>
                                <dl ng-show="checked">
                                    <ng-form name="inner">
                                        <dd><label><span class="warning" ng-show="inner.uName.$error.required"><strong>*</strong></span>Nom d'utilisateur</label>
                                            <input name="uName" type="text" ng-model="user.username" required/>
                                        </dd>
                                        <dd><label><span class="warning" ng-show="inner.email.$error.required"><strong>*</strong></span>Courriel</label>
                                            <input type="email" name="email" ng-model="user.email" required/>
                                            <span class="error" ng-show="inner.email.$error.email">invalide</span>
                                        </dd>

                                        <dd><select name="" id="" ng-model="user.role">
                                                <option value="0">Utilisateur</option>
                                                <option value="1">Administrateur</option>
                                        </select></dd>
                                        <dd>
                                            <button class="btn btn-success btn-mini" ng-show="(inner.$valid)" ng-click="users.put(user.id)">Sauvegarder</button>
                                            <button class="btn btn-danger btn-mini" ng-click="users.delete(user.id)">Supprimer</button>
                                        </dd>
                                    </ng-form>
                                </dl>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>


    </section>

    </section>


    </div>
<? require 'footer.php' ?>