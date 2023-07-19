<?php

use frontend\models\Tournament;
use frontend\models\TournamentToPlayer;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\Player;
use common\models\PlayerRace;
use common\models\PlayerFaction;
use common\models\PlayerClass;
use common\models\PlayerWorld;
use common\helpers\DataTransformHelper;

/** @var $this View */
/** @var $model Tournament */

$nicks = DataTransformHelper::getList(Player::class, 'nick');
$nicksJson = json_encode(array_values($nicks));
$classes = DataTransformHelper::getList(PlayerClass::class, 'name', 'id', '');
//$worlds = DataTransformHelper::getList(PlayerWorld::class, 'name', 'id', '');
$worlds = DataTransformHelper::getList(PlayerWorld::class, 'name');
$worldsJson = json_encode(array_values($worlds));
//$races = DataTransformHelper::getList(PlayerRace::class, 'name', 'id', '');
$races = [null => ''];
foreach (PlayerRace::find()->orderBy('name, gender')->all() as $raceModel) {
    $races[$raceModel->id] = $raceModel->name . ($raceModel->gender == PlayerRace::GENDER_MALE ? ' (Male)' : ' (Female)');
}
$factions = DataTransformHelper::getList(PlayerFaction::class, 'name', 'id', '');

$players = $model->tournamentToPlayer;
$isEmptyParticipants = false;
// if (empty($players)) {
//     $new = new TournamentToPlayer();
//     $new->tournament_id = $model->id;
//     $players[0] = $new;
//
//     $isEmptyParticipants = true;
// }
$jsParticipants = [];
foreach ($nicks as $nick) {
    $jsParticipants[mb_strtolower($nick)] = $nick;
}
$this->registerJsVar('__nicks_all', $jsParticipants);
$this->registerJsVar('__nicks_available', $jsParticipants);
$this->registerJsVar('__worlds', $worlds ? array_values($worlds) : []);

$usedParticipantIds = $model->getParticipantIdsInAllBrackets();
?>
    <div class="popup" id="adminAddParticipants">
        <div class="popup-wrap">
            <div class="popup-main">
                <?php Pjax::begin([
                    'id' => 'participantsForm',
                    'enablePushState' => false,
                    'enableReplaceState' => false,
                ]); ?>
                <?= Html::beginForm(['/participant/update', 'id' => $model->id], 'post', [
                    'data-pjax' => 1,
                    'enctype' => 'multipart/form-data',
                ]); ?>
                <?= Html::hiddenInput($model->formName() . '[id]', $model->id, []) ?>
                <div class="popup-head">
                    <div class="popup-close js-popup-close"><a class="link-back" href="#">
                            <svg class="icon">
                                <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                            </svg>
                            close</a></div>
                    <div class="popup-title h3"><?= $isEmptyParticipants ? 'Add' : 'Edit' ?> Participants <span>/ <?= Html::encode($model->typeName) ?></span>
                    </div>
                </div>
                <div class="popup-content">
                    <div class="content-block">
                        <div class="add append">
                            <table class="append-template" data-append="player">
                                <tr class="append-item" data-append="player">
                                    <td>
                                        <?= Html::hiddenInput('TournamentToPlayer[%i%][id]', null, []) ?>
                                        <?= Html::hiddenInput('TournamentToPlayer[%i%][tournament_id]', $model->id, []) ?>
                                        <div class="add-cell">
                                            <div class="autocomplete">
                                                <?= Html::textInput('TournamentToPlayer[%i%][playerNick]', null, [
                                                    'placeholder' => 'enter',
                                                    'class' => 'field field--md autocomplete-field js-custom-autocomplete'
                                                ]) ?>
                                                <div class="autocomplete-arrow">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList('TournamentToPlayer[%i%][class_id]', null,
                                                        $classes,
                                                        [
                                                            'class' => 'js-select',
                                                            'data-placeholder' => 'choose',
                                                            'data-style' => '2',
                                                            'data-drop' => 'select--md',
                                                        ]
                                                    ) ?>
                                                </div>
                                                <div class="select-drop">
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
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList('TournamentToPlayer[%i%][race_id]', null,
                                                        $races,
                                                        [
                                                            'class' => 'js-select',
                                                            'data-placeholder' => 'choose',
                                                            'data-style' => '2',
                                                            'data-drop' => 'select--md',
                                                        ]
                                                    ) ?>
                                                </div>
                                                <div class="select-drop">
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
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <div class="select select--md">
                                                <div class="select-btn">
                                                    <?= Html::dropDownList('TournamentToPlayer[%i%][faction_id]', null,
                                                        $factions,
                                                        [
                                                            'class' => 'js-select',
                                                            'data-placeholder' => 'choose',
                                                            'data-style' => '2',
                                                            'data-drop' => 'select--md',
                                                        ]
                                                    ) ?>
                                                </div>
                                                <div class="select-drop">
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
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="add-cell">
                                            <div class="autocomplete">
                                                <?= Html::textInput('TournamentToPlayer[%i%][worldName]', null, [
                                                    'placeholder' => 'enter',
                                                    'class' => 'field field--md js-world-autocomplete'
                                                ]) ?>
                                                <div class="autocomplete-arrow">
                                                    <svg class="icon">
                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="add-table__clear">
                                        <div class="clear"><a class="clear-btn js-custom-add-clear" href="#"
                                                              data-append="player">
                                                <svg class="icon">
                                                    <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                </svg>
                                            </a></div>
                                    </td>
                                </tr>
                            </table>
                            <div class="add-table">
                                <div class="table">
                                    <div class="table-content">
                                        <div class="table-inner">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th>player Nickname <span>*</span></th>
                                                    <th>class <span>*</span></th>
                                                    <th>race <span>*</span></th>
                                                    <th>faction</th>
                                                    <th>game world</th>
                                                    <th class="add-table__clear"></th>
                                                </tr>
                                                </thead>
                                                <tbody class="append-wrap" data-append="player">
                                                <?php foreach ($players as $i => $player) : ?>
                                                    <tr class="append-item" data-append="player">
                                                        <td>
                                                            <?= Html::hiddenInput($player->formName() . '[' . $i . '][id]', $player->id, []) ?>
                                                            <?= Html::hiddenInput($player->formName() . '[' . $i . '][tournament_id]', $model->id, []) ?>
                                                            <div class="add-cell <?= $player->hasErrors('playerNick') ? 'error' : '' ?>">
                                                                <div class="autocomplete">
                                                                    <?= Html::textInput($player->formName() . '[' . $i . '][playerNick]', $player->getPlayerNick(), [
                                                                        'placeholder' => 'enter',
                                                                        'class' => 'field field--md autocomplete-field js-custom-autocomplete'
                                                                    ]) ?>
                                                                    <div class="autocomplete-arrow">
                                                                        <svg class="icon">
                                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                                <?= Html::error($player, 'playerNick', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell <?= $player->hasErrors('class_id') ? 'error' : '' ?>">
                                                                <div class="select select--md">
                                                                    <div class="select-btn">
                                                                        <?= Html::dropDownList($player->formName() . '[' . $i . '][class_id]', $player->class_id,
                                                                            $classes,
                                                                            [
                                                                                'class' => 'js-select',
                                                                                'data-placeholder' => 'choose',
                                                                                'data-style' => '2',
                                                                                'data-drop' => 'select--md',
                                                                            ]
                                                                        ) ?>

                                                                    </div>
                                                                    <div class="select-drop">
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
                                                                    </div>
                                                                </div>
                                                                <?= Html::error($player, 'class_id', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell <?= $player->hasErrors('race_id') ? 'error' : '' ?>">
                                                                <div class="select select--md">
                                                                    <div class="select-btn">
                                                                        <?= Html::dropDownList($player->formName() . '[' . $i . '][race_id]', $player->race_id,
                                                                            $races,
                                                                            [
                                                                                'class' => 'js-select',
                                                                                'data-placeholder' => 'choose',
                                                                                'data-style' => '2',
                                                                                'data-drop' => 'select--md',
                                                                            ]
                                                                        ) ?>
                                                                    </div>
                                                                    <div class="select-drop">
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
                                                                    </div>
                                                                </div>
                                                                <?= Html::error($player, 'race_id', ['class' => 'field-error', 'style' => 'color: #DF0D14; display:block;']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell">
                                                                <div class="select select--md">
                                                                    <div class="select-btn">
                                                                        <?= Html::dropDownList($player->formName() . '[' . $i . '][faction_id]', $player->faction_id,
                                                                            $factions,
                                                                            [
                                                                                'class' => 'js-select',
                                                                                'data-placeholder' => 'choose',
                                                                                'data-style' => '2',
                                                                                'data-drop' => 'select--md',
                                                                            ]
                                                                        ) ?>
                                                                    </div>
                                                                    <div class="select-drop">
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
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="add-cell <?= $player->hasErrors('worldName') ? 'error' : '' ?>">
                                                                <div class="autocomplete">
                                                                    <?= Html::textInput($player->formName() . '[' . $i . '][worldName]', $player->worldName, [
                                                                        'placeholder' => 'enter',
                                                                        'class' => 'field field--md js-world-autocomplete'
                                                                    ]) ?>
                                                                    <div class="autocomplete-arrow">
                                                                        <svg class="icon">
                                                                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                                <?= Html::error($player, 'worldName', ['class' => 'field-error']) ?>
                                                            </div>
                                                        </td>
                                                        <td class="add-table__clear">
                                                            <div class="clear">
                                                                <?php if (!isset($usedParticipantIds[$player->id])) : ?>
                                                                <a class="clear-btn js-custom-add-clear"
                                                                                  href="#" data-append="player">
                                                                    <svg class="icon">
                                                                        <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-clear"></use>
                                                                    </svg>
                                                                </a>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-controls">
                                <div class="add-controls__btn">
                                    <a class="btn btn--md js-add-btn" href="#" data-append="player">
                                        add one
                                    </a>
                                </div>

                                <div class="add-controls__main">
                                    <div class="add-controls__divider prop">or</div>
                                    <div class="add-controls__content">
                                        <div class="add-controls__field">
                                            <input class="field field--md js-parser-source" type="text" placeholder="insert link to add">
                                            <div class="js-parser-answer" style="color: #DF0D14; width: 100%;"></div>
                                        </div>
                                        <div class="add-controls__btn">
                                            <button type="button" class="btn btn--md js-parser-runner" data-pjax="0">
                                                Add Data from link
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="a-footer a-footer--start">
                    <button class="btn" type="submit">save and continue</button>
                    <a class="btn js-popup-close" href="#">cancel</a>
                </div>
                <?= Html::endForm(); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
<?php
$popupId = '#participantsForm';

$js = <<<JS
    $(document).on('click', '{$popupId}  .js-add-btn', function() {
        updateParticIndices();
        autocompleteParticipants();
    });

    $(document).on('click', '.js-custom-add-clear', function (e) {
        e.preventDefault();
        var appendItem = $(this).closest(
          '.append-item[data-append=' + $(this).attr('data-append') + ']'
        );
        if (appendItem.is('tr')) {
          appendItem
            .children('td')
            .wrapInner('<div />')
            .children()
            .fadeOut(function () {
              appendItem.remove();
              updateAvailableParticipants();
              updateParticIndices();
            });
          return false;
        } else {
          appendItem.fadeOut(function () {
            appendItem.remove();
            updateAvailableParticipants();
            updateParticIndices();
          });
          return false;
        }
    });
    
    function updateParticIndices() { 
        $('{$popupId} .append-wrap .append-item').each(function(index) {
            $(this).find('input, select').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + index + ']'));
            });
        });
    }
    
    function updateAvailableParticipants() {
        __nicks_available = {};
        for (let key in __nicks_all) {
          __nicks_available[key] = __nicks_all[key]; 
        }
    
        let usedParticipants = {};
        $('.append-wrap input[name$="[playerNick]"]').each(function(index, item) {
            let selectedName = $(this).val();
            usedParticipants[selectedName.toLowerCase()] = selectedName.toLowerCase();
        });  
            
        for (let usedName in usedParticipants) {
            delete __nicks_available[usedName];
        }
    }
    
    $(document).on('blur', '.js-custom-autocomplete', function() {
        updateAvailableParticipants();              
    });
    
    function autocompleteParticipants() {
      $('.js-custom-autocomplete').each(function (i, item) {
        var options = {
          lookup: function(query, done) {
              done({
                  suggestions: Object.values(__nicks_available)
                      .filter(nick => nick.toLowerCase().indexOf(query.toLowerCase()) !== -1)                  
                      .map(nick => ({
                        value: nick
                      }))
              })
          },
          maxHeight: 140,
          minChars: 0,
          onSelect: function (suggestion) {
              updateAvailableParticipants();
          },
          beforeRender: function (container, suggestions) {
              if (window.autocompleteSimplebar) {
                window.autocompleteSimplebar.unMount();
              }
              window.autocompleteSimplebar = new SimpleBar(container.get(0));
              $(item).closest('.autocomplete').addClass('active');
          },
          onHide: function () {
              $(item).closest('.autocomplete').removeClass('active');
          },
        };
        
        $(item).autocomplete(options);
        $(item)
            .closest('.autocomplete')
            .on('click', function () {
                $(item).focus();
                $(item).triggerHandler($.Event('keyup', { keyCode: 65, which: 65 }));
                $(item).trigger('change');
            });
      });
      
      $('.js-world-autocomplete').each(function (i, item) {
        var options = {
          lookup: function(query, done) {
              done({
                  suggestions: __worlds.map(world => ({
                        value: world
                      }))
              })
          },
          maxHeight: 140,
          minChars: 0,
          onSelect: function (suggestion) {
              updateAvailableParticipants();
          },
          beforeRender: function (container, suggestions) {
              if (window.autocompleteSimplebar) {
                window.autocompleteSimplebar.unMount();
              }
              window.autocompleteSimplebar = new SimpleBar(container.get(0));
              $(item).closest('.autocomplete').addClass('active');
          },
          onHide: function () {
              $(item).closest('.autocomplete').removeClass('active');
          },
        };
        
        $(item).autocomplete(options);
        $(item)
            .closest('.autocomplete')
            .on('click', function () {
                $(item).focus();
                $(item).triggerHandler($.Event('keyup', { keyCode: 65, which: 65 }));
                $(item).trigger('change');
            });
      });
    }
    
    $(document).on('click', '.js-parser-runner', function(e) {
        let runnerBtn = $(this),
            runnerRow = runnerBtn.closest('.add-controls'),
            runnerSection = runnerRow.closest('.content-block'),
            answerElement = $('.js-parser-answer'),
            parserSrcUrl = $('.js-parser-source').val();
        
        runnerBtn.addClass('waiting');
        
        $.ajax({
            url: '/participant/parse-player',
            data: {'url': parserSrcUrl},
            type: 'POST',
            dataType: 'json',
            cache: false
        }).done(function(response) {
            if (response.status === 'success') {
                $('.append-wrap input[name$="[playerNick]"]').each(function(index, item) {
                    if ($(this).val().search(new RegExp(response.player.nick, "i")) > -1) {
                        $(this).closest('.append-item').remove();
                    }
                });
                
                runnerRow.find('.js-add-btn').trigger('click');
                let lastTr = runnerSection.find('.append-wrap tr.append-item').last();
                lastTr.find('input[name$="[playerNick]"]').val(response.player.nick);
                lastTr.find('select[name$="[class_id]"]').val(response.player.classId);
                lastTr.find('select[name$="[race_id]"]').val(response.player.raceId);
                lastTr.find('select[name$="[faction_id]"]').val(response.player.factionId);
                lastTr.find('select[name$="[faction_id]"]').val(response.player.factionId);
                lastTr.find('input[name$="[worldName]"]').val(response.player.world);
                __worlds = response.worlds;
                
                answerElement.text('');
                $('.js-parser-source').val('');
                
                selectReInit(); 
            } else {
                answerElement.text(response.message);
            }
        })
        .fail(function() {
            alert("Error while parse link.");
        })
        .always(function() {
            runnerBtn.removeClass('waiting');
        });
        
    });
    
    autocompleteParticipants();

    updateAvailableParticipants();

JS;
$this->registerJs($js);