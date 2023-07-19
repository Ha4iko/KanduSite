<?php

namespace common\widgets;

use Yii;
use ReflectionClass;
use common\traits\ClassExtendTrait;

class BaseWidget extends \yii\base\Widget
{
    use ClassExtendTrait;

    public function getViewPath()
    {
        $class = new ReflectionClass($this);

        return dirname($class->getFileName()) . DIRECTORY_SEPARATOR . $this->getClassNameUnderscore();
    }
}
