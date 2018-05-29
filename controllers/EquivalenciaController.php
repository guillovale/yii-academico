<?php

namespace app\controllers;

use Yii;
use app\models\Equivalencia;
use app\models\EquivalenciaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\filters\AccessControl;
/**
 * EquivalenciaController implements the CRUD actions for Equivalencia model.
 */
class EquivalenciaController extends Controller
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
     * Lists all Equivalencia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EquivalenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Equivalencia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($idequivalencia)
    {
        return $this->render('view', [
            'model' => $this->findModel($idequivalencia),
        ]);
    }

    /**
     * Creates a new Equivalencia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idAsig)
    {
	
        $model = new Equivalencia();
		$model->asignatura = $idAsig;
		$model->fecha = date('Y-m-d H:i:s');
		$model->usuario = Yii::$app->user->id;
		$url = yii::$app->session->get('url');

		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {

				if ($model->load(Yii::$app->request->post()) && $model->save()) {
					if ($url) return $this->redirect($url);
					else {
		
						return $this->render('create', [
							'model' => $model,
						]);
					}
		
		
				} else {
				    return $this->render('create', [
				        'model' => $model,
				    ]);
				}
			}
		}
		return $this->redirect(['index']);	
    }

    /**
     * Updates an existing Equivalencia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($idequivalencia)
    {
        $model = $this->findModel($idequivalencia);
		$url = yii::$app->session->get('url');
		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {

				if ($model->load(Yii::$app->request->post()) && $model->save() ) {
				return $this->redirect($url);
				    //return $this->redirect(['view', 'idequivalencia' => $model->idequivalencia]);
				} else {
				    return $this->render('update', [
				        'model' => $model,
				    ]);
				}
			}
		}
		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Equivalencia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($idequivalencia)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
				$this->findModel($idequivalencia)->delete();
			}
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Equivalencia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Equivalencia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Equivalencia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.zzz');
        }
    }

}
