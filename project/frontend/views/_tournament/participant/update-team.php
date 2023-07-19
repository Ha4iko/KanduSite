<?php

use frontend\models\ParticipantsWithTeamsForm;
use frontend\models\TournamentToPlayer;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;
use frontend\models\Team;
use frontend\models\Player;
use common\models\PlayerRace;
use common\models\PlayerFaction;
use common\models\PlayerClass;
use common\models\PlayerWorld;
use common\helpers\DataTransformHelper;

/** @var $this View */
/** @var $model ParticipantsWithTeamsForm */


$nicks = DataTransformHelper::getList(Player::class, 'nick');
$teamNames = DataTransformHelper::getList(Team::class, 'name');
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

$nicksJson = json_encode(array_values($nicks));
$teamNamesJson = json_encode(array_values($teamNames));

$participantsLinksGroupedByTeam = $model->participantsRelationsWithTeamsGroupedByTeam;
$isEmptyParticipants = false;
if (empty($participantsLinksGroupedByTeam)) {
    $new = new TournamentToPlayer();
    $new->tournament_id = $model->id;
    $participantsLinksGroupedByTeam[0][0] = $new;

    $isEmptyParticipants = true;
}

$jsParticipants = [];
foreach ($nicks as $nick) {
    $jsParticipants[mb_strtolower($nick)] = $nick;
}
$this->registerJsVar('__nicks_all', $jsParticipants);
$this->registerJsVar('__nicks_available', $jsParticipants);
$this->registerJsVar('__worlds', $worlds ? array_values($worlds) : []);

?>
<style>
    .autocomplete-suggestions {
        overflow-y: scroll;
        overflow-x: hidden;
    }
</style>
<div class="popup" id="adminAddParticipants2">
    <div class="popup-wrap">
        <div class="popup-main">
            <?php Pjax::begin([
                'id' => 'tournamentPlayersForm',
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]); ?>
            <?= Html::beginForm(['/participant/update-team', 'id' => $model->id], 'post', [
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
                'data-form-teams' => 'yes',
            ]); ?>
            <?= Html::hiddenInput($model->formName() . '[id]', $model->id, []) ?>
            <div style="display: none;" data-max-players="<?= $model->type->players_in_team ?>"></div>
            <div class="popup-head">
                <div class="popup-close js-popup-close">
                    <a class="link-back" href="#">
                        <svg class="icon">
                            <use href="<?= IMG_ROOT ?>/sprites/main.symbol.svg#image-chevron"></use>
                        </svg>
                        close
                    </a>
                </div>
                <div class="popup-title h3">
                    <?= $isEmptyParticipants ? 'Add' : 'Edit' ?> Teams <span>/ <?= Html::encode($model->typeName) ?></span>
                </div>
            </div>
            <div class="popup-content">
                <div class="append">
                    <?= $this->render('_update-team_template_team', [
                        'classes' => $classes,
                        'races' => $races,
                        'factions' => $factions,
                        'worlds' => $worlds,
                        'model' => $model,
                    ]) ?>
                    <?= $this->render('_update-team_list', [
                        'classes' => $classes,
                        'races' => $races,
                        'factions' => $factions,
                        'worlds' => $worlds,
                        'model' => $model,
                        'participantsLinksGroupedByTeam' => $participantsLinksGroupedByTeam,
                    ]) ?>
                    <div class="content-block">
                        <div class="add">
                            <div class="add-controls">
                                <div class="add-controls__btn">
                                    <a class="btn btn--md js-add-btn" href="#" data-append="team">
                                        add one more team
                                    </a>
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
$popupId = '#tournamentPlayersForm';
$js = <<<JS
    $(document).on('click', '{$popupId}  .js-add-btn[data-append="team"]', function() {
        let teamOffsets = [];
        $('.field[name^=teamNamesByOffset]').each(function (index, value){
            let elementName = $(this).attr('name');
            let teamOffset = elementName.match(/\[(\d+)\]/);
            
            if (teamOffset.length > 1) {
                teamOffsets.push(teamOffset[1]);
            }
        });
        
        let maxOffset = Math.max.apply(null, teamOffsets);
        let nextTeamNumber = (maxOffset / 100) + 1;
        let nextOffset = maxOffset + 100;

        $('.append-wrap [name="teamNamesByOffset[0][name]"]').each(function (index, value){
            $(this).attr('name', 'teamNamesByOffset[' + nextOffset + '][name]');
            $(this).closest('.append-item').find('.content-block__title').text('team #' + nextTeamNumber);
            let newPlayers = $(this).closest('.append-item').find('.add-table .append-wrap .append-item');

            if (newPlayers.length > 0) {
                newPlayers.attr('data-offset', nextOffset);
                newPlayers.find('[name$="[postTeamOffset]"]').val(nextOffset);
                updateParticipantsIndices();
                autocompleteParticipants();
            }
        });
        
        $('.append-wrap [name="teamNamesByOffset[0][id]"]').each(function (index, value){
            $(this).attr('name', 'teamNamesByOffset[' + nextOffset + '][id]');
        });
    });
    
    $(document).on('click', '{$popupId} .js-custom-add-clear[data-append="team"]', function() {
        updateParticipantsIndices(); 
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
              syncVisibleAddPlayerRow();
            });
          return false;
        } else {
          appendItem.fadeOut(function () {
            appendItem.remove();
            updateAvailableParticipants();
            syncVisibleAddPlayerRow();
          });
          return false;
        }
    });
    
    $(document).on('click', '{$popupId}  .js-add-btn[data-append="player"]', function() {
        let newPlayers = $(this).closest('.append-item').find('.add-table .append-wrap .append-item[data-offset=0]');

        if (newPlayers.length > 0) {
            $(this).closest('.append-item').find('.field[name^=teamNamesByOffset]').each(function (index, value){
                let elementName = $(this).attr('name');
                let teamOffset = elementName.match(/\[(\d+)\]/);
                
                if (teamOffset.length > 1) {
                    newPlayers.attr('data-offset', teamOffset[1]);
                    newPlayers.find('[name$="[postTeamOffset]"]').val(teamOffset[1]);
                }
            });
            
            updateParticipantsIndices();
            autocompleteParticipants();
        }
        
        updateParticipantsIndices();
        autocompleteParticipants();
        
        syncVisibleAddPlayerRow();
    });
    
    $(document).on('click', '{$popupId}  .js-custom-add-clear[data-append="player"]', function() {
        updateParticipantsIndices();
    });

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
    
    $(document).on('blur', '.js-player-autocomplete', function() {
        updateAvailableParticipants();              
    });
    
    function updateParticipantsIndices() { 
        $('{$popupId} .append-wrap[data-append="player"] .append-item').each(function(index) {
            let indexInTeam = parseInt($(this).attr('data-offset')) + index;
            $(this).find('input[name], select[name]').each(function() {
                $(this).attr('name', $(this).attr('name').replace(/\[(\d+|%i%)\]/g, '[' + indexInTeam + ']'));
            });
        });
    }
    
    function syncVisibleAddPlayerRow() { 
        let maxPlayers = parseInt($('[data-max-players]').attr('data-max-players'));
        $('.append-wrap .content-block.append-item').each(function() {
            let currentPlayers = $(this).find('.append-wrap .append-item[data-append="player"]');
            let addPlayerRow = $(this).find('.add-controls');
            if (currentPlayers.length >= maxPlayers) {
                addPlayerRow.hide();
            } else {
                addPlayerRow.show();
            }
        });
    }
    
    function autocompleteParticipants() {
      $('.js-player-autocomplete').each(function (i, item) {
        let options = {
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
              $(item).trigger('change');
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
              //$(item).attr('value', '');
              $(item).triggerHandler($.Event('keyup', { keyCode: 65, which: 65 }));
              $(item).trigger('change');
            }); 
      });
      $('.js-team-autocomplete').each(function (i, item) {
        let options = {
          lookup: {$teamNamesJson},
          maxHeight: 140,
          minChars: 0,
          onSelect: function (suggestion) {
              $(item).trigger('change');
          },
          beforeRender: function (container, suggestions) {
            scroll(container);
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
              //$(item).attr('value', '');
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
            answerElement = runnerRow.find('.js-parser-answer'),
            parserSrcUrl = runnerRow.find('.js-parser-source').val();
        
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
    
    $(document).on('click', '[data-form-teams] [type=submit]', function(e) {
        let teamNames = [],
            playerNames = [],
            hasError = false;
        
        $('.append-wrap .js-team-autocomplete').each(function(idx, el) {
            let teamInput = $(this),
                teamName = teamInput.val(),
                teamError = teamInput.closest('.add-cell').find('.js-name-error');
            
            teamName = teamName.trim();
            
            if (teamName.length < 1) {
                teamError.text('Team name can not be blank.');
                teamInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
                hasError = true;
            } else if (teamNames.indexOf(teamName) !== -1) {
                teamError.text('Team name duplicate! Change name.');
                teamInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
                hasError = true;
            } else {
                teamNames.push(teamName);
                teamInput.attr('style', '');
            }
        });

        $('.append-wrap .content-block.append-item .append-wrap .append-item[data-append="player"] .js-player-autocomplete').each(function(idx, el) {
            let playerInput = $(this),
                playerName = playerInput.val(),
                playerError = playerInput.closest('.add-cell').find('.js-name-error');
            
            playerName = playerName.trim();
            
            if (playerName.length < 1) {
                playerError.text('Player nick can not be blank.');
                playerInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
                hasError = true;
            } else if (playerNames.indexOf(playerName) !== -1) {
                playerError.text('Player nick duplicate! Change nick.');
                playerInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
                hasError = true;
            } else {
                playerNames.push(playerName);
                playerInput.attr('style', '');
            }
        }); 
        
        
        if (hasError) {
            return false; 
        } 
        
        return true;
    });
    
    
    $(document).on('change', '.append-wrap .js-team-autocomplete', function(e) {
        let teamInput = $(this),
            teamName = teamInput.val(),
            teamError = teamInput.closest('.add-cell').find('.js-name-error');
        teamName = teamName.trim();

        if (teamName.length > 1) {
            teamInput.attr('style', '');
            teamError.text('');
        } else {
            teamError.text('Team name can not be blank.');
            teamInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
        }
    });
    
    $(document).on('change', '.append-wrap .content-block.append-item .append-wrap .append-item[data-append="player"] .js-player-autocomplete', function(e) {
        let playerInput = $(this),
            playerName = playerInput.val(),
            playerError = playerInput.closest('.add-cell').find('.js-name-error');
        // playerName = playerName.trim();
        //
        // if (playerName.length > 1) {
            playerInput.attr('style', '');
            playerError.text('');
        // } else {
        //     playerError.text('Team name can not be blank.');
        //     playerInput.attr('style', 'color: #DF0D14; border-color: #DF0D14 !important;');
        // }
    });
JS;
$this->registerJs($js);
