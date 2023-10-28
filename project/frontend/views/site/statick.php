<?php
use frontend\assets\DatepickerWithClearAsset;

$this->title = 'OTK HARDCORE MAK GORA';

DatepickerWithClearAsset::register($this);
?>




    <main class="main">
        <section class="section section--head">
            <div class="section-bg">
                <div class="section-bg__overlay"><span></span><span></span><span></span></div>
                <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg14.jpg)"></div>
            </div>
            <div class="section-inner">
                <div class="container">
                    <div class="section-back">
                        <a class="link-back" href="/tournaments">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>All tournaments
                        </a>
                    </div>
                    <div class="section-title">
                        <h1 class="h2">1234</h1>
                    </div>
                    <div class="infos js-scroll" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 0px;">
                        <div class="infos-inner">
                            <div class="info">
                                <div class="info-icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-shield"></use>
                                    </svg>
                                </div>
                                <div class="icon-content">
                                    <div class="info-value h6">1 vs 1</div>
                                    <div class="info-prop prop">type</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6">8 March 2023</div>
                                    <div class="info-prop prop">date</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6">03:00</div>
                                    <div class="info-prop prop">time</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6">English</div>
                                    <div class="info-prop prop">language</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6">root</div>
                                    <div class="info-prop prop">organizer</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6">In progress</div>
                                    <div class="info-prop prop">status</div>
                                </div>
                            </div>
                        </div>
                    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 34px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div></div>
                </div>
            </div>
        </section>
          
		<div class="nav js-scroll">
            <div class="container--sm">
                <div class="nav-container">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="nav-link "
                               href="statickbrackets">
                                brackets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"
                               href="statickParticipents">
                                Participants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="statickSchedule">
                                Schedule
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "
                               href="statikRules">
                                Rules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "
                               href="statikPrizes">
                                Prizes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link "
                               href="statickMedia">
                                Media
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
			  
        <section class="section section--main section--sm">
            <div class="section-bg">
                <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
            </div>
                <div class="section-inner">
                    <div class="container--sm">                      
                        <div class="participants">

						
						<!-- Шаблон --> 
                            <div class="participant">
                                <div class="participant-avatar">
                                    <picture>
                                        <source srcset="AVATAR">
                                        <img src="AVATAR" alt="">
                                    </picture>
                                </div>
                                <div class="participant-content">
                                    <div class="participant-title h6" style="color: COLOR !important;">name</div>
                                    <div class="participant-prop">class</div>
                                </div>
                            </div>

                        </div>						
<div class="share">
    <div class="share-title h6">Share this event</div>
    <ul class="share-list">
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Flocalhost%3A9020%2Ftournaments%2F1234%2Fparticipants">
                facebook
            </a>
        </li>
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window" href="http://vk.com/share.php?url=http%3A%2F%2Flocalhost%3A9020%2Ftournaments%2F1234%2Fparticipants">
                vk
            </a>
        </li>
        <li class="share-item">
            <a target="_blank" rel="nofollow" class="share-link js-target-window" href="http://twitter.com/share?url=http%3A%2F%2Flocalhost%3A9020%2Ftournaments%2F1234%2Fparticipants">
                twitter
            </a>
        </li>
    </ul>
    <div class="share-drop">
        <div class="dropdown dropdown--sm">
            <div class="dropdown-result js-dropdown-btn">
                <div class="dropdown-result__text">more</div>
                <div class="dropdown-result__icon">
                    <svg class="icon">
                        <use href="/assets/57e1fb5e/sprites/main.symbol.svg#image-chevron"></use>
                    </svg>
                </div>
            </div>
            <div class="dropdown-box bottom" style="">
                <div class="close js-close">
                    <div class="close-inner">
                        <div class="close-icon">
                            <svg class="icon">
                                <use href="/assets/57e1fb5e/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                        </div>
                        <div class="close-text">close</div>
                    </div>
                </div>
                <div class="dropdown-box__inner">
                    <ul class="dropdown-items">
                        <li class="dropdown-item">
                            <a target="_blank" rel="nofollow" class="dropdown-link share-link js-target-window" href="tg://msg?text=http%3A%2F%2Flocalhost%3A9020%2Ftournaments%2F1234%2Fparticipants">
                                telegram
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>     
</main>
</body></html>
