<?php

namespace app\controllers;

use Yii;
use app\models\MallaEstudiante;
use app\models\MallaEstudianteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * MallaEstudianteController implements the CRUD actions for MallaEstudiante model.
 */
class MallaestudianteController extends Controller
{
    public function behaviors()
    {
        return [

		'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index'],
                'rules' => [/*
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],*/
			[
                        //'actions' => ['create'],
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
     * Lists all MallaEstudiante models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MallaEstudianteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MallaEstudiante model.
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
     * Creates a new MallaEstudiante model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$usuario = Yii::$app->user->identity;
	
        $model = new MallaEstudiante();
		$model->fecha = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && ($usuario->idperfil == 'sa' || 
					$usuario->idperfil == 'diracad' || $usuario->idperfil == 'centros') ) {
			$model->save();
            return $this->redirect(['view', 'id' => $model->id_malla]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MallaEstudiante model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && ($usuario->idperfil == 'sa' || 
				$usuario->idperfil == 'diracad' || $usuario->idperfil == 'centros')) {
			$model->save();
            return $this->redirect(['view', 'id' => $model->id_malla]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MallaEstudiante model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' ) {
        	$this->findModel($id)->delete();
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the MallaEstudiante model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MallaEstudiante the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MallaEstudiante::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
