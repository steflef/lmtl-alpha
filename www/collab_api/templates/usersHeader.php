<!doctype html>
<html class="no-js" ng-app="appMain">
<head>
    <meta http-equiv="Content-Type" content="charset=utf-8"/>
    <title>LMTL Utilisateurs</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="<?=$base_url?>" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="./public/style/bootstrap.css">

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="./public/imgs/favicon.png">
    <link rel="apple-touch-icon" href="./public/imgs/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="./public/imgs/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="./public/imgs/apple-touch-icon-114x114.png">

    <script type="text/javascript">
        var BASE_URL = '';
        <?if (!empty($user)):?>
        var USER_NAME = '<?=$user?>';
        <?endif;?>
    </script>

  <script type="text/javascript" src="./public/js/libs/angularjs/1.1.4/angular.min.js"></script>
  <script type="text/javascript" src="./public/js/libs/angularjs/1.1.4/angular-sanitize.min.js"></script>
    <script type="text/javascript" src="./public/js/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript" src="./public/js/libs/underscore/1.4.4/underscore-min.js"></script>
    <script type="text/javascript" src="./public/js/ng/ctrl/toolbar.js"></script>
    <script type="text/javascript" src="./public/js/ng/app-users.js"></script>
    <script type="text/javascript" src="./public/js/libs/bootstrap/bootstrap-dropdown.js"></script>
    <script type="text/javascript" src="./public/js/libs/bootstrap/bootstrap-transition.js"></script>
    <script type="text/javascript" src="./public/js/libs/bootstrap/bootstrap-modal.js"></script>

</head>
<body>

<div ng-controller="ToolBarCtrl"><toolbar></toolbar></div>
<noscript>
    <div class="translate" id="noscript">
        <h3>Tu dois activer JavaScript pour naviguer sur cette application</h3>
        <p>Nous utilisons les meilleures technologies de pointe disponibles pour offrir à nos utilisateurs une expérience Web optimale.
            <br/>Il est recommandé d'activer JavaScript dans les paramètres du navigateur pour continuer.
        </p>
        <p class="small">Contactez un administrateur pour plus d'informations</p>
    </div>
</noscript>
