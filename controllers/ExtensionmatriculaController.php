<?php

namespace app\controllers;

use Yii;
use app\models\ExtensionMatricula;
use app\models\Periodolectivo;
use yii\helpers\ArrayHelper;
use app\models\Carrera;
use app\models\ExtensionMatriculasearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ExtensionmatriculaController implements the CRUD actions for ExtensionMatricula model.
 */
class ExtensionmatriculaController extends Controller
{
    public function behaviors()
    {
        return [

		'access' => [
                'class' => AccessControl::className(),
                
                'rules' => [
			[
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ExtensionMatricula models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtensionMatriculasearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExtensionMatricula model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ExtensionMatricula model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$usuario = Yii::$app->user->identity;
		$carreras = [];
		if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'dist' ){
			$model = new ExtensionMatricula();
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
			#if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			#}
		
			#else {
			#	$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
			#										->orderBy(['nombcarr'=>SORT_DESC])
			#										->all(), 'idCarr', 'NombCarr');
			#}
			$this->view->params['carreras'] = $carreras;
			$this->view->params['idperiodo'] = $periodo?$periodo->idper:'';
			$this->view->params['usuario'] = $usuario?$usuario->LoginUsu:'';


			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				//return $this->redirect(['view', 'id' => $model->id]);
				return $this->redirect(['index']);
			} else {
				return $this->render('create', [
				    'model' => $model,
				]);
			}
		}
		else {
			return $this->redirect(\Yii::$app->request->getReferrer());
		}

    }

    /**
     * Updates an existing ExtensionMatricula model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
		$carreras = [];

		if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'dist' ){

			$model = $this->findModel($id);
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
			#if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			#}
		
			#else {
			#	$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
			#										->orderBy(['nombcarr'=>SORT_DESC])
			#										->all(), 'idCarr', 'NombCarr');
			#}
			$this->view->params['carreras'] = $carreras;
			$this->view->params['idperiodo'] = $periodo?$periodo->idper:'';
			$this->view->params['usuario'] = $usuario?$usuario->LoginUsu:'';

			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				//return $this->redirect(['view', 'id' => $model->id]);
				return $this->redirect(['index']);
			} else {
				return $this->render('update', [
				    'model' => $model,
				]);
			}
		}
		else {
			return $this->redirect(\Yii::$app->request->getReferrer());
		}

    }

    /**
     * Deletes an existing ExtensionMatricula model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if (Yii::$app->user->identity->idperfil == 'diracad' || Yii::$app->user->identity->idperfil == 'sa'){
		    //$this->findModel($id)->delete();
		}
		return $this->redirect(['index']);
    }

    /**
     * Finds the ExtensionMatricula model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtensionMatricula the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExtensionMatricula::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
