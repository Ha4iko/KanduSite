<?php

namespace frontend\components\pagination;

use frontend\assets\AppAsset;
use yii\web\AssetBundle;

class PaginationWidgetAsset extends \mranger\load_more_pager\LoadMorePagerWidgetAsset {

	public $depends = [
        AppAsset::class
	];
}
