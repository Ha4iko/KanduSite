<?php

use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\Tournament;
use yii\web\View;
use frontend\widgets\LatestTournamentsWidget;
use frontend\models\Bracket;

/** @var $this View */
/** @var $action string */
/** @var $content string */
/** @var $tournament Tournament */
/** @var $brackets Bracket[] */

$action = $this->params['action'];
$tournament = $this->params['tournament'];
/* @var Bracket $bracket */
$bracket = $this->params['bracket'];

$canModify = !isset(Yii::$app->params['preview']) && Yii::$app->user->can('updateTournament', ['tournamentId' => $tournament->id]);
?>
<?php $this->beginContent('@frontend/views/layouts/main.php'); ?>

    <main class="main">
        <section class="section section--head">
            <div class="section-bg">
                <div class="section-bg__overlay"><span></span><span></span><span></span></div>
                <div class="section-bg__img" style="background-image: url(<?= $tournament->getThumbnail('bg_image', 1920) ?>)"></div>
            </div>
            <div class="section-inner">
                <div class="container">
                    <div class="section-back">
                        <a class="link-back" href="<?= Url::to(['/tournament/index']) ?>">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>All tournaments
                        </a>
                    </div>
                    <div class="section-title">
                        <h1 class="h2"><?= Html::encode($tournament->title) ?></h1>
                    </div>
                    <div class="infos js-scroll">
                        <div class="infos-inner">
                            <div class="info">
                                <div class="info-icon">
                                    <svg class="icon">
                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-shield"></use>
                                    </svg>
                                </div>
                                <div class="icon-content">
                                    <div class="info-value h6"><?= Html::encode($tournament->typeName) ?></div>
                                    <div class="info-prop prop">type</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6"><?= Yii::$app->formatter->asDate($tournament->date, 'php:j F Y') ?></div>
                                    <div class="info-prop prop">date</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6"><?= Yii::$app->formatter->asTime($tournament->time, 'php:H:i') ?></div>
                                    <div class="info-prop prop">time</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6"><?= Html::encode($tournament->languageName) ?></div>
                                    <div class="info-prop prop">language</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6"><?= Html::encode($tournament->organizerName) ?></div>
                                    <div class="info-prop prop">organizer</div>
                                </div>
                            </div>
                            <div class="info">
                                <div class="icon-content">
                                    <div class="info-value h6"><?= $tournament->statusLabel ?></div>
                                    <div class="info-prop prop">status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="nav js-scroll">
            <div class="container--sm">
                <div class="nav-container">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'brackets' ? 'active' : '' ?>"
                               href="<?= Url::to(['brackets', 'slug' => $tournament->slug]) ?>">
                                brackets
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'participants' ? 'active' : '' ?>"
                               href="<?= Url::to(['participants', 'slug' => $tournament->slug]) ?>">
                                Participants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'schedule' ? 'active' : '' ?>"
                               href="<?= Url::to(['schedule', 'slug' => $tournament->slug]) ?>">
                                Schedule
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'rules' ? 'active' : '' ?>"
                               href="<?= Url::to(['rules', 'slug' => $tournament->slug]) ?>">
                                Rules
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'prizes' ? 'active' : '' ?>"
                               href="<?= Url::to(['prizes', 'slug' => $tournament->slug]) ?>">
                                Prizes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $action === 'media' ? 'active' : '' ?>"
                               href="<?= Url::to(['media', 'slug' => $tournament->slug]) ?>">
                                Media
                            </a>
                        </li>
                    </ul>
                    <?php if (Yii::$app->user->isGuest) : ?>
                    <div class="nav-controls">
                        <a class="btn btn--dark" href="<?= Url::to(['/site/contacts']) ?>">
                            how to register
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        $bracketsOfTournament = $tournament->brackets;
        if (($action === 'brackets' && $canModify) || ($action === 'brackets' && $bracketsOfTournament)) : ?>
        <div class="nav nav--sm js-scroll">
            <div class="container--sm">
                <div class="nav-container">
                    <ul class="nav-list">
                        <?php foreach ($bracketsOfTournament as $bracketCurrent) :
                            $updateBracketRoute = ['/bracket/update-bracket-table', 'id' => $tournament->id, 'bracketId' => $bracketCurrent->id];
                            if ($bracketCurrent->bracket_type == Bracket::TYPE_RELEGATION) {
                                $updateBracketRoute = ['/bracket-relegation/update-bracket', 'id' => $tournament->id, 'bracketId' => $bracketCurrent->id];
                            }
                            if ($bracketCurrent->bracket_type == Bracket::TYPE_GROUP) {
                                $updateBracketRoute = ['/bracket-group/update-bracket', 'id' => $tournament->id, 'bracketId' => $bracketCurrent->id];
                            }
                            if ($bracketCurrent->bracket_type == Bracket::TYPE_SWISS) {
                                $updateBracketRoute = ['/bracket-swiss/update-bracket', 'id' => $tournament->id, 'bracketId' => $bracketCurrent->id];
                            }
                            if ($bracketCurrent->bracket_type == Bracket::TYPE_TABLE) {
                                $updateBracketRoute = ['/bracket-table/update-bracket-table', 'id' => $tournament->id, 'bracketId' => $bracketCurrent->id];
                            }
                        ?>
                            <li class="nav-item">
                                <?php if ($canModify) : ?>
                                <div class="nav-drop <?= $bracketCurrent->id == $bracket->id ? 'active' : '' ?>">
                                    <div class="dropdown dropdown--xs">
                                        <div class="dropdown-result">
                                            <a class="dropdown-result__text" href="<?= Url::to(['/tournament/brackets', 'slug' => $tournament->slug, 'id' => $bracketCurrent->id]) ?>">
                                                <?= Html::encode($bracketCurrent->title) ?>
                                            </a>
                                            <div class="dropdown-result__icon js-dropdown-btn">
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
                                                        <a class="dropdown-link js-ajax-popup" href="#" data-pjax="0"
                                                            data-url="<?= Url::to($updateBracketRoute) ?>">
                                                            Edit bracket
                                                        </a>
                                                    </li>
                                                    <li class="dropdown-item">
                                                        <a class="dropdown-link js-ajax-popup" href="#" data-pjax="0"
                                                            data-url="<?= Url::to(['/bracket/delete-bracket', 'id' => $bracketCurrent->id]) ?>">
                                                            Delete bracket
                                                        </a>
                                                    </li>
                                                    <li class="dropdown-item">
                                                        <a class="dropdown-link js-ajax-popup" href="#" data-pjax="0"
                                                            data-url="<?= Url::to(['/bracket/clone-bracket', 'id' => $bracketCurrent->id]) ?>">
                                                            Clone bracket from current
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php else : ?>
                                <a class="nav-link <?= $bracketCurrent->id == $bracket->id ? 'active' : '' ?>" href="<?= Url::to(['/tournament/brackets', 'slug' => $tournament->slug, 'id' => $bracketCurrent->id]) ?>">
                                    <?= Html::encode($bracketCurrent->title) ?>
                                </a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                        <?php if ($canModify) : ?>
                        <div class="nav-btns">
                            <a class="btn btn--sm js-ajax-popup" href="#"
                               data-url="<?= Url::to(['bracket/select-type', 'id' => $tournament->id]) ?>">
                                add bracket
                            </a>
                        </div>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($bracket->bracket_type == Bracket::TYPE_RELEGATION) : ?>
        <div class="nav nav--sm js-scroll fullscreen-panel" id="fullscreen-panel">
            <div class="container--sm">
                <div class="nav-container">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="nav-link js-tab-btn active" href="#bracket1" data-tab="bracket1">Main bracket</a>
                        </li>

                        <?php if ($bracket->second_defeat) : ?>
                        <li class="nav-item">
                            <a class="nav-link js-tab-btn" href="#bracket2" data-tab="bracket2">Defeat bracket</a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link js-tab-btn" href="#bracket3" data-tab="bracket3">grand final</a>
                        </li>
                    </ul>
                    <div class="nav-controls">
                        <a class="btn btn--icon js-fullscreen" href="#" data-fullscreen-panel="fullscreen-panel" data-fullscreen-content="fullscreen-content">
                            <span class="btn-icon">
                                <span class="btn-icon--default">
                                    <svg class="icon">
                                      <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-resize-open"></use>
                                    </svg>
                                </span>
                                <span class="btn-icon--active">
                                    <svg class="icon">
                                      <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-resize-close"></use>
                                    </svg>
                                </span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($bracket->bracket_type == Bracket::TYPE_GROUP || $bracket->bracket_type == Bracket::TYPE_SWISS) : ?>
        <div class="nav nav--sm js-scroll">
            <div class="container--sm">
                <div class="nav-container">
                    <ul class="nav-list">
                        <?php $bracketRounds = $bracket->rounds;
                        foreach ($bracketRounds as $k => $round) : ?>
                        <li class="nav-item">
                            <a class="nav-link js-tab-btn <?= !$k ? 'active' : '' ?>" href="#round<?= $round->order ?>" data-tab="round<?= $round->order ?>">
                                <?= Html::encode($round->title) ?>
                            </a>
                        </li>
                        <?php endforeach; ?>

                        <?php if ($bracketRounds) : ?>
                        <li class="nav-item">
                            <a class="nav-link js-tab-btn" href="#standings" data-tab="standings">standings</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <section class="section section--main section--sm">
            <div class="section-bg">
                <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
            </div>
            <div class="section-inner">
                <?php if (
                    $action === 'schedule' ||
                    ($bracket && $bracket->bracket_type == Bracket::TYPE_RELEGATION)
                ) : ?>
                    <?= $content ?>
                <?php else : ?>
                    <div class="container--sm">
                        <?= $content ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>


        <?php if ($action === 'schedule') : ?>
        <section class="section section--sm">
            <div class="section-bg">
                <div class="section-bg__img" style="background-image: url(<?= IMG_ROOT ?>/bg5.jpg)"></div>
            </div>
            <div class="section-inner">
                <div class="section-head">
                    <div class="container--sm">
                        <div class="section-head__container">
                            <h2 class="h3 section-head__title">other tournaments</h2>
                            <a class="section-head__link" href="<?= Url::to(['/tournament/index']) ?>">All tournaments
                                <svg class="icon">
                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="section-content">
                    <div class="container">
                        <div class="tourneys js-scroll">
                            <div class="tourneys-inner">
                                <?= LatestTournamentsWidget::widget(['limit' => 3, 'excludeIds' => [$tournament->id]]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </main>


<?php $this->endContent(); ?>