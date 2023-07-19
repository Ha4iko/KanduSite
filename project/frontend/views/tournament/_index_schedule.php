<?php

use yii\helpers\Html;
use yii\web\View;
use common\services\ScheduleService;

/* @var $this View */
/* @var $scheduleService ScheduleService */
/* @var $schedules array */

$days = $schedules['days'];
$chartEvents = $schedules['chartEvents'];
$scheduleTournaments = $schedules['scheduleTournaments'];
$periodStartDay = $schedules['periodStartDay'];
$periodDays = $schedules['periodDays'];

if (count($scheduleTournaments)) :
?>

<section class="section">
    <div class="section-inner">
        <div class="schedule">

            <div class="schedule-top">
                <div class="container--sm">

                    <h2 class="schedule-title">schedule tournaments</h2>
                    <div class="schedule-nav fullscreen-panel" id="fullscreen-panel">
                        <div class="schedule-nav__inner">
                            <div class="schedule-nav__content">

                                <div class="schedule-nav__items">
                                    <div class="schedule-nav__item">
                                        <div class="checkbox checkbox--indicator-green">
                                            <label class="checkbox-label" for="schedule1">
                                                <input class="checkbox-input" type="checkbox" id="schedule1" data-type="green" checked="checked">
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">Primary tournaments</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="schedule-nav__item">
                                        <div class="checkbox checkbox--indicator-purple">
                                            <label class="checkbox-label" for="schedule2">
                                                <input class="checkbox-input" type="checkbox" id="schedule2" data-type="purple" checked="checked">
                                                <div class="checkbox-content">
                                                    <div class="checkbox-style"></div>
                                                    <div class="checkbox-text h6">non-primary tournaments</div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="schedule-nav__resize">
                                    <a class="btn btn--icon js-fullscreen"
                                       href="#" data-fullscreen-panel="fullscreen-panel" data-fullscreen-content="fullscreen-content">
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

                </div>
            </div>

            <div class="schedule-content fullscreen-content" id="fullscreen-content">
                <div class="container">
                    <div class="schedule-chart">
                        <div class="chart">

                            <div class="chart-sidebar">
                                <?php foreach ($scheduleTournaments as $tour) : ?>
                                <a class="chart-sidebar__item" href="<?= $tour->url ?>">
                                    <div class="h6 chart-sidebar__item-title">
                                        <span class="<?= $tour->is_primary ? 'primary' : 'purple' ?>">
                                            <?= Html::encode($tour->organizerNick) ?>
                                        </span>
                                        <?= Html::encode($tour->title) ?>
                                    </div>
                                    <div class="prop chart-sidebar__item-prop">
                                        <?= Html::encode($tour->typeName) ?>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            </div>

                            <div class="chart-main">
                                <div class="chart-inner">
                                    <div class="chart-head">
                                        <div class="chart-head__inner">
                                            <div class="chart-months">
                                                <?php foreach ($days as $month => $daysOfMonth) : ?>
                                                <div class="chart-month">
                                                    <div class="chart-month__title"><?= $month ?></div>
                                                    <div class="chart-month__days">
                                                        <?php foreach ($daysOfMonth as $day => $char) : ?>
                                                        <div class="chart-day">
                                                            <div class="chart-day__num"><?= $day ?></div>
                                                            <div class="chart-day__name"><?= $char ?></div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="chart-content">
                                        <div class="chart-content__inner">

                                            <div class="chart-indicators">
                                                <?php foreach ($days as $month => $daysOfMonth) : ?>
                                                    <?php foreach ($daysOfMonth as $day => $char) : ?>
                                                        <div class="chart-indicator"></div>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                            </div>

                                            <div class="chart-rows">
                                                <?php foreach ($scheduleTournaments as $tour) :
                                                    $colorClass = $tour->is_primary ? 'primary' : 'purple';
                                                    $tourType = $tour->typeName;
                                                    $tourDate = Yii::$app->formatter->asDate($tour->date, 'php:j F Y');
                                                    $tourTime = Yii::$app->formatter->asTime($tour->time, 'php:H:i');
                                                    $tourStart = $chartEvents[$tour->date] ?? 0;
                                                    $tourDuration = 5;
                                                    $tourHasFinalDate = false;
                                                    if ($tour->date_final) {
                                                        $begin = new \DateTime(date('Y-m-d', max(strtotime($tour->date), time() - 60 * 60 * 24 * abs($periodStartDay))));
                                                        $end = new \DateTime(date('Y-m-d', min(strtotime($tour->date_final), time() + 60 * 60 * 24 * abs($periodDays))));
                                                        $diff = $begin->diff($end);
                                                        $diffDays = intval($diff->format('%R%a'));
                                                        if ($diffDays > 0) {
                                                            $tourDuration = $diffDays;
                                                            $tourHasFinalDate = true;
                                                        }
                                                    }
$divTour = <<<DIV
<div class="chart-drop">
    <div class="chart-drop__title h6">
        <span class="{$colorClass}">{$tour->organizerNick}</span> {$tour->title}
    </div>
    <div class="chart-drop__items">
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">Type</div>
            <div class="chart-drop__value prop">{$tourType}</div>
        </div>
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">Date</div>
            <div class="chart-drop__value prop">{$tourDate}</div>
        </div>
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">Time</div>
            <div class="chart-drop__value prop">{$tourTime}</div>
        </div>
DIV;
if (trim($tour->prize_one)) {
    $prizeMoney = Html::encode($tour->prize_one);
    $prizeDescription = Html::encode($tour->getAttributeLabel('prize_one'));
$divTour .= <<<DIV
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">{$prizeDescription}</div>
            <div class="chart-drop__value prop">{$prizeMoney}</div>
        </div>
DIV;
}
if (trim($tour->prize_two)) {
    $prizeMoney = Html::encode($tour->prize_two);
    $prizeDescription = Html::encode($tour->getAttributeLabel('prize_two'));
$divTour .= <<<DIV
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">{$prizeDescription}</div>
            <div class="chart-drop__value prop">{$prizeMoney}</div>
        </div>
DIV;
}
if (trim($tour->prize_three)) {
    $prizeMoney = Html::encode($tour->prize_three);
    $prizeDescription = Html::encode($tour->getAttributeLabel('prize_three'));
$divTour .= <<<DIV
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">{$prizeDescription}</div>
            <div class="chart-drop__value prop">{$prizeMoney}</div>
        </div>
DIV;
}
if (trim($tour->prize_four)) {
    $prizeMoney = Html::encode($tour->prize_four);
    $prizeDescription = Html::encode($tour->getAttributeLabel('prize_four'));
$divTour .= <<<DIV
        <div class="chart-drop__item">
            <div class="chart-drop__prop prop">{$prizeDescription}</div>
            <div class="chart-drop__value prop">{$prizeMoney}</div>
        </div>
DIV;
}
$divTour .= <<<DIV
    </div>
</div>
DIV;
                                                ?>

                                                <div class="chart-row">
                                                    <a href="<?= $tour->url ?>" class="chart-event chart-event--<?= $tour->is_primary ? 'green' : 'purple' ?> <?= $tourHasFinalDate ? '' : 'chart-event--half' ?>"
                                                         data-start="<?= $tourStart ?>"
                                                         data-duration="<?= $tourDuration ?>"
                                                         data-type="<?= $tour->is_primary ? 'green' : 'purple' ?>"
                                                         data-content="<?= htmlspecialchars($divTour) ?>">
                                                    </a>
                                                </div>

                                                <?php endforeach; ?>
                                            </div>

                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>