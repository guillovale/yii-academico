<?php

namespace app\controllers;

use Yii;
use app\models\Asignatura;
use app\models\DetalleMalla;
use app\models\DetalleMallaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * DetallemallaController implements the CRUD actions for DetalleMalla model.
 */
class DetallemallaController extends Controller
{
    public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete','update', 'create'],
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
     * Lists all DetalleMalla models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new DetalleMallaSearch();
		$searchModel->idmalla = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DetalleMalla model.
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
     * Creates a new DetalleMalla model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idmalla)
    {
        $model = new DetalleMalla();
		$model->idmalla = $idmalla;
		$model->credito = 0;
		$usuario = Yii::$app->user->identity;
		$asignaturas = Asignatura::find()
					->select(['IdAsig', 'concat(IdAsig, "--", NombAsig) as nombre'])
					->where(['statusAsig' => 1])
					->orderBy('IdAsig ASC')
					->all();
		$listaasignatura = $asignaturas?ArrayHelper::map($asignaturas,'IdAsig', 'nombre'):'';
		$nivel = ['0'=>0, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, '10'=>10];
		$caracter = ['OBLIGATORIA'=>'OBLIGATORIA', 'OPCIONAL'=>'OPCIONAL'];
		$this->view->params['asignatura'] = $listaasignatura;	
		$this->view->params['nivel'] = $nivel;
		$this->view->params['caracter'] = $caracter;		
	
        if ($model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' ) {
				$model->save();
			}
            #return $this->redirect(['index']);
			#return $this->redirect(Yii::$app->request->referrer);
			return $this->redirect(['index', 'id'=> $model->idmalla]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DetalleMalla model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
		$asignaturas = Asignatura::find()
					->select(['IdAsig', 'concat(IdAsig, "-", NombAsig) as nombre'])
					->where(['statusAsig' => 1])
					->orderBy('NombAsig ASC, IdAsig DESC')
					->all();
		$listaasignatura = $asignaturas?ArrayHelper::map($asignaturas,'IdAsig', 'nombre'):'';
		$nivel = ['0'=>0, '1'=>1, '2'=>2, '3'=>3, '4'=>4, '5'=>5, '6'=>6, '7'=>7, '8'=>8, '9'=>9, '10'=>10];
		$caracter = ['OBLIGATORIA'=>'OBLIGATORIA', 'OPCIONAL'=>'OPCIONAL'];
		$this->view->params['asignatura'] = $listaasignatura;	
		$this->view->params['nivel'] = $nivel;
		$this->view->params['caracter'] = $caracter;
        if ($model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' ) {
				$model->save();
			}
            return $this->redirect(['index', 'id'=> $model->idmalla]);
			#return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DetalleMalla model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' ) {
			$this->findModel($id)->delete();
		}

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the DetalleMalla model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DetalleMalla the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DetalleMalla::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
