<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\assets\WebAsset;

WebAsset::register($this);

$htmlClasses[] = 'cc-' . Yii::$app->controller->id;
$htmlClasses[] = 'ca-' . Yii::$app->controller->action->id;
$htmlClasses = implode(' ', array_merge($htmlClasses, ArrayHelper::getValue($this->params, 'layout.html.class', [])));

$this->params['renderPopup.login'] = Yii::$app->user->isGuest;
$this->params['renderPopup.forgot'] = Yii::$app->user->isGuest;

$this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en" class="<?= $htmlClasses ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="idleague.live" />
    <meta property="og:url" content="https://idleague.live/">
    <meta property="og:image" content="<?= IMG_ROOT ?>/unknown.png">
    <meta charset="utf-8">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="<?= IMG_ROOT ?>/favicons/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= IMG_ROOT ?>/favicons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= IMG_ROOT ?>/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= IMG_ROOT ?>/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= IMG_ROOT ?>/favicons/android-chrome-96x96.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= IMG_ROOT ?>/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= IMG_ROOT ?>/favicons/favicon-16x16.png">
    <link rel="manifest" href="<?= IMG_ROOT ?>/favicons/manifest.json">
    <link rel="yandex-tableau-widget" href="<?= IMG_ROOT ?>/favicons/yandex-browser-manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= IMG_ROOT ?>/favicons/mstile-144x144.png">
    <meta name="msapplication-config" content="<?= IMG_ROOT ?>/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Google tag (gtag.js) --> 
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NMPXF5TNN2"></script> 
    <script> 
       window.dataLayer = window.dataLayer || []; 
       function gtag(){dataLayer.push(arguments);} 
       gtag('js', new Date()); 
 
       gtag('config', 'G-NMPXF5TNN2'); 
    </script>

    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="loader">
    <div class="loader-inner"><img class="loader-img" src="<?= IMG_ROOT ?>/preloader.gif" alt=""></div>
</div>

<?= $this->render('_header') ?>
<?= $content ?>
<?= $this->render('_footer') ?>
<?= $this->render('_popups') ?>

<?= $this->render('_panel', [
    'tournament' => $panelTournament,
    'action' => $panelAction,
]) ?>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();