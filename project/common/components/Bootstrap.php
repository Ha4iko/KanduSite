<?php
namespace common\components;

use frontend\assets\AppAsset;
use frontend\assets\ImgAsset;
use common\models\Setting;
use Yii;
use yii\base\Component;
use yii\base\BootstrapInterface;

class Bootstrap extends Component implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->id == 'frontend' && stripos($app->request->absoluteUrl, '/debug/') === false) {
            AppAsset::register($app->view);
            $imgAsset = ImgAsset::register($app->view);
            define('IMG_ROOT', $imgAsset->baseUrl);

            $app->params['settings'] = Setting::find()
                ->select('value, key')->indexBy('key')->column();
        }
    }
}

