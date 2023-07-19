<?php

namespace frontend\components\pagination;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class LinkPager extends \yii\widgets\LinkPager {

    public $internalLinksWrapperClass = null;
    public $externalLinksTag = false;
    public $internalLinksTag = 'li';
    public $separator = '...';

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        $this->linkContainerOptions['tag'] = $this->externalLinksTag;

        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0,
                false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass,
                $currentPage <= 0, false);
        }

        // page calculations
        list($beginPage, $endPage) = $this->getPageRange();
        $startSeparator = false;
        $endSeparator = false;
        $beginPage++;
        $endPage--;
        if ($beginPage != 1) {
            $startSeparator = true;
            $beginPage++;
        }
        if ($endPage + 1 != $pageCount - 1) {
            $endSeparator = true;
            $endPage--;
        }

        if ($this->internalLinksWrapperClass) {
            $buttons[] = '<ul class="' . $this->internalLinksWrapperClass .'">';
        }

        $this->linkContainerOptions['tag'] = $this->internalLinksTag;

        // smallest page
        $buttons[] = $this->renderPageButton(1, 0, null, false, 0 == $currentPage);

        // separator after smallest page
        if ($startSeparator) {
            $buttons[] = $this->renderPageButton($this->separator, null, null, true, false);
        }
        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            if ($i != 0 && $i != $pageCount - 1) {
                $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
            }
        }
        // separator before largest page
        if ($endSeparator) {
            $buttons[] = $this->renderPageButton($this->separator, null, null, true, false);
        }
        // largest page
        $buttons[] = $this->renderPageButton($pageCount, $pageCount - 1, null, false,
            $pageCount - 1 == $currentPage);

        if ($this->internalLinksWrapperClass) {
            $buttons[] = '</ul>';
        }

        $this->linkContainerOptions['tag'] = $this->externalLinksTag;

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass,
                $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass,
                $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('div', implode("\n", $buttons), $this->options);
    }



    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = $this->linkContainerOptions;
        $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
        Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

        // if ($active) {
        //     Html::addCssClass($options, $this->activePageCssClass);
        // }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);
            $disabledItemOptions = $this->disabledListItemSubTagOptions;
            $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'span');

            if ($linkWrapTag === false) return Html::tag($tag, $label, array_merge($disabledItemOptions, $options));
            return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        if ($active) {
            Html::addCssClass($linkOptions, $this->activePageCssClass);
        }

        if ($linkWrapTag === false) return Html::a($label, $this->pagination->createUrl($page), array_merge($linkOptions, $options));
        return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }

}