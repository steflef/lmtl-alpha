function ToolBarCtrl($rootScope, $scope) {

    var self = $scope;
    $scope.baseUrl = './';
    $scope.appTitle = 'LMTL';
    $scope.selectedIcon = '';
    $scope.selectedItem = '';

    $scope.topmenu = [
        {   id:'tb',
            title:'Tableau de bord',
            url:'',
            icon:'icon-map-marker',
            selected:''
        },
        {   id:'upload',
            title:'Téléchargez!',
            url:'upload',
            icon:'icon-download',
            selected:''
        }
    ];

    $scope.$on('newMenu', function ($scope, route) {
        _.each( self.topmenu, function(item){
            if(item.id === route.id){
                item.selected = 'active';
                self.selectedIcon = item.icon;
                self.selectedItem = item.title;
                self.selectedUrl = item.url;
            }else{
                item.selected = '';
            }
        });
    });
}