<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
//bg-light

$controller = Yii::$app->controller;
$module = $controller->module;
$action = $controller->action;
$route = $controller->getRoute();
$user = Yii::$app->user;

$leftMenu1 = [
    // [
    //     'label' => 'Турниры',
    //     'url' => ['/tournament/index'],
    //     'active' => in_array($controller->id, ['tournament']),
    //     'visible' => $user->can('root'),
    // ],
    // [
    //     'label' => 'Игроки',
    //     'url' => ['/player/index'],
    //     'active' => in_array($controller->id, ['player']),
    //     'visible' => $user->can('root'),
    // ],
    // [
    //     'label' => 'Команды',
    //     'url' => ['/team/index'],
    //     'active' => in_array($controller->id, ['team']),
    //     'visible' => $user->can('root'),
    // ],
    // [
    //     'label' => 'Media',
    //     'url' => ['/media/index'],
    //     'active' => in_array($controller->id, ['media']),
    //     'visible' => $user->can('root'),
    // ],
];

$leftMenu2 = [
    [
        'label' => 'Worlds',
        'url' => ['/player-world/index'],
        'active' => in_array($controller->id, ['player-world']),
        'visible' => $user->can('root'),
    ],
    [
        'label' => 'Factions',
        'url' => ['/player-faction/index'],
        'active' => in_array($controller->id, ['player-faction']),
        'visible' => $user->can('root'),
    ],
    [
        'label' => 'Races',
        'url' => ['/player-race/index'],
        'active' => in_array($controller->id, ['player-race']),
        'visible' => $user->can('root'),
    ],
    [
        'label' => 'Classes',
        'url' => ['/player-class/index'],
        'active' => in_array($controller->id, ['player-class']),
        'visible' => $user->can('root'),
    ],

    [
        'label' => 'Types of tournament',
        'url' => ['/tournament-type/index'],
        'active' => in_array($controller->id, ['tournament-type']),
        'visible' => $user->can('root'),
    ],
    // [
    //     'label' => 'Правила',
    //     'url' => ['/tournament-rule/index'],
    //     'active' => in_array($controller->id, ['tournament-rule']),
    //     'visible' => $user->can('root'),
    // ],
    // [
    //     'label' => 'Призы',
    //     'url' => ['/tournament-prize/index'],
    //     'active' => in_array($controller->id, ['tournament-prize']),
    //     'visible' => $user->can('root'),
    // ],
    [
        'label' => 'Languages',
        'url' => ['/language/index'],
        'active' => in_array($controller->id, ['language']),
        'visible' => $user->can('root'),
    ],
    [
        'label' => 'Settings',
        'url' => ['/setting/index'],
        'active' => in_array($controller->id, ['setting']),
        'visible' => $user->can('root'),
    ],
];

$htmlClasses[] = 'h-100';
$htmlClasses[] = 'm-' . $module->id;
$htmlClasses[] = 'c-' . $controller->id;
$htmlClasses[] = 'a-' . $action->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="<?= implode(' ', $htmlClasses) ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => 'Home',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark',
        ],
    ]);
    $menuItems = [
        //['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-4 py-3 content-sidebar">
                <?= Nav::widget([
                    'items' => $leftMenu1,
                    'encodeLabels' => false,
                    'options' => [
                        'class' => 'left-block flex-column nav-pills',
                        //'aria-orientation' => 'vertical',
                    ],
                ]) ?>

                <?= Nav::widget([
                    'items' => $leftMenu2,
                    'encodeLabels' => false,
                    'options' => [
                        'class' => 'left-block flex-column nav-pills mt-3',
                        //'aria-orientation' => 'vertical',
                    ],
                ]) ?>
            </div>
            <div class="col-md-9 col-sm-8 py-3 content-container">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
