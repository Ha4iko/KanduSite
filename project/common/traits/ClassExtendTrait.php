<?php
namespace common\traits;

use ReflectionClass;

trait ClassExtendTrait
{
    public static function classNameShort() {
        return (new ReflectionClass(get_called_class()))->getShortName();
    }

    public function getClassNameShort() {
        return (new ReflectionClass($this))->getShortName();
    }
    public function getClassNameUnderscore() {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $this->getClassNameShort())), '_');
    }
    public function getClassNameHyphen() {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $this->getClassNameShort())), '-');
    }
}
