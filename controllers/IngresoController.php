<?php

namespace app\controllers;

use Yii;
use app\models\Ingreso;
use app\models\IngresoSearch;
use app\models\Informacionpersonal;
use app\models\TipoAdmision;
use app\models\Periodolectivo;
use app\models\Carrera;
use app\models\DetalleMalla;
use app\models\DetalleMatricula;
use app\models\CursoOfertado;
use app\models\Factura;
use app\models\MallaCarrera;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UploadForm;
use yii\web\UploadedFile;

/**
 * IngresoController implements the CRUD actions for Ingreso model.
 */
class IngresoController extends Controller
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
     * Lists all Ingreso models.
     * @return mixed
     */
    public function actionIndex()
    {
		$usuario = Yii::$app->user->identity;
        $searchModel = new IngresoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ingreso model.
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
     * Creates a new Ingreso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$usuario = Yii::$app->user->identity;
		$carreras = [];
		if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa'){

		    $model = new Ingreso();

		    if ($model->load(Yii::$app->request->post())) {
				//echo var_dump($model->idmalla), exit;
				$model->save();
		        //return $this->redirect(['view', 'id' => $model->id]);
				return $this->redirect(['index', 'IngresoSearch[CIInfPer]'=> $model->CIInfPer ]);
		    } 
			else {
				//**********************************************
				if (isset($usuario->idcarr)) {
					$carreras_user = explode("'", $usuario->idcarr);
					if (in_array('%', $carreras_user)) {
						$carreras = ArrayHelper::map(Carrera::find()
											#->where(['culminacion' => 1])
											//->andwhere(['!=','optativa' => 1])
											->orderBy(['nombcarr'=>SORT_ASC])
											->all(), 'idCarr', 'NombCarr');
					}
		
					else {
						$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
																->orderBy(['nombcarr'=>SORT_DESC])
																->all(), 'idCarr', 'NombCarr');
					}
				}
				//**********************************************
				//$carrera=ArrayHelper::map(Carrera::find()
				//			->Where(['statuscarr' => 1])->all(), 'idCarr', 'NombCarr');
				$periodos = ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=> SORT_DESC])->all(), 'idper','DescPerLec');
				$this->view->params['periodos'] = ($periodos?$periodos:null);
				$this->view->params['carrera'] = ($carreras?$carreras:null);
		
				$this->view->params['mallas'] = array();
				$admision=ArrayHelper::map(TipoAdmision::find()
							->Where(['estado' => 1])->all(), 'tad_id', 'tad_nombre');
				$this->view->params['admision'] = ($admision?$admision:null);
				$userid = (isset($usuario->id)?$usuario->id:'');
				$dataPeriodo=Periodolectivo::find()->where(['StatusPerLec' => 1])->one();
				$date = date('Y-m-d');
				$model->idper = $dataPeriodo?$dataPeriodo->idper:'';
				$model->usuario = $userid?$userid:'';
				$model->fecha = $date;
				$model->observacion = 'INGRESO POR FORMULARIO';


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
     * Updates an existing Ingreso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
		$model = $this->findModel($id);
		if ($model && ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'snna') ){

			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()
										->orderBy(['nombcarr'=>SORT_ASC])
										->all(), 'idCarr', 'NombCarr');
			}
		
			else {
				$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
												->orderBy(['nombcarr'=>SORT_DESC])
												->all(), 'idCarr', 'NombCarr');
			}

			$periodos = ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=> SORT_DESC])->all(), 'idper','DescPerLec');
			$this->view->params['carrera'] = ($carreras?$carreras:null);
			$this->view->params['periodos'] = ($periodos?$periodos:null);
			if ($usuario->idperfil == 'snna'){
			
				$mallas = ArrayHelper::map(MallaCarrera::find()
						->where(['idcarrera' => $model->idcarr])
						->andwhere(['like', 'detalle', '%SNNA%'])
						->one(), 'id', 'detalle');
		
			}
			else {
				$mallas = ArrayHelper::map(MallaCarrera::find()
						->where(['idcarrera' => $model->idcarr])
						->orderBy('detalle DESC')
						->all(), 'id', 'detalle');
			}
			$this->view->params['mallas'] = ($mallas?$mallas:null);
			$this->view->params['malla'] = ($model?$model->malla:null);
			$admision=ArrayHelper::map(TipoAdmision::find()
							->Where(['estado' => 1])->all(), 'tad_id', 'tad_nombre');
			$this->view->params['admision'] = ($admision?$admision:null);
			$model->usuario = $usuario->id;	
			if ($model->load(Yii::$app->request->post()) ) {
				$model->save();
				#echo var_dump($model->getErrors()); exit;
				return $this->redirect(['index', 'IngresoSearch[CIInfPer]'=> $model->CIInfPer]);
			}
			
			return $this->render('update', [
				    'model' => $model,
			]);

		}
		else {
			return $this->redirect(\Yii::$app->request->getReferrer());
		}
		

    }

    /**
     * Deletes an existing Ingreso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		$model = $this->findModel($id);
		$cedula = $model?$model->CIInfPer:'';
		if ($model && ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa')){
			$model->delete();
		}

        return $this->redirect(['index', 'IngresoSearch[CIInfPer]'=> $cedula]);
    }

	public function actionUpload()
    {
        $model = new UploadForm();
		#$hoy = date("Y-m-d");
		$usuario = Yii::$app->user->identity;
		$periodo = Periodolectivo::find()
							->where(['StatusPerLec' => 1])
							->orderBy('idPer DESC')
							->one();
		$query = null;
		if ( ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa') && $periodo) {
		    if (Yii::$app->request->isPost) {
		        $model->csvFile = UploadedFile::getInstance($model, 'csvFile');
		        if ($model->upload()) {
					#print_r($model->csvFile);exit();
					$handle = fopen('uploads/' . $model->csvFile, "r");
					while (($fileop = fgetcsv($handle, 1000, ",")) !== false) 
		            {
				        $cedula = $fileop[0];
				        $apellido1 = $fileop[1];
						$apellido2 = $fileop[2];
						$nombre = $fileop[3];
						$tipodoc = $fileop[4];
						$idcarr = $fileop[5];
				        $paralelo = $fileop[6];
						$idfactura = 0;
						$hayingreso = false;
						$detallemallas = null;
						$esalumno = $this->crearAlumno($cedula, $apellido1, $apellido2, $nombre, $tipodoc);
						$malla = MallaCarrera::find()
							->where(['idcarrera' => $idcarr, 'estado' => 1])
							->andWhere(['like', 'detalle', 'SNNA'])
							->orderBy('detalle DESC')
							->one();
						
						if ($malla && $esalumno) {
							$detallemallas = DetalleMalla::find()
								->where(['idmalla' => $malla->id, 'estado' => 1])
								->all();
							$hayingreso = $this->crearIngreso($periodo->idper, $idcarr, $cedula, $malla->id, $usuario->LoginUsu);
						}
						
						if ($hayingreso && $malla && $esalumno) {
							$idfactura = $this->crearFactura($periodo->idper, $cedula);	
						}
						
						if ($idfactura > 0 && $detallemallas && $esalumno) {
							foreach ($detallemallas as $detallemalla) {
								$modelcurso = CursoOfertado::find()
									->where(['idper'=> $periodo->idper, 'iddetallemalla'=> $detallemalla->id, 
											'paralelo'=> $paralelo, 'estado'=> 1])->one();
								#echo var_dump($periodo->idper,$detallemalla->id, $paralelo, $modelcurso); exit;
								if ($modelcurso) {
									$query = $this->crearMatricula($idfactura, $idcarr, $modelcurso);
								}
							}
						}
						
					}

					if ($query) {
						echo "data upload successfully";
					}
						    // file is uploaded successfully
					return $this->redirect(['veringreso', 'idper' => $periodo->idper]);
		        }
				return $this->redirect(['index']);
		    }

        	return $this->render('cargamasiva', ['model' => $model]);
		}
		return $this->redirect(['index']);
    }

	public function actionVeringreso($idper)
    {
        $searchModel = new IngresoSearch();
		$searchModel->idper = $idper;
		$searchModel->tipo_ingreso = 'SNNA';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('veringreso', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionListamalla($id)	{

		$idcarr = ($id?$id:'');
		$usuario = Yii::$app->user->identity;
		//echo "<option>ok</option>";
		if ($usuario->idperfil == 'snna'){
			
				$mallas = MallaCarrera::find()
						->where(['idcarrera' => $model->idcarr])
						->andwhere(['like', 'detalle', '%SNNA%'])
						->one();
		
			}
			else {
				$mallas = MallaCarrera::find()
				->where(['idcarrera' => $idcarr])
				//->andwhere(['idSemestre' => $idnivel])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion'])
				->orderBy('detalle DESC')
				->all();
			}
		#$mallas = MallaCarrera::find()
		#		->where(['idcarrera' => $idcarr])
				//->andwhere(['idSemestre' => $idnivel])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion'])
		#		->orderBy('detalle DESC')
		#		->all();
			//echo var_dump($idcarr); exit;
			if ($mallas){
				echo "<option>-</option>";
				foreach($mallas as $malla){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$malla->id."'>".$malla->detalle."</option>";
				}
			}
	}


    /**
     * Finds the Ingreso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ingreso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ingreso::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	protected function crearIngreso($idper, $idcarr, $cedula, $idmalla, $usuario)
    {
        if ( ($model = Ingreso::find()
				->where(['idper'=> $idper, 'idcarr'=> $idcarr, 'CIInfPer'=> $cedula, 'idmalla'=> $idmalla])->one()) === null) {
			$hoy = date("Y-m-d");
            $sql = "INSERT INTO ingreso(idper, idcarr, idmalla, 
								CIInfPer, fecha, tipo_ingreso, observacion, usuario) 
								VALUES ($idper, '$idcarr', $idmalla, '$cedula', '$hoy', 
								'SNNA','CARGA MASIVA CURSO NIVELACION Y ADMISION - ESMERALDAS', '$usuario')";
							#echo var_dump($sql); exit;
			$query = Yii::$app->db->createCommand($sql)->execute();
        	if (!$query)
				return false;
        }
		return true;
    }

	protected function crearAlumno($cedula, $apellido1, $apellido2, $nombre, $tipodoc)
    {
        if ( ($model = Informacionpersonal::find()->where(['CIInfPer'=> $cedula])->one()) === null) {
			$codigo = MD5($cedula);
            $sql = "INSERT INTO informacionpersonal(CIInfPer, cedula_pasaporte, TipoDocInfPer, ApellInfPer, 
								ApellMatInfPer, NombInfPer, statusper, codigo_dactilar) 
								VALUES ('$cedula', '$cedula', '$tipodoc', '$apellido1', '$apellido2', '$nombre', 1, '$codigo')";
							#echo var_dump($sql); exit;
            $query = Yii::$app->db->createCommand($sql)->execute();
			if (!$query)
				return false;
						
        }
		return true;
    }

	protected function crearFactura($idper, $cedula)
    {
        if ( ($model = Factura::find()
						->where(['idper'=> $idper, 'cedula'=> $cedula, 'tipo_documento'=> 'MATRICULA'])->one()) === null) {
			$hoy = date("Y-m-d H:i:s");
			$model = new Factura();
			$model->idper = $idper;
			$model->cedula = $cedula;
			$model->tipo_documento = 'MATRICULA';
			$model->fecha = $hoy;
			$model->total = 0;
			$model->observacion = 'SNNA';
			
			if (!$model->save())
				return 0;
						
        }
		return $model?$model->id:0;
    }

	protected function crearMatricula($idfactura, $idcarr, $modelcurso)
    {
		if ($modelcurso) {
		    if ( ($model = DetalleMatricula::find()->where(['idfactura'=> $idfactura, 'idcurso'=> $modelcurso->id])->one()) === null) {
				#$modelcurso = CursoOfertado::find()->where(['idcurso'=> $idcurso, 'estado'=> 1])->one();
				$hoy = date("Y-m-d");
				$idasig = $modelcurso->detallemalla->idasignatura;
				$nivel = $modelcurso->detallemalla->nivel;
				$paralelo = $modelcurso->paralelo;
				$sql = "INSERT INTO detalle_matricula(idfactura, idcarr, idcurso, 
									idasig, nivel, paralelo, credito, vrepite, costo, fecha, estado) 
									VALUES ($idfactura, '$idcarr', $modelcurso->id, '$idasig', 
									$nivel, '$paralelo', 0, 0, 0, '$hoy', 1)";
				#echo var_dump($sql); exit;
		        $query = Yii::$app->db->createCommand($sql)->execute();
				if (!$query) {
					return false;
				}			
		    }
			return true;
		}
		else
			return false;
    }

}
