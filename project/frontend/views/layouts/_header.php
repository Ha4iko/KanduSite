<?php

use \yii\web\View;
use yii\helpers\Url;

/* @var $this View */

$route = Yii::$app->controller->getRoute();
?>
<header class="header">
    <div class="container">
        <div class="header-container">
            <div class="header-btn js-header-btn">
                <div class="header-btn__inner"><span></span><span></span><span></span></div>
            </div>
            <div class="header-logo"><a class="logo" href="/"><img class="logo-img" src="<?= IMG_ROOT ?>/logo.png" alt=""></a></div>
            <div class="header-drop">
                <div class="close js-close">
                    <div class="close-inner">
                        <div class="close-icon">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </div>
                        <div class="close-text">close</div>
                    </div>
                </div>
                <div class="header-drop__inner">
                    <div class="header-menu">
                        <ul class="header-menu__list">
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'tournament/index' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/tournament/index']) ?>">
                                    Tournaments
                                </a>
                            </li>
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'tournament/champions' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/tournament/champions']) ?>">
                                    Champs
                                </a>
                            </li>
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'site-media/index' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/site-media/index']) ?>">
                                    Media
                                </a>
                            </li>
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'site/contacts' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/site/contacts']) ?>">
                                    Contacts
                                </a>
                            </li>
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'site/donate' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/site/donate']) ?>">
                                    Donate
                                </a>
                            </li>
                            <li class="header-menu__item">
                                <a class="header-menu__link <?= $route == 'site/thanks' ? 'active' : '' ?>"
                                   href="<?= Url::to(['/site/thanks']) ?>">
                                    Thanks
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-controls">
                <div class="header-lang">
                    <div class="dropdown">
                        <div class="dropdown-result js-dropdown-btn">
                            <div class="dropdown-result__text">En</div>
                            <div class="dropdown-result__icon">
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                </svg>
                            </div>
                        </div>
                        <div class="dropdown-box">
                            <div class="close js-close">
                                <div class="close-inner">
                                    <div class="close-icon">
                                        <svg class="icon">
                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                        </svg>
                                    </div>
                                    <div class="close-text">close</div>
                                </div>
                            </div>
                            <div class="dropdown-box__inner">
                                <ul class="dropdown-items">
                                    <?php foreach (Yii::$app->params['languages'] as $language): ?>
                                        <li class="dropdown-item">
                                            <a class="dropdown-link active" href="<?= Url::home() ?>"><?= $language ?></a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-personal">
                    <?php if (Yii::$app->user->isGuest) : ?>
                        <a class="header-menu__link js-popup-open" href="#" data-popup="adminLogin">Login</a>
                    <?php else : ?>
                        <div class="dropdown">
                            <div class="dropdown-result js-dropdown-btn">
                                <div class="dropdown-result__media">
                                    <img class="dropdown-result__bg"
                                         src="<?= Yii::$app->user->identity->avatar ?: (IMG_ROOT . '/logo-big.png') ?>" alt=""/>
                                </div>
                                <div class="dropdown-result__icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                    </svg>
                                </div>
                            </div>
                            <div class="dropdown-box">
                                <div class="close js-close">
                                    <div class="close-inner">
                                        <div class="close-icon">
                                            <svg class="icon">
                                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                            </svg>
                                        </div>
                                        <div class="close-text">close</div>
                                    </div>
                                </div>
                                <div class="dropdown-box__inner">
                                    <ul class="dropdown-items">
                                        <li class="dropdown-item">
                                            <a class="dropdown-link" href="#"><?= Yii::$app->user->identity->email ?></a>
                                        </li>
                                    </ul>
                                    <ul class="dropdown-items">
                                        <?php if (Yii::$app->user->can('createTournament')) : ?>
                                            <li class="dropdown-item">
                                                <a class="dropdown-link js-ajax-popup"
                                                   data-url="<?= Url::to(['/tournament/create']) ?>" href="#">
                                                    Create tournament
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <?php if (Yii::$app->user->can('root')) : ?>
                                            <li class="dropdown-item">
                                                <a class="dropdown-link js-ajax-popup"
                                                   data-url="<?= Url::to(['/site-media/update']) ?>" href="#">
                                                    Create media
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        <li class="dropdown-item">
                                            <a class="dropdown-link" href="<?= Url::to(['/cabinet/tournaments']) ?>">my tournaments</a>
                                        </li>

                                        <?php if (Yii::$app->user->can('root')) : ?>
                                        <li class="dropdown-item">
                                            <a class="dropdown-link" href="<?= Url::to(['/cabinet/index']) ?>">edit users</a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                    <ul class="dropdown-items">
                                        <li class="dropdown-item">
                                            <a class="dropdown-link" href="<?= Url::to(['/cabinet/profile']) ?>">settings</a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a class="dropdown-link" href="<?= Url::to(['/cabinet/logout']) ?>" data-method="post">logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>

