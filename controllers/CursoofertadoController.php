<?php

namespace app\controllers;

use Yii;
use app\models\CursoOfertado;
use app\models\CursoOfertadoSearch;
use app\models\Periodolectivo;
use app\models\Horario;
use app\models\Carrera;
use app\models\Paralelo;
use app\models\MallaCarrera;
use app\models\DetalleMalla;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\helpers\Url;
/**
 * CursoofertadoController implements the CRUD actions for CursoOfertado model.
 */
class CursoofertadoController extends Controller
{
    public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','delete','update', 'create', 'crearcurso', 'docente'],
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
     * Lists all CursoOfertado models.
     * @return mixed
     */
    public function actionIndex()
    {
		#session_start();
		#echo var_dump(Yii::$app->user->identity); exit;
        $searchModel = new CursoOfertadoSearch();
		$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
		$idper = $periodo?$periodo->idper:0;
		$searchModel->idper = $idper;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$carreras = ArrayHelper::map(Carrera::find()
					->where(['StatusCarr'=>1])
					->orderBy('NombCarr')
					->all(),'idCarr','NombCarr');
		$this->view->params['carrera'] = $carreras;
		Url::remember('cursoofertado');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CursoOfertado model.
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
     * Creates a new CursoOfertado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
        $model = new CursoOfertado();
		$hoy = date('Y-m-d');
		$model->fecha_inicio = $periodo?$periodo->fechinicioperlec:$hoy;
		$model->fecha_fin = $periodo?$periodo->fechfinalperlec:$hoy;
		$usuario = Yii::$app->user->identity;

        if ( $model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
				//echo var_dump($usuario->idperfil); exit;
				$model->save();
			}
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CursoOfertado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
		$paralelos = ArrayHelper::map(Paralelo::find()
					//->orderBy(['paralelo'=>SORT_ASC])
					->all(), 'paralelo', 'paralelo');
		$this->view->params['paralelos'] = $paralelos;
        if ($model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
				#echo var_dump($model->fecha_fin); exit;
				$model->save();
			}
			return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CursoOfertado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
			$this->findModel($id)->delete();
		}
		return $this->redirect(['index']);
		
    }

	public function actionDocente()
    {
        $searchModel = new CursoOfertadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('docente', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionCrearcurso() {
		$usuario = Yii::$app->user->identity;
		$hoy = date("Y-m-d");
		$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {		
			if (isset(Yii::$app->user->identity->idcarr)) {
				$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
				$paralelos = ArrayHelper::map(Paralelo::find()
						//->orderBy(['paralelo'=>SORT_ASC])
						->all(), 'paralelo', 'paralelo');
				$this->view->params['paralelos'] = $paralelos;
				
				if (in_array('%', $carreras_user)) {
					$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
										->orderBy(['nombcarr'=>SORT_ASC])
										->all(), 'idCarr', 'NombCarr');
				}
		
				else {
					$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
															->orderBy(['nombcarr'=>SORT_DESC])
															->all(), 'idCarr', 'NombCarr');
				}
		

				//$periodos = ArrayHelper::map(Periodolectivo::find()
					//											->orderBy(['idper'=>SORT_DESC])
						//										->all(), 'idper', 'DescPerLec');
				$this->view->params['carreras'] = $carreras;
				$this->view->params['paralelos'] = $paralelos;
			}
			else{
				$this->view->params['carreras'] = [];
				$this->view->params['paralelos'] = [];

			}
			$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		
			//$this->layout = "/cupos";
			$model = new CursoOfertado();
			$model->fecha_inicio = $periodo?$periodo->fechinicioperlec:$hoy;
			$model->fecha_fin = $periodo?$periodo->fechfinalperlec:$hoy;
			//echo var_dump(Yii::$app->request->get()); exit;
			if ($model->load(Yii::$app->request->get()) && $periodo) {
				$model->idper = $periodo->idper;
				$horario = Horario::find()->where(['idper'=>$periodo->idper, 
								'idcarrera'=>$_GET['CursoOfertado']['idcarr'],
								'nivel'=>intval($_GET['CursoOfertado']['nivel']),
								'paralelo'=>$model->paralelo
							])->one();
				if (!$horario) {
					$modelhorario = new Horario();
					$modelhorario->idper = $periodo->idper;
					$modelhorario->idcarrera = $_GET['CursoOfertado']['idcarr'];
					$modelhorario->nivel = intval($_GET['CursoOfertado']['nivel']);
					$modelhorario->paralelo = $model->paralelo;
					$modelhorario->hora_clase = date('H:i', strtotime('01:00'));
					$modelhorario->hora_inicio = date('H:i', strtotime('07:30'));
					$modelhorario->hora_fin = date('H:i', strtotime('22:00'));
					if ($modelhorario->save()) 
						$model->idhorario = $modelhorario->id;
					else 
						$model->idhorario = 0;
				}
				else 
					$model->idhorario = $horario->id;
				//$model->iddetallemalla = $_GET['CursoOfertado']['asignaturamalla'];
				//$model->iddetallemalla = $_GET['CursoOfertado']['paralelo'];
				//echo var_dump($model->idhorario, ' ' , $modelhorario->id ); exit;
				if ($model->save()) {
					$detallemalla = DetalleMalla::find()->where(['id'=>$model->iddetallemalla])->one();
					if ($detallemalla) {
						$detallemalla->estado = 1;
						$detallemalla->save();
					}
					return $this->redirect(['index']); 
				}

		    } else {
		        return $this->render('crearcurso', [
		            'model' => $model,
		        ]);
		    }
		
		}
		return $this->redirect(['index']);
    }


	public function actionListamalla($id)	{

		$idcarr = ($id?$id:'');
		
		//echo "<option>ok</option>";
		$mallas = MallaCarrera::find()
				->where(['idcarrera' => $idcarr])
				//->andwhere(['idSemestre' => $idnivel])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion'])
				->orderBy('detalle DESC')
				->all();
			//echo var_dump($idcarr); exit;
			if ($mallas){
				echo "<option>-</option>";
				foreach($mallas as $malla){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$malla->id."'>"
						.$malla->detalle."</option>";
				}
			}
	}

	public function actionSetearnivel()	{
		$niveles = array('0'=>'0','1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
		echo "<option>-</option>";
		foreach($niveles as $nivel){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$nivel."'>"
						.$nivel."</option>";
				}
		
	}


	public function actionListasignatura($nivel)	{

		$idcarr = '';
		$malla = '';
		$idmalla = 0;
		$str = str_replace("'", "", $nivel);
		$porciones = explode(";", $str);
		
		// echo var_dump($porciones); exit;
		//echo $porciones[1]; // porción1
		//echo "--";
		//echo $porciones[2]; // porción2
		//exit;
		//$pos = strpos($nivel, ';'); // $pos = 7, no 0
		//$pos1 = strpos($nivel, ';', 1); // $pos = 7, no 0
		
		$idnivel = $str[0];
		if ($porciones[0]) $idnivel = $porciones[0];
		if ($porciones[1]) $idmalla = $porciones[1];

		$asignmallas = DetalleMalla::find()
				->where(['idmalla' => $idmalla, 'nivel'=> $idnivel, 'estado'=> 1])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion', 'idsemestre'])
				//->orderBy('asignatura.NombAsig ASC')
				->all();
		if ($asignmallas){
			echo "<option>-</option>";
			foreach($asignmallas as $asignaturamalla){
				//$nombreasig = Asignatura::find()
				//		->where(['idAsig' => $asignaturamalla->asignatura->idasignatura])
				//		->one();
				//	$asignatura = $nombreasig?$nombreasig->NombAsig:'';
				//$asignatura = $asignaturamalla->asignatura->IdAsig . '-' . $asignaturamalla->asignatura->NombAsig;


					echo "<option value='".$asignaturamalla->id."'>"
						.$asignaturamalla->asignatura->IdAsig.'-'.$asignaturamalla->asignatura->NombAsig."</option>";
			}
		}
		//}
		
 
		
		else{
			echo "<option>-</option>";
			echo var_dump($idnivel, '_', $idmalla); exit;
		}
 
	}



    /**
     * Finds the CursoOfertado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CursoOfertado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CursoOfertado::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
