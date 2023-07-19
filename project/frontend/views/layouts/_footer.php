<?php

use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
?>
<footer class="footer">
    <div class="container">
        <div class="footer-container">
            <div class="footer-logo"><a class="logo" href="#"><img class="logo-img" src="<?= IMG_ROOT ?>/logo.png" alt=""></a></div>
            <div class="footer-content">
                <div class="footer-menu">
                    <ul class="footer-menu__list">
                        <li class="footer-menu__item">
                            <a class="footer-menu__link" href="<?= Url::to(['/site/contacts']) ?>">Contacts</a>
                        </li>
                        <li class="footer-menu__item">
                            <a class="footer-menu__link" href="<?= Url::to(['/site/terms']) ?>">Terms of services</a>
                        </li>
                        <li class="footer-menu__item">
                            <a class="footer-menu__link" href="<?= Url::to(['/site/privacy']) ?>">Privacy policy</a>
                        </li>
                    </ul>
                </div>
                <div class="footer-controls">
                    <div class="footer-lang">
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
                                        <li class="dropdown-item"><a class="dropdown-link active" href="#">English</a></li>
                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Russian</a></li>
                                        <li class="dropdown-item"><a class="dropdown-link" href="#">Espanyol</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

