<?php

use common\models\Bracket\Group;
use frontend\models\BracketGroupDuelsForm;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\PlayerClass;

/* @var $this View */
/* @var $model BracketGroupDuelsForm */
/* @var $bracket Group */
/* @var $classIds array */

$standingsGroups = $bracket->getStandings();
$isEven = ! boolval($bracket->best_of % 2);

$isEmpty = true;
foreach ($standingsGroups as $standingsGroup) {
    foreach ($standingsGroup['participants'] as $pId => $standingsParticipant) {
        if (!$standingsParticipant['name']) continue;

        $isEmpty = false;
    }
}

?>
<?php if ($isEmpty) : ?>

    <?= $this->render('@frontend/views/tournament/_list_empty', ['renderInPlace' => true]) ?>

<?php else : ?>

    <?php foreach ($standingsGroups as $standingsGroup) :  ?>

        <div class="table table--center">
            <div class="group-title prop"><?= Html::encode($standingsGroup['title']) ?></div>
            <div class="table-content">
                <div class="table-inner">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="table-col--full table-col--left">
                                    <div class="table-sort">
                                        <div class="table-sort__text">Player</div>
                                        <div class="table-sort__btn"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-sort">
                                        <div class="table-sort__text">win</div>
                                        <div class="table-sort__btn"></div>
                                    </div>
                                </th>
                                <?php if ($isEven) : ?>
                                <th>
                                    <div class="table-sort">
                                        <div class="table-sort__text">tie</div>
                                        <div class="table-sort__btn"></div>
                                    </div>
                                </th>
                                <?php endif; ?>
                                <th>
                                    <div class="table-sort">
                                        <div class="table-sort__text">lose</div>
                                        <div class="table-sort__btn"></div>
                                    </div>
                                </th>
                                <th>
                                    <div class="table-sort">
                                        <div class="table-sort__text">points</div>
                                        <div class="table-sort__btn"></div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0;
                            ArrayHelper::multisort($standingsGroup['participants'], ['points', 'name'], [SORT_DESC, SORT_ASC]);
                            foreach ($standingsGroup['participants'] as $pId => $standingsParticipant) :
                                if (!$standingsParticipant['name']) continue;
                                $i++;
                                $classId = $classIds[$standingsParticipant['id']] ?? 0;
                                $link = PlayerClass::findOne($classId);
                                $class = $link->avatar;
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td class="table-col--full table-col--left"
                                    style="color: <?= $class ?> !important;">
                                    <?php if ($standingsParticipant['external_link']): ?>
                                        <a href="<?= $standingsParticipant['external_link'] ?>" class="no-decor" target="_blank" style="color: <?= $class ?> !important;">
                                            <?= Html::encode($standingsParticipant['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= Html::encode($standingsParticipant['name']) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $standingsParticipant['win'] ?></td>

                                <?php if ($isEven) : ?>
                                <td><?= $standingsParticipant['tie'] ?></td>
                                <?php endif; ?>

                                <td><?= $standingsParticipant['lose'] ?></td>
                                <td><?= $standingsParticipant['points'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php endif; ?>
