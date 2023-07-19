<?php

namespace frontend\widgets;

use common\widgets\BaseWidget;
use frontend\models\Media;

class LatestMediaWidget extends BaseWidget
{
    /**
     * @var int
     */
    public $limit = 6;

    /**
     * @var array
     */
    public $excludeIds = [];

    /**
     * init widget
     */
    public function init()
    {
       parent::init();

       if (!$this->limit) $this->limit = 1;
       if ($this->limit < 1) $this->limit = 1;
       if ($this->limit > 100) $this->limit = 100;
    }

    /**
     * @return string
     */
    public function run()
    {
        $mediaQuery = Media::find()->limit($this->limit)
            ->where('active = 1')
            ->orderBy('id desc');

        if ($this->excludeIds) {
            $mediaQuery->andWhere(['NOT IN', 'id', $this->excludeIds]);
        }

        return $this->render('list', [
            'media' => $mediaQuery->all(),
        ]);
    }
}

