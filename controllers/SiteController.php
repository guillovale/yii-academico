<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
           // return $this->goHome();
        }

        $model = new LoginForm();
		#echo var_dump($model->login()); exit;
		
        if ($model->load(Yii::$app->request->post()) ) {
			Yii::$app->user->login($model->getUser());
			if ($model->login()) {
				#echo var_dump(Yii::$app->user->identity); exit;
				# return $this->redirect(['cursoofertado/index']);
			    return $this->goBack();
			}
		//return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
       return $this->render('about');
    }

	public function actionTcpdf()
    {
	//echo var_dumps('hola');
	//exit;
        //return $this->render('tcpdf');
    }
	//enviar mail
	public function enviarMail($cedula, $texto)
	{
		//$docente = InformacionpersonalD::find()
			//									->where(['CIInfPer'=> $cedulad])
				//								->one();
		$docente = InformacionpersonalD::find()
												->where(['CIInfPer'=> $cedula])
												->one();
		$emailtics = 'tics@utelvt.edu.ec';
		$emailacademico = 'viceacademico@utelvt.edu.ec';
		$emaildocente = $emailtics;
		if ($docente) {
			if ($docente->mailInst !== NULL)
				$emaildocente =$docente->mailInst;
		}
		
		
		
		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emaildocente)
				//->setCc($emailtics)
				->setSubject('Ingreso al sistema')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailtics)
				->setSubject('Ingreso al sistema')
				->setTextBody($texto)
				->send();

		/*$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailacademico)
				->setSubject('Eliminación de asignatura')
				->setTextBody($texto)
				->send();
			*/
	}

}
