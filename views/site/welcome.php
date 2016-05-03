<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Сайт для загрузки документов</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!--[if lte IE 8]><script src="js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="css/ie9.css" /><![endif]-->
</head>
<body class="landing">

<!-- Page Wrapper -->
<div id="page-wrapper">


    <!-- Banner -->
    <section id="banner">
        <div class="inner">
            <h2>Документы</h2>
            <p>Ресурс для загрузки<br />
                 работ<br />
            <ul class="actions">
                <li><a href="<?=\yii\helpers\Url::toRoute('site/login')?>" class="button special">Войти</a></li>
                <li><a href="<?=\yii\helpers\Url::toRoute('site/signup')?>" class="button special">Регистрация</a></li>
            </ul>
        </div>
    </section>

</div>

<!-- Scripts -->
<script src="js/jquery.scrollex.min.js"></script>
<script src="js/jquery.scrolly.min.js"></script>
<script src="js/skel.min.js"></script>
<script src="js/util.js"></script>
<!--[if lte IE 8]><script src="js/ie/respond.min.js"></script><![endif]-->
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<?$this->registerJsFile('@web/js/welcome.js',['position' => yii\web\View::POS_END,'depends' => [\yii\web\JqueryAsset::className()]]);?>

</body>
</html>