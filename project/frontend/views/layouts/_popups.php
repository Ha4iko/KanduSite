<?php

/* @var $this \yii\web\View */

?>


<div class="popups">
    <div class="popups-bg"></div>
    <div id="ajaxPopups"></div>

    <?php if ($this->params['renderPopup.login']): ?>
        <?= $this->render('../popups/login') ?>
    <?php endif; ?>

    <?php if ($this->params['renderPopup.forgot']): ?>
        <?= $this->render('../popups/forgot') ?>
    <?php endif; ?>

</div>

