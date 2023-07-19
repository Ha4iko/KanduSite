<?php

use frontend\models\BracketGroupParticipantsForm;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

/** @var $this View */
/** @var $model BracketGroupParticipantsForm */


?>
<table>

    <thead>
    <tr>
        <th>
            <div class="checkbox">
                <label class="checkbox-label" for="insertParticipantsAll">
                    <input class="checkbox-input" type="checkbox" id="insertParticipantsAll">
                    <div class="checkbox-content">
                        <div class="checkbox-style"></div>
                        <div class="checkbox-text h6">select all</div>
                    </div>
                </label>
            </div>
        </th>
        <th>Nickname</th>
        <th>class</th>
    </tr>
    </thead>

    <tbody>
    <?php foreach ($tournamentParticipants as $participantId => $participant) :
        $isAttached = isset($attachedParticipantsIds[$participantId]); ?>
        <tr <?= $isAttached ? '' : 'class="disabled"' ?> data-checkbox>
            <td>
                <div class="checkbox checkbox--toggler">
                    <label class="checkbox-label" for="insertParticipants<?= $participantId ?>">
                        <input class="checkbox-input" type="checkbox" id="insertParticipants<?= $participantId ?>"
                               name="<?= 'Participant[' . $participantId . '][active]' ?>"
                            <?= $isAttached ? 'checked="checked"' : '' ?>>

                        <div class="checkbox-content">
                            <div class="checkbox-style"></div>
                            <div class="checkbox-text h6" data-text-in="enabled" data-text-out="disabled">
                                <?= $isAttached ? 'enabled' : 'disabled' ?>
                            </div>
                        </div>
                    </label>
                </div>
            </td>

            <td>
                <?= Html::hiddenInput('Participant[' . $participantId . '][id]', $participantId, []) ?>
                <div class="table-player">
                    <div class="table-player__avatar">
                        <img src="<?= $participant['player_avatar'] ?>" alt="">
                    </div>
                    <div class="table-player__name js-filter-by">
                        <?= Html::encode($participant['player_nick']) ?>
                    </div>
                </div>
            </td>

            <td><?= Html::encode($participant['player_class']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>