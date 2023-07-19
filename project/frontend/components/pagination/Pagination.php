<?php

namespace frontend\components\pagination;

use mranger\load_more_pager\LoadMorePager;
use Yii;
use yii\helpers\Json;

/**
 * Class Pagination
 * @package app\components
 */
class Pagination extends LoadMorePager
{

    protected function registerPlugin() {
        $js = [];

        $view = $this->getView();

        $asset = PaginationWidgetAsset::register($view);

        if (!$this->includeCssStyles) {
            $asset->css = [];
        }

        $options = [
            'id'                  => $this->id,
            'contentSelector'     => $this->contentSelector,
            'contentItemSelector' => $this->contentItemSelector,
            'loaderShow'          => $this->loaderShow,
            'loaderAppendType'    => $this->loaderAppendType,
            'loaderTemplate'      => $this->loaderTemplate,
            'buttonText'          => $this->buttonText,
            'onLoad'              => (($this->onLoad !== null) ? $this->onLoad : null),
            'onAfterLoad'         => (($this->onAfterLoad !== null) ? $this->onAfterLoad : null),
            'onFinished'          => (($this->onFinished !== null) ? $this->onFinished : null),
            'onError'             => (($this->onError !== null) ? $this->onError : null),
        ];

        $options = Json::encode($options);

        $js[] = "LoadMorePagination.addPagination($options);";

        if (!Yii::$app->request->isAjax) {
            $view->registerJs(implode("\n", $js));
        }
    }
}
