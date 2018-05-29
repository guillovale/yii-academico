<?php

namespace app\controllers;

use Yii;
use app\models\Asignatura;
use app\models\AsignaturaSeach;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AsignaturaController implements the CRUD actions for Asignatura model.
 */
class AsignaturaController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete','update', 'create', 'abonar'],
                'rules' => [
                    [
                        //'actions' => ['delete','update', 'create'],
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
     * Lists all Asignatura models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AsignaturaSeach();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Asignatura model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Asignatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa') ) {
		    $model = new Asignatura();

		    if ($model->load(Yii::$app->request->post()) && $model->save()) {
		        return $this->redirect(['view', 'id' => $model->IdAsig]);
		    } else {
		        return $this->render('create', [
		            'model' => $model,
		        ]);
		    }
		}
		return $this->redirect(['index']);
    }

    /**
     * Updates an existing Asignatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa')) {
		    $model = $this->findModel($id);

		    if ($model->load(Yii::$app->request->post()) && $model->save()) {
		        return $this->redirect(['view', 'id' => $model->IdAsig]);
		    } else {
		        return $this->render('update', [
		            'model' => $model,
		        ]);
		    }
		}
		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Asignatura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa')) {
       		$this->findModel($id)->delete();
		}

        return $this->redirect(['index']);
    }

    /**
     * Finds the Asignatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Asignatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Asignatura::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
