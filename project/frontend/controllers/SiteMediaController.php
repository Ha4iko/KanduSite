<?php

namespace frontend\controllers;

use frontend\models\Media;
use frontend\models\MediaForm;
use frontend\models\search\MediaSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Media controller
 */
class SiteMediaController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['root'],
                    ],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => ['update', 'delete']
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MediaSearch();
        $data = Yii::$app->request->get();
        $dataProvider = $searchModel->search($data, '');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $slug
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($slug)
    {
        $model = $this->findModelBySlug($slug);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * @param int|null $id
     * @return string|\yii\web\Response
     * @throws \Throwable
     */
    public function actionUpdate($id = null)
    {
        if ($id) {
            $model = $this->findFormById($id);
        } else {
            $model = (new MediaForm())->initDefaultValues();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // $redirectUrl = Yii::$app->request->post('ajaxPopupRedirect', ['/site-media/index']);
            // return $this->redirect($redirectUrl);
            return $this->redirect(['view', 'slug' => $model->slug]);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionDelete($id)
    {
        $model = $this->findModelById($id);

        if (Yii::$app->request->isPost) {
            $model->delete();
            $redirectUrl = Yii::$app->request->post('ajaxPopupRedirect', ['/site-media/index']);
            return $this->redirect($redirectUrl);
        }

        return $this->renderAjax('delete', [
            'model' => $model
        ]);
    }

    /**
     * @param string $slug
     * @return Media
     * @throws NotFoundHttpException
     */
    protected function findModelBySlug($slug)
    {
        $conditions = ['slug' => $slug];
        if (!Yii::$app->user->can('root')) {
            $conditions['active'] = 1;
        }
        $model = Media::findOne($conditions);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Media not found');
    }

    /**
     * @param int $id
     * @return Media
     * @throws NotFoundHttpException
     */
    protected function findModelById($id)
    {
        $model = Media::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Media not found');
    }

    /**
     * @param int $id
     * @return MediaForm
     * @throws NotFoundHttpException
     */
    protected function findFormById($id)
    {
        $model = MediaForm::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Media not found');
    }

}
