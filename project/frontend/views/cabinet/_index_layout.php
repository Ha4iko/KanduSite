<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

?>

<?php $this->beginBlock('listUsersTemplate'); ?>
    <div class="table table--control table--center">
        <div class="table-content">
            <div class="table-inner">
                <table>
                    <thead>
                        <tr>
                            <th>
                                <div class="table-sort">
                                    <div class="table-sort__text">Nickname</div>
                                    <div class="table-sort__btn"></div>
                                </div>
                            </th>
                            <th class="table-col--left">
                                <div class="table-sort">
                                    <div class="table-sort__text">Role</div>
                                    <div class="table-sort__btn"></div>
                                </div>
                            </th>
                            <th>
                                <div class="table-sort">
                                    <div class="table-sort__text">created tournament</div>
                                    <div class="table-sort__btn"></div>
                                </div>
                            </th>
                            <th class="table-col--right">
                                <div class="table-sort">
                                    <div class="table-sort__text">added</div>
                                    <div class="table-sort__btn"></div>
                                </div>
                            </th>
                            <th class="table-col--right"></th>
                        </tr>
                    </thead>
                    <tbody>
                        {items}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="catalog-controls">
        <div></div>
        {pager}
    </div>
<?php $this->endBlock(); ?>