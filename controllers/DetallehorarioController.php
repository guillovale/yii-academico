<?php

namespace app\controllers;

use Yii;
use app\models\DetalleHorario;
use app\models\CursoOfertado;
use app\models\DetalleHorarioSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * DetallehorarioController implements the CRUD actions for DetalleHorario model.
 */
class DetallehorarioController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all DetalleHorario models.
     * @return mixed
     */
    public function actionIndex1()
    {
        $searchModel = new DetalleHorarioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DetalleHorario model.
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
     * Creates a new DetalleHorario model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idcurso, $idhorario)
    {

		$query = Detallehorario::find()->where(['idhorario' => $idhorario]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
			'sort' => [
				'defaultOrder' => [
				    'dia' => SORT_ASC,
				    'hora_inicio' => SORT_ASC, 
				]
			],
		]);

		// returns an array of Post objects
		//$posts = $provider->getModels();


		//$searchModel = new DetalleHorarioSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new DetalleHorario();
		$dias = ['LUNES'=>'LUNES','MARTES'=>'MARTES','MIERCOLES'=>'MIERCOLES','JUEVES'=>'JUEVES',
				'VIERNES'=>'VIERNES','SÁBADO'=>'SÁBADO', 'TODO'=>'LUNES-VIERNES'];
		$diaslab = ['LUNES'=>'LUNES','MARTES'=>'MARTES','MIERCOLES'=>'MIERCOLES','JUEVES'=>'JUEVES',
				'VIERNES'=>'VIERNES'];
		$horas = [];
		$horas = $this->time_range( '7:00', '22:00', 1800 );
		$this->view->params['horas'] = $horas;
		$this->view->params['dias'] = $dias;
		$model->idcurso = $idcurso;
		$model->idhorario = $idhorario;
		$post = Yii::$app->request->post();
		Url::remember(['detallehorario/create', 'idcurso' => $idcurso, 'idhorario'=> $idhorario], 'detallehorario');

		//echo var_dump($model); exit;
        if ( $model->load($post) ) {
			#echo var_dump(); exit;
			//$model->hora_inicio = strtotime($model->hora_inicio);
			//$model->hora_fin = strtotime($model->hora_fin);
			$usuario = Yii::$app->user->identity;
			if ( $usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' || $usuario->idperfil == 'dist' ) {
				if ($post["DetalleHorario"]["dia"] == 'TODO') {
					foreach($diaslab as $dia) {
						$model = new DetalleHorario();
						$model->idcurso = $idcurso;
						$model->idhorario = $idhorario;
						$model->dia = $dia;
						$model->hora_inicio = $post["DetalleHorario"]["hora_inicio"];
						$model->hora_fin = $post["DetalleHorario"]["hora_fin"];
						$model->save();
					}
				}
				else
					$model->save();
				//Yii::$app()->user->setFlash('message', 'Successfully save form');
				Yii::$app->session->setFlash('success', 'Thank you ');
				//return $this->redirect(['view', 'id' => $model->id]);
			}
			return $this->redirect(\Yii::$app->request->getReferrer());
        } else {
            return $this->render('create', [
                'model' => $model, 
            	'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing DetalleHorario model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post())) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' || $usuario->idperfil == 'dist' ) {
				$model->save();
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DetalleHorario model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' || $usuario->idperfil == 'dist' ) {
        	$this->findModel($id)->delete();
		}
        return Url::previous('detallehorario');
    }

	public function actionVer($idcurso)
    {
		$modelCurso = CursoOfertado::find()->where(['id'=>$idcurso])->one();
		$query = Detallehorario::find()->where(['idcurso' => $idcurso]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 50,
			],
			'sort' => [
				'defaultOrder' => [
				    'dia' => SORT_ASC,
				    'hora_inicio' => SORT_ASC, 
				]
			],
		]);

		return $this->render('ver', [
                'modelCurso' => $modelCurso, 
            	'dataProvider' => $dataProvider,
        ]);
        
    }

	public function time_range( $start, $end, $step = 1800 ) {
		$return = array();
		for( $time = strtotime($start); $time <= strtotime($end); $time += $step )
		    $return[date( 'H:i', $time )] = date( 'H:i', $time );
		return $return;
	}

    /**
     * Finds the DetalleHorario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DetalleHorario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */


    protected function findModel($id)
    {
        if (($model = DetalleHorario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
