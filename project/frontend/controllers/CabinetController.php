<?php

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\CabinetInterfaceForm;
use frontend\models\CabinetNotificationForm;
use frontend\models\CabinetPasswordForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\search\TournamentSearch;
use frontend\models\search\UserSearch;
use frontend\models\SignupForm;
use frontend\models\User;
use frontend\models\UserForm;
use frontend\models\VerifyEmailForm;
use Yii;
use frontend\models\CabinetProfileForm;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\AjaxFilter;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Cabinet controller
 */
class CabinetController extends Controller
{
    /**
     * @var string the ID of the action that is used when the action ID is not specified
     * in the request. Defaults to 'index'.
     */
    public $defaultAction = 'profile';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'request-password-reset', 'reset-password-info', 'reset-password', 'reset-password-done'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile', 'password', 'notification', 'interface'],
                        'roles' => ['updateUser'],
                        'roleParams' => function ($rule) {
                            return [
                                'userId' => Yii::$app->request->get('id') ?: Yii::$app->user->id
                            ];
                        }
                    ],

                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['createUser'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['updateUser'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['deleteUser'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['tournaments'],
                        'roles' => ['organizer', 'admin', 'root'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['root'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'ajax' => [
                'class' => AjaxFilter::class,
                'only' => [
                    'create', 'update', 'delete',
                    'login', 'request-password-reset'
                ]
            ],
        ];
    }

    /**
     * Displays tournaments list.
     *
     * @return mixed
     */
    public function actionTournaments()
    {
        $data = Yii::$app->request->get();
        $searchModel = new TournamentSearch();
        $searchModel->inCabinet = true;

        // organizers can view only own tournaments
        if (Yii::$app->user->can('organizer')) {
            $searchModel->organizer_id = Yii::$app->user->id;
        }

        $dataProvider = $searchModel->search($data, '');

        return $this->render('tournaments', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays users list.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $data = Yii::$app->request->get();
        $searchModel = new UserSearch();

        $dataProvider = $searchModel->search($data, '');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param int|null $id - user ID
     * @return string|\yii\web\Response
     */
    public function actionProfile($id = null)
    {
        $request = Yii::$app->request;

        $model = new CabinetProfileForm();
        $model->loadFromUserModel($id ?: Yii::$app->user->id);

        if ($request->isPost && $model->load($request->post()) && $model->validate() && $model->update()) {
            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    /**
     * @param int|null $id - user ID
     * @return string|\yii\web\Response
     */
    public function actionPassword($id = null)
    {
        $request = Yii::$app->request;

        $model = new CabinetPasswordForm();
        $model->loadFromUserModel($id ?: Yii::$app->user->id);

        if ($request->isPost && $model->load($request->post()) && $model->validate() && $model->update()) {
            return $this->refresh();
        }

        return $this->render('password', [
            'model' => $model
        ]);
    }

    /**
     * @param int|null $id - user ID
     * @return string|\yii\web\Response
     */
    public function actionNotification($id = null)
    {
        $request = Yii::$app->request;
        $post = $request->post();

        $model = new CabinetNotificationForm();
        $model->loadFromUserModel($id ?: Yii::$app->user->id);

        if ($request->isPost && !isset($post[$model->formName()])) {
            $post[$model->formName()] = [];
        } // без этого $model->load() не сработает при всех выкл галочках

        if ($request->isPost && $model->load($post) && $model->validate() && $model->update()) {
            return $this->refresh();
        }

        return $this->render('notification', [
            'model' => $model
        ]);
    }

    /**
     * @param int|null $id - user ID
     * @return string|\yii\web\Response
     */
    public function actionInterface($id = null)
    {
        $request = Yii::$app->request;
        $post = $request->post();

        $model = new CabinetInterfaceForm();
        $model->loadFromUserModel($id ?: Yii::$app->user->id);

        if ($request->isPost && $model->load($post) && $model->validate() && $model->update()) {
            return $this->refresh();
        }

        return $this->render('interface', [
            'model' => $model
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->renderPartial('@frontend/views/popups/login', [
            'model' => $model
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->redirect(['/cabinet/reset-password-info']);
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->renderPartial('@frontend/views/popups/forgot', [
            'model' => $model
        ]);
    }

    public function actionResetPasswordInfo()
    {
        return $this->render('reset-password-info', []);
    }

    public function actionResetPasswordDone()
    {
        return $this->render('reset-password-done', []);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->redirect(['/cabinet/reset-password-done']);
        }

        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     *-/
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     *-/
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }*/

    /**
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = (new UserForm())->initDefaultValues();

        if ($model->load(Yii::$app->request->post()) && $model->saveUser()) {
            return $this->redirect(['cabinet/index']);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    /**
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findFormById($id);

        if ($model->load(Yii::$app->request->post()) && $model->saveUser()) {
            return $this->redirect(['cabinet/index']);
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
            return $this->redirect(['cabinet/index']);
        }

        return $this->renderAjax('delete', [
            'model' => $model
        ]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModelById($id)
    {
        $model = User::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('User not found');
    }

    /**
     * @param $id
     * @return UserForm
     * @throws NotFoundHttpException
     */
    protected function findFormById($id)
    {
        $model = UserForm::findOne($id);

        if ($model !== null) {
            return $model->initDefaultValues();
        }

        throw new NotFoundHttpException('User not found');
    }

    /*public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }*/

}
