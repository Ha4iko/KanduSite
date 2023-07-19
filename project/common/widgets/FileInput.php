<?php

namespace common\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\InputWidget;

class FileInput extends Widget {

    /** @var Model */
    public $model;
    public $preview = true;
    public $attribute;
    public $showLabel = true;
    public $accept = 'image/*';
    public $bg = '';

    public function run() {
        $rand = rand(1000, 9999);
        $id = 'file-field-' . $rand;
        $attribute = $this->attribute;
        $label = $this->showLabel ? ('<label>' . $this->model->getAttributeLabel($attribute) . '</label>') : '';
        $value = $this->model->$attribute;

        $input = Html::activeHiddenInput($this->model, $this->attribute);
        $fileInput = Html::fileInput($this->model->formName() . '[' . $this->attribute . ']', null, [
            'style' => 'display: none;',
            'accept' => $this->accept,
        ]);

        //        $input = $value ? Html::activeHiddenInput($this->model, $this->attribute) : '';
        //        $fileInput = Html::activeFileInput($this->model, $this->attribute, [
        //            'style' => 'display: none;'
        //        ]);

        if (!$value && $this->preview) {
            $value = '/images/no-image.png';
        }

        if ($this->preview) {
            $preview = "<img class=\"img-thumbnail mb-3 w-100\" style=\"background-color: $bg\" src=\"$value\">";
        } else {
            $preview = "<input readonly class=\"form-control mb-2\" type=\"text\" value=\"$value\">";
        }

        $bg = $this->bg ?: '#fff';
        $html = <<<HTML
                <div id="$id" class="form-group">               
                    $label
                    $preview                   
                    $fileInput
                    $input
                    <div class="btn btn-primary change-image">Изменить</div>
                    <div class="btn btn-outline-secondary remove-image">Очистить</div>                                                  
                </div>               
HTML;

        $js = <<<JS
            $('#$id input[type=file]').change(e => {
                var input = e.target;
                if (input.files && input.files[0]) {
                    var readonly = $('#$id [readonly]');
                    if (readonly.length) {
                        readonly.val(input.files[0].name);
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $('#$id .img-thumbnail').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
                $(input).next('input').remove();
            });
            $('#$id .change-image').click(() => {
                $('#$id input[type=file]').click();
            });
            $('#$id .remove-image').click(() => {
               $('#$id input').val('');
               $('#$id .img-thumbnail').attr('src', '/images/no-image.png');
            });
JS;

        Yii::$app->view->registerJs($js, View::POS_READY);

        return $html;
    }

}