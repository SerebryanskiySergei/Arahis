<?php
namespace app\controllers;

use app\models\Documents;
use app\models\Settings;
use app\models\SettingsForm;
use app\models\User;
use Yii;
use app\models\LoginForm;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->renderPartial('welcome');
        }
        if(User::isAdmin(Yii::$app->user->id))
        {
            $years = array();
            $dates = Documents::find()->select('updated_at')->distinct()->asArray()->all();
            foreach ($dates as $date) {
                $buf = Yii::$app->formatter->asDate($date['updated_at'],'php:Y');
                if(!in_array($buf,$years))
                    array_push($years,$buf);
            }
            rsort($years,1);
//            var_dump($years);exit;
            $documents = Documents::find()->orderBy('status')->all();
            $new_count =0; $warn_count =0; $ready_count =0;
            foreach ($documents as $doc){
                if(Yii::$app->formatter->asDate($doc->updated_at, 'Y') == $years[0])
                    switch ($doc->status) {
                        case \app\models\Documents::STATUS_NEW:
                            $new_count++;
                            break;
                        case \app\models\Documents::STATUS_WARNING:
                            $warn_count++;
                            break;
                        case \app\models\Documents::STATUS_READY:
                            $ready_count++;
                            break;
                    }
            }
            $model = new Documents();
            return $this->render('index',['documents'=>$documents,'model'=>$model,'years'=>$years,'new_count'=>$new_count,'warn_count'=>$warn_count,'ready_count'=>$ready_count]);
        }
        else{
            $documents = Documents::find()->where(['user_id'=>Yii::$app->user->id])->all();
            $model = new Documents();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else {
                return $this->render('index',['documents'=>$documents,'model'=>$model]);
            }
        }
        
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
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
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Спасибо что связались с нами.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при отправке сообщения.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
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
                Yii::$app->session->setFlash('success', 'Проверьте вашу почту.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Извините,данное действие невозможно.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
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
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
    public function actionSettings(){
        $model = new SettingsForm();
        if ($model->load(Yii::$app->request->post())) {
            $model->req_file = UploadedFile::getInstance($model, 'req_file');
            if(!empty($model->req_file)){
                $model->upload();
                $buf = Settings::find()->where(['name'=>Settings::REQ_FILE])->one();
                $buf->value = $model->req_file;
                $buf->save();
            }
            $buf=Settings::find()->where(['name'=>Settings::REQ_TEXT])->one();
            $buf->value = $model->req_text;
            $buf->save();
            $buf=Settings::find()->where(['name'=>Settings::WORK])->one();
            $buf->value = $model->working;
            $buf->save();
            Yii::$app->session->setFlash('success', 'Настройки сохранены.');
        }
        $model->req_text = Settings::find()->where(['name'=>Settings::REQ_TEXT])->one()->value;
        $model->req_file = Settings::find()->where(['name'=>Settings::REQ_FILE])->one()->value;
        $model->working = Settings::find()->where(['name'=>Settings::WORK])->one()->value;
        return $this->render('settings',['model'=>$model]);
    }

    public function actionDownloadFile($file)
    {
        // отдаем файл
        $path = 'uploads/'.$file;
        $ext = explode('.',$file)[1];
        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path,'Файл с требованиями.'.$ext);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }
    }

}
