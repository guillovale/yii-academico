<?php

namespace app\controllers;

use Yii;
use app\models\Asignatura;
use app\models\Bitacora;
use app\models\DetalleMatricula;
use app\models\Factura;
use app\models\DetalleMatriculaSearch;
use app\models\DetalleMalla;
use app\models\Periodolectivo;
use app\models\Notasalumnoasignatura;
use app\models\Matricula;
use app\models\Carrera;
use app\models\ExtensionMatricula;
use app\models\MallaCurricular;
use app\models\NotasDetalle;
use app\models\Informacionpersonal;
use app\models\InformacionpersonalD;
use app\models\Configuracion;
use app\models\CursoOfertado;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\db\Query;

require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;
/**
 * DetallematriculaController implements the CRUD actions for DetalleMatricula model.
 */
class DetallematriculaController extends Controller
{

	//public $layout = "/main";	

    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'vercupos', 'vernotas', 
							'index', 'enviarMail', 'veraprobados', 'reporte_egresados'],
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
     * Lists all DetalleMatricula models.
     * @return mixed
     */
    public function actionIndex( $idfactura)
    {
		$factura = Factura::find()
				->where(['id'=>$idfactura])
				->one();
		$hoy = date("Y-m-d");
		$fechamax = '';
		#$this->view->params['eliminar'] = 0;
		$this->view->params['eliminar'] = 1;
		$periodo = Periodolectivo::find()
				->where(['StatusPerLec'=>1])
				->one();
		$query = DetalleMatricula::find()
							->where(['idfactura'=>$idfactura])
							->orderBy(['idcarr'=>SORT_ASC, 'nivel'=>SORT_ASC, 'idasig'=>SORT_ASC,'paralelo'=>SORT_ASC]);
		
		$this->view->params['alumno'] = $factura?$factura->getNombreAlumno():'';
				//(count($alumno))?($alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' .$alumno->NombInfPer):'';
		$this->view->params['cedula'] = $factura?$factura->cedula:'';
		$this->view->params['idfactura'] = $factura?$factura->id:'';
		$idper = $factura?$factura->idper:0;
		#echo var_dump($query); exit;
	
		if ($periodo)	
			$fechamax = ($periodo->fechamaxeliminarmatricula)?$periodo->fechamaxeliminarmatricula:'';

		if ($hoy <= $fechamax && $periodo->idper == $idper) 
			$this->view->params['eliminar'] = 1;
		//echo var_dump($hoy, ' ', $fechamax); exit;		
		
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$searchModel = $dataProvider->getModels();

	    return $this->render('index', [
	        'searchModel' => $searchModel,
	        'dataProvider' => $dataProvider,
	    ]);
    }

    /**
     * Displays a single DetalleMatricula model.
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
     * Creates a new DetalleMatricula model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //$model = new DetalleMatricula();
		/*
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing DetalleMatricula model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$model = $this->findModel($id);
		if ($model) {
			$contarnotas = count($model->notas);
			#echo var_dump($contarnotas); exit;
			$idmalla = $model->curso?$model->curso->iddetallemalla:0;
			$idper = $model->factura->idper;
			$cedula = $model->factura->cedula;
			$alumno = $model->factura->getNombreAlumno();
			$idcarr = $model->curso?$model->curso->detallemalla->malla->idcarrera:0;
			$carreraop = $model->curso?$model->curso->detallemalla->malla->carrera->optativa:'';
			$nivel = $model->curso?$model->curso->detallemalla->nivel:'';
			$idasig = $model->curso?$model->curso->detallemalla->idasignatura:0;
			$fecha = date('Y-m-d');
			#$cursos = CursoOfertado::find()
			#		->where(['iddetallemalla' => $idmalla, 'idper' => $idper])
			#		->andwhere(['>=','curso_ofertado.fecha_fin', $fecha])
			#		->orderBy('paralelo ASC')
			#		->all();
			#$listacurso=$cursos?ArrayHelper::map($cursos,'id','paralelo'):'';
			#$this->view->params['listacurso'] = $listacurso;
			$this->view->params['cedula'] = $cedula;
			$this->view->params['alumno'] = $alumno;

			$detalleasig = DetalleMatricula::find()
				->select(['carrera.NombCarr as carrera', 'detalle_malla.nivel', 'cupo',
					'curso_ofertado.paralelo', 'asignatura.NombAsig as asignatura', 'idcurso', 
					'curso_ofertado.fecha_fin','COUNT(*) AS cnt'])
					->joinWith('curso')
					->joinWith('curso.detallemalla')
					->joinWith('curso.detallemalla.asignatura')
					->joinWith('curso.detallemalla.malla.carrera')
					->where(['curso_ofertado.idper' => $idper])
					->andwhere(['curso_ofertado.iddetallemalla' => $idmalla])
					->andwhere(['>=','curso_ofertado.fecha_fin', $fecha])
					->andwhere(['detalle_matricula.estado' => 1])
					->groupBy(['idcurso'])
					->orderBy(['curso_ofertado.paralelo'=>SORT_ASC]);
						#echo var_dump(count($detalleasig->all())); exit;
			$this->view->params['detalleasig'] = $detalleasig;
			$this->layout = "/cupos";
			$usuario = Yii::$app->user->identity;

		    if ( $model->load(Yii::$app->request->post()) ) {
				//$ids = ArrayHelper::getColumn($carreras, 'idCarr');
				$request = Yii::$app->request->post();
				#echo var_dump($model->idcurso); exit;
				#$idcurso = $request["DetalleMatricula"]["idcurso"];
				$curso = CursoOfertado::find()
					->where(['id' => $model->idcurso])
					->one();
				$paralelo = $curso?$curso->paralelo:'';
				$cupos = $curso?$curso->getCupos():0;
				#echo var_dump('idcurso: ',$model->idcurso,'paral: ', $paralelo, 'cupos: ', $cupos, 'cont: ', $contarnotas); exit;
				if ( $curso && ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' || $usuario->idperfil == 'centros') 
				#if ( ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' || $usuario->idperfil == 'centros') 
					&&	( in_array($model->idcarr, explode("'", $usuario->idcarr)) || 
					in_array('%', explode("'", $usuario->idcarr)) )	) {
					#echo var_dump($cupos, $contarnotas, $carreraop); exit;
					if ($cupos > 0 && ($contarnotas == 0 || $carreraop == 1) ) {
					#if ( ($contarnotas == 0 || $carreraop == 1) ) {
						#$model->idcurso = $curso->id;
						$model->paralelo = $paralelo;
						$model->save();
						#echo var_dump($model->Erros()); exit;
					}
					
					$total = DetalleMatricula::find()
									->where(['idfactura'=>$model->idfactura, 'estado'=> 1])->sum('credito*costo');
					
					if ($total >= 0) {
						$model->factura->valor_credito = $total;
						$model->factura->total = $model->factura->valor_matricula + $model->factura->valor_otro + $total;
						$model->factura->save();
					}
				}
				
				return $this->redirect(['detallematricula/index', 
									'idfactura'=>$model->idfactura, 'idper'=>$idper, 
									'cedula'=>$cedula, 'alumno'=>$alumno									
									]);
		    } else {
				//echo var_dump($model->getErrors()); exit;
		        return $this->render('update', [
		            'model' => $model,
		        ]);
		    }
		
		}	
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing DetalleMatricula model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	
		if ($model = $this->findModel($id)){
		
			$usuario = Yii::$app->user->identity;
			$alumno = $model->factura->cedula0;
			$periodo = $model->factura->periodo;
			$contarnotas = count($model->notas);
			$hoy = date("Y-m-d");
			
			if ($periodo && $usuario && $contarnotas == 0 && $alumno ) {
				$fecha_tope = $periodo->fechamaxeliminarmatricula;
				//echo var_dump($model->idcarr); exit;
				if ( $fecha_tope >= $hoy && ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa') &&
					( in_array($model->idcarr, explode("'", $usuario->idcarr)) || 
					in_array('%', explode("'", $usuario->idcarr)) ) ) {
					//echo var_dump($periodo->idper, '-', $idperiodo, '-', $fecha_tope, '-', $hoy); exit;
					$user = $usuario->LoginUsu;
					$cedula = $model->factura->cedula;
					$email = $alumno->mailPer;
					$asignatura = $model->idAsig->NombAsig;
					$carrera = $model->idCarr0?$model->idCarr0->NombCarr:'';
					$nivel = $model->nivel;
					$paralelo = $model->paralelo;
					$idnota = $model->idnota;
					$idfactura = $model->idfactura;
					$notaalumno = Notasalumnoasignatura::find()
									->where(['idnaa'=> $idnota])
									->one();
					#$model->estado = 0;
					if ($model->delete()) {
						$total = DetalleMatricula::find()
								->where(['idfactura'=>$idfactura, 'estado'=> 1])->sum('credito*costo');
						//echo var_dump($total); exit;
						   // return $this->redirect(Yii::$app->request->referrer);
						if ($total >= 0) {
							$factura = Factura::find()
										->where(['id'=> $idfactura])
										->one();
							if ($factura) {
								$factura->valor_credito = $total;
								$factura->total = $factura->valor_matricula + $factura->valor_otro + $total;
								$factura->save();
							}
						}

						$hoyhora = date("Y-m-d H:i:s");
							//echo var_dump($cedulad); exit;
						$texto = 'De acuerdo a lo solicitado, Dirección de Carrera con usuario:'. $user.
								' ha procedido con la anulación de matrícula '. 
								'Alumno Cédula: '. $cedula . ' Carrera: '.$carrera .
								' Asignatura: ' .$asignatura . ' nivel: '. 
								$nivel. ' paralelo: '.	$paralelo. ' Fecha: ' . $hoyhora;
						$bitacora = new Bitacora();
						$bitacora->bt_usuario = $user;
						$bitacora->bt_fechahora = $hoyhora;
						$bitacora->bt_accion = 'Anulación Matrícula';
						$bitacora->bt_ippc = Yii::$app->getRequest()->getUserIP();
						$bitacora->bt_observacion = $texto;
						$bitacora->save();
						if ($notaalumno) {
							$notaalumno->CalifFinal = 0;
							$notaalumno->asistencia = 0;
							$notaalumno->StatusCalif = 3;
							$notaalumno->observacion = 'ANULADA';
							$notaalumno->aprobada = 0;
							if ($notaalumno->save()) {
								$bitacora = new Bitacora();
								$bitacora->bt_usuario = $user;
								$bitacora->bt_fechahora = $hoyhora;
								$bitacora->bt_accion = 'Anulación Nota';
								$bitacora->bt_ippc = Yii::$app->getRequest()->getUserIP();
								$bitacora->bt_observacion = $texto.' Nota: ';
								$bitacora->save();
							}
						}
								
						try {
							$this->enviarMail($email, $texto);
						}catch (Exception $e) {
							echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						}
					}
					/*if ($model->delete()) {
						$hoyhora = date("Y-m-d H:i:s");
						//echo var_dump($cedulad); exit;
						$texto = 'De acuerdo a lo solicitado, Dirección de Carrera con usuario:'. $user.
								' ha procedido con la eliminación de : '. 
								'Alumno Cédula: '. $cedula . ' Carrera: '.$carrera .
								' Asignatura: ' .$asignatura . ' nivel: '. 
								$nivel. ' paralelo: '.	$paralelo;
						$bitacora = new Bitacora();
						$bitacora->bt_usuario = $user;
						$bitacora->bt_fechahora = $hoyhora;
						$bitacora->bt_accion = 'Eliminación Matrícula';
						$bitacora->bt_ippc = Yii::$app->getRequest()->getUserIP();
						$bitacora->bt_observacion = $texto;
						$bitacora->save();
						if ($notaalumno) {
							$nota = $notaalumno->CalifFinal;
							if ($notaalumno->delete()) {
								$bitacora = new Bitacora();
								$bitacora->bt_usuario = $user;
								$bitacora->bt_fechahora = $hoyhora;
								$bitacora->bt_accion = 'Eliminación Nota';
								$bitacora->bt_ippc = Yii::$app->getRequest()->getUserIP();
								$bitacora->bt_observacion = $texto.' Nota: '.$nota;
								$bitacora->save();
							}
						}

						//echo var_dump($bitacora->getErrors()); exit;		
			
						try {
							$this->enviarMail($cedula, $texto);
						}catch (Exception $e) {
							echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						}
						//echo var_dump($modelext->errors);exit;
					}*/
				}
			}
		}
	
		return $this->redirect(Yii::$app->request->referrer);
        //return $this->redirect(['index']);
    }

	public function actionVercupos() {
		$usuario = Yii::$app->user->identity;
		$export = 0;
		if ($usuario) {
			/*
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
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
			*/
			$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');

			$periodos = ArrayHelper::map(Periodolectivo::find()
															->orderBy(['idper'=>SORT_DESC])
															->all(), 'idper', 'DescPerLec');
			$this->view->params['carreras'] = $carreras;
			$this->view->params['periodos'] = $periodos;
		}
		else{
			$this->view->params['carreras'] = [];
			$this->view->params['periodos'] = [];

		}
		$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		
		//$this->layout = "/cupos";
		$searchModel = new DetalleMatriculaSearch();
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		

		if($export== 1)
                {
                        $searchModel->outputCSV($dataProvider->getModels(),'filename.csv');
                }

		return $this->render('vercupos', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }

	public function actionVernotas() {
		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			/*
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
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
			*/
			$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			$periodos = ArrayHelper::map(Periodolectivo::find()
															->orderBy(['idper'=>SORT_DESC])
															->all(), 'idper', 'DescPerLec');
			$this->view->params['carreras'] = $carreras;
			$this->view->params['periodos'] = $periodos;
		}
		else{
			$this->view->params['carreras'] = [];
			$this->view->params['periodos'] = [];

		}
		$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		//$notas_detalle = $this->getNotas(23718);
		//echo var_dump($notas_detalle); exit;
		//$this->layout = "/cupos";
		//$this->getPublicar();

		$searchModel = new DetalleMatriculaSearch();
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('vernotas', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }

	public function actionVeraprobados() {
		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			
			$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			$periodos = ArrayHelper::map(Periodolectivo::find()
															->orderBy(['idper'=>SORT_DESC])
															->all(), 'idper', 'DescPerLec');
			$this->view->params['carreras'] = $carreras;
			$this->view->params['periodos'] = $periodos;
		}
		else{
			$this->view->params['carreras'] = [];
			$this->view->params['periodos'] = [];

		}
		$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		//$notas_detalle = $this->getNotas(23718);
		//echo var_dump($notas_detalle); exit;
		//$this->layout = "/cupos";
		//$this->getPublicar();

		$searchModel = new DetalleMatriculaSearch();
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->searchaprobados(Yii::$app->request->queryParams);
		#echo var_dump($dataProvider->getModels()); exit;
		return $this->render('veraprobados', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }


	public function actionPublicar($idfactura)
    {
		$publicar = 0;
		$cursomodel = $this->findCurso($idcurso);
		$idper = $cursomodel?$cursomodel->idper:0;
		
		$periodo = Periodolectivo::find()
				->where(['idPer'=>$idper])
				->one();
		#echo var_dump($cursomodel->iddocente); exit;
		$idcarr = $cursomodel?$cursomodel->detallemalla->malla->carrera->idCarr:0;
		$iddocente = $cursomodel?$cursomodel->iddocente:'';
		$carrera = $cursomodel?$cursomodel->detallemalla->malla->carrera->NombCarr:'';
		$carreraoptativa = $cursomodel?$cursomodel->detallemalla->malla->carrera->optativa:0;
		$idasig = $cursomodel?$cursomodel->detallemalla->idasignatura:'';
		$asignatura = $cursomodel?$cursomodel->detallemalla->asignatura->NombAsig:'';
		$nivel = $cursomodel?$cursomodel->detallemalla->nivel:0;
		$paralelo = $cursomodel?$cursomodel->paralelo:'';

		if ($periodo) {
			$hoy  = date('Y-m-d');
			if ($hoy >= $periodo->examsupletorio_ini && $hoy <= $periodo->examsupletorio_fin)
				$publicar = 1;
			if ($carreraoptativa == 1)
				$publicar = 1;
			if ($periodo->StatusPerLec == 0)
				$publicar = 1;
		}
		$ca = configuracion::find()->where(['dato'=> 'CA'])->one();
		$cb = configuracion::find()->where(['dato'=> 'CB'])->one();
		$cc = configuracion::find()->where(['dato'=> 'CC'])->one();
		$ex = configuracion::find()->where(['dato'=> 'EX'])->one();
		$as = configuracion::find()->where(['dato'=> 'AS'])->one();
		$ct = configuracion::find()->where(['dato'=> 'CT'])->one();
		$compA = $ca?$ca->valor/100:0;
		$compB = $cb?$cb->valor/100:0;
		$compC = $cc?$cc->valor/100:0;
		$compEx = $ex?$ex->valor/100:0;
		$compAs = $as?$as->valor/100:0;
		$compT = $ct?$ct->valor/100:0;

		$this->view->params['publicar'] = $publicar;
		$this->view->params['idper'] = $idper;
		$this->view->params['idcarr'] = $idcarr;
		$this->view->params['idasig'] = $idasig;
		$this->view->params['periodo'] = $periodo?$periodo->DescPerLec:'';
		$this->view->params['carrera'] = $carrera;
		$this->view->params['asignatura'] = $asignatura;
		$this->view->params['nivel'] = $nivel;
		$this->view->params['paralelo'] = $paralelo;
		$this->view->params['idcurso'] = $idcurso;
		$this->view->params['ca'] = $compA;
		$this->view->params['cb'] = $compB;
		$this->view->params['cc'] = $compC;
		$this->view->params['ex'] = $compEx;
		$this->view->params['as'] = $compAs;
		$this->view->params['ct'] = $compT;
		//echo var_dump($compA, $compB, $compC); exit;
		$identity = Yii::$app->user->identity;
		    
		$docente = Informacionpersonald::find()->where(['CIInfPer'=>$iddocente])->one();
		if ($docente) {
				$this->view->params['docente'] = $docente->ApellInfPer . ' ' . $docente->ApellMatInfPer . ' ' . $docente->NombInfPer;
				$this->view->params['cedula'] = $iddocente;
		}
	
			
		if ($idcurso > 0) {
			$query = $this->getQuerynotas($idper, $idcurso);
		}
		else {
			#$query = $this->getHistoriconotas($idper, $iddocente, $idcarr, $idasig, $nivel, $paralelo);
		}

		//echo var_dump($query->all()); exit;
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pagesize' => 80,],
		]);

		$searchModel = $dataProvider->getModels();

		return $this->render('publicar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			#'matriculas' => $matriculas,
        ]);
    }

	public function getNotas($idmatricula)
    {
		$nota1 = 0;
		$nota2 = 0;
		$ca = configuracion::find()->where(['dato'=> 'CA'])->one();
		$cb = configuracion::find()->where(['dato'=> 'CB'])->one();
		$cc = configuracion::find()->where(['dato'=> 'CC'])->one();
		$ex = configuracion::find()->where(['dato'=> 'EX'])->one();
		$as = configuracion::find()->where(['dato'=> 'AS'])->one();
		$ct = configuracion::find()->where(['dato'=> 'CT'])->one();
		$compA = $ca?$ca->valor/100:0;
		$compB = $cb?$cb->valor/100:0;
		$compC = $cc?$cc->valor/100:0;
		$compEx = $ex?$ex->valor/100:0;
		$compAs = $as?$as->valor/100:0;
		$compT = $ct?$ct->valor/100:0;

		$subquery = NotasDetalle::find()
					->select(['factura.cedula,libreta_calificacion.idparametro, idlibreta, iddetallematricula, 
						hemisemestre, idfactura, idcomponente, nota, 
						concat(ApellInfPer, " ", ApellMatInfPer, " ", NombInfPer) as estudiante,
						IF(hemisemestre = 1 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA1,
						IF(hemisemestre = 1 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB1,
						IF(hemisemestre = 1 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC1,
						IF(hemisemestre = 1 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM1,
						IF(hemisemestre = 1 && sigla = "T", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Ast1,
						IF(hemisemestre = 2 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA2,
						IF(hemisemestre = 2 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB2,
						IF(hemisemestre = 2 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC2,
						IF(hemisemestre = 2 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM2,
						IF(hemisemestre = 2 && sigla = "T", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Ast2,
						IF(hemisemestre = 0 && sigla = "R", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Rec
						'])
					->leftJoin('libreta_calificacion', 'libreta_calificacion.id = notas_detalle.idlibreta')
					->leftJoin('parametroscalificacion', 'parametroscalificacion.idparametro = libreta_calificacion.idparametro')
					->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
					->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
					->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
					->where(['iddetallematricula'=>$idmatricula])
					->groupBy(['libreta_calificacion.hemisemestre','libreta_calificacion.idparametro'])
					->orderBy(['libreta_calificacion.hemisemestre'=>SORT_ASC, 'libreta_calificacion.idparametro'=>SORT_ASC]);
		$query = new Query();
		$query->select(['*'])
			->addSelect(['sum(c.CompA1) as A1, sum(c.CompB1) as B1, sum(c.CompC1) as C1, sum(c.EXM1) as Ex1, sum(c.Ast1) as As1,
						sum(c.CompA2) as A2, sum(c.CompB2) as B2, sum(c.CompC2) as C2, sum(c.EXM2) as Ex2, sum(c.Ast2) as As2,
						sum(c.Rec) as Suf
						'])
			->from(['c' => $subquery]);
		$row = $query->one();
		$estado = '';
		//$recuperacion = 0;
		//$sumanotas = 0;
		$notas = [];
		$aprobada = 0;
		
		if (count($row)) { 
			$nota1 = round( ($row["A1"]*$compA + $row["B1"]*$compB + $row["C1"]*$compC)*$compT + $row["Ex1"]*$compEx );
			$nota2 = round( ($row["A2"]*$compA + $row["B2"]*$compB + $row["C2"]*$compC)*$compT + $row["Ex2"]*$compEx );
			$asis1 = round($row["As1"] >=0 && $row["As1"] <=10)?$row["As1"]*10:$row["As1"];
			$asis2 = round($row["As2"] >=0 && $row["As2"] <=10)?$row["As2"]*10:$row["As2"];
			$promedionota = round((round($nota1) + round($nota2))/2, 2);
			$promedioasistencia = round((round($asis1) + round($asis2))/2, 2);
			$recp = round($row["Suf"]?$row["Suf"]:0);
			if ( $promedionota >= 7 && ($promedioasistencia >= 70 && $promedioasistencia <= 100) ) {
				$estado = 'APROBADA';
				$aprobada = 1;	
			}
			elseif ( $promedionota >= 5 && $promedionota < 7 && ($promedioasistencia >= 70 && $promedioasistencia <= 100) )
				if ($promedionota*2 + $recp >= 20){
					$estado = 'APROBADA';
					$promedionota = 7;
					$aprobada = 1;
				}
				else {
					$estado = 'REPROBADA';
					$aprobada = 0;
				}
			else {
				$estado = 'REPROBADA';
				$aprobada = 0;
			}
			
			$notas = ['nota1'=> $nota1, 'nota2'=> $nota2, 'asis1'=> $asis1, 'asis2'=> $asis2, 'recp'=> $recp,
					'nota'=> $promedionota, 'asistencia'=> $promedioasistencia, 
					'estado'=> $estado, 'aprobada'=> $aprobada];
		}
		return $notas;
		// echo var_dump($notas); exit;
    }

	public function actionReporte_egresados() {

		$query = new Query;
		$subQuery = new Query;
		$queryalumno = new Query;
		$request = Yii::$app->request->get();
				#$idcurso = $request["DetalleMatricula"]["idcurso"];
		#echo var_dump($request); exit;
				
		$idper = isset($request["DetalleMatricula"]["idfactura"])?$request["DetalleMatricula"]["idfactura"]:0;
		$idcarr = isset($request["DetalleMatricula"]["idcarr"])?$request["DetalleMatricula"]["idcarr"]:'';
		$periodo = Periodolectivo::find()
			->where(['idper' => $idper ])
			#->orderBy(['idper' => SORT_DESC])
			->one();
		#$idper =  $periodo?$periodo->idper:0;
		$usuario = Yii::$app->user->identity;
		$carreras = [];
		if ($usuario) {
			
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
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
		}
		
	
		$nombrecarrera = Carrera::find()->where(['idCarr'=>$idcarr])->one();
		$nombrecarr = $nombrecarrera?$nombrecarrera->NombCarr:'';
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
		$this->view->params['carrera'] = $carreras;
		$this->view->params['nombrecarrera'] = $nombrecarr;
	
		$subQuery->select(['f.idper', 'f.cedula', 'm.idcarr', 'i.idmalla'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('carrera c', 'm.idcarr = c.idcarr')
		->leftJoin('ingreso i', 'i.idcarr = m.idcarr and i.CIInfPer = f.cedula')
		->where(['f.idper'=> $idper, 'm.idcarr'=> $idcarr, 'm.estado'=>1, 'f.tipo_documento'=> 'MATRICULA'])
		//->andwhere('idcarr not in("056", "197", "206", "601", "602","603")')
		#->andwhere("f.cedula = '1718771841'")
		#->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula'])
		->orderBy(['i.idmalla' => SORT_DESC]);
		#->orderBy(['m.nivel' => SORT_DESC]);
		
		$alumnos = $subQuery->all();
		$listaalumnos = [];
		foreach($alumnos as $alumno) {
			#$notas = $this->getNotas($nota->iddetalle);
			$culmino = 1000;
			$idmalla = $alumno?$alumno['idmalla']:0;
			$cedula =  $alumno?$alumno['cedula']:'';
					//if ($cont >= 100)
			if ($idmalla > 0) {
				$sql = 'SELECT idasignatura from detalle_malla dm
					WHERE idmalla = '.$idmalla.
					' and idasignatura not in (SELECT idasig from notasalumnoasignatura where CIInfPer = "'.$cedula.'" and aprobada = 1)
					and idasignatura not in (SELECT asignatura FROM equivalencia
					where equivalencia in (SELECT idasig from notasalumnoasignatura where CIInfPer = "'.$cedula.'" and aprobada = 1)
					ORDER BY equivalencia.asignatura  DESC)
					and estado = 1';

				$culmino = count(DetalleMalla::findBySql($sql)->all());
				#echo var_dump($listaalumnos, $culmino ); exit;
			}
			if ($culmino == 0 ) {
				array_push($listaalumnos,$cedula);
				
			}
			
		}

		$queryalumno->from('informacionpersonal')->where(['CIInfPer'=> $listaalumnos])->orderBy('ApellInfPer',
		'ApellMatInfPer',
		'NombInfPer');
		#echo var_dump($queryalumno->all(), $listaalumnos ); exit;

		$dataProvider = new ActiveDataProvider([
            'query' => $queryalumno,	
				'sort' =>false,
				'pagination' => [
				'pageSize' => 200,
			    ],
        ]);
		#$searchModel = $subQuery->all();
		$searchModel = new DetalleMatricula;
		#echo var_dump($searchModel); exit;
		
        return $this->render('reporteegresado', [
            'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
            
        ]);
        // return $this->render('index');
    }

	public function actionPublicar1()
    {
		//echo var_dump(Yii::getLogger()->getElapsedTime()); exit;
		//$notasdetalle = NotasDetalle::find()
						//->where(['not', ['usuario' => null]]);
						//->where(['usuario'=> '0802813725'])->all();
		//$idmatriculanotas = ArrayHelper::getColumn($notasdetalle, 'iddetallematricula');
		//$query = new Query;             
		$query = DetalleMatricula::find()
				->select('detalle_matricula.id')
				//->from('detalle_matricula')
				->joinWith('notas')                               
				->where(['>', 'notas_detalle.fecha', '2017-11-01'])
				//->groupBy(['detalle_matricula.id'])
			    ->orderBy(['detalle_matricula.id' => SORT_ASC])
				->all();
		$detallematricula = DetalleMatricula::find()
							->joinWith('factura')
							//->where(['in','id', $idmatriculanotas])
							->joinWith('notas')
							//->join('LEFT JOIN', 'factura', 'factura.id = detalle_matricula.idfactura')
							//->join('LEFT JOIN', 'notas_detalle', 'notas_detalle.iddetallematricula = detalle_matricula.id')
							//->join('LEFT JOIN', 'notas_detalle', 'notas_detalle.iddetallematricula = detalle_matricula.id')
							//->where(['factura.idper'=>108])
							//->where(['not', ['usuario' => null]])
							//->andWhere(['idcarr' => '015'])
							->andWhere(['factura.cedula' => '0802835488'])
							//->orderBy(['idcarr'=>SORT_ASC, 'nivel'=>SORT_ASC, 'idasig'=>SORT_ASC,'paralelo'=>SORT_ASC])
							->all();
		
		//echo var_dump(count($query), ' ', Yii::getLogger()->getElapsedTime()); exit;
		if ($detallematricula) {
			ini_set('max_execution_time', 900);
			//ini_set('memory_limit', '1024MB');
			$ids = ArrayHelper::getColumn($detallematricula, 'idnota');
			$notasalumno = Notasalumnoasignatura::find()
									->where(['in','idnaa', $ids])
									//->where(['iddetalle', $query])
									//->andWhere(['StatusCalif' => 1])
									->all();
			//echo var_dump(count($notasalumno), ' ', Yii::getLogger()->getElapsedTime()); exit;
			//$cont = 1;
			if (count($notasalumno)) {

				foreach($notasalumno as $nota) {
					$notas = $this->getNotas($nota->iddetalle);
					//if ($cont >= 100)
					//	break;
					if ($notas["nota"] >= 0 && $notas["asistencia"] >=0 && $notas["aprobada"] >= 0) {
							//echo var_dump($notasalumno); exit;
						//if ($nota->StatusCalif != 3) {

							$nota->CalifFinal = $notas["nota"];
							$nota->asistencia = $notas["asistencia"];
							$nota->StatusCalif = 3;
							$nota->observacion = $notas["estado"];
							$nota->aprobada = $notas["aprobada"];
							//$notasalumno->save(false);
								//echo var_dump($notasalumno->getErrors()); exit;
								if (!$nota->save(false)) {
									echo var_dump($nota->getErrors()); exit;
								}
								//$cont = $cont + 1;
						//}
						
					}
				}
				//echo var_dump(count($notasalumno), ' ', Yii::getLogger()->getElapsedTime()); exit;			
				
			}		
		}
	
		return $this->redirect(Yii::$app->request->referrer);

	}

	public function actionListaasignatura($id)	{

		$porciones = explode(";", $id);
		$nivel = ($porciones[0]?$porciones[0]:'');
		$idper = ($porciones[1]?$porciones[1]:'');
		$idcarr = ($porciones[2]?$porciones[2]:'');
		
		//echo "<option>$nivel $idper $idcarr</option>";
		$asignmallas = Detallematricula::find()
				->joinWith(['factura'])
				->joinWith(['idAsig'])
				->where(['factura.idper' => $idper])
				->andWhere(['idcarr' => $idcarr])
				->andwhere(['nivel' => $nivel])
				
				->groupBy(['idasig'])
				->orderBy('asignatura.NombAsig ASC')
				->all();
			#echo var_dump($asignmallas); exit;
			if ($asignmallas){
				echo "<option></option>";
				foreach($asignmallas as $asignaturamalla){
					
					echo "<option value='".$asignaturamalla->idasig."'>"
						.$asignaturamalla->idasig.'-'.$asignaturamalla->idAsig->NombAsig."</option>";
				}
			}
	}

	public function actionListaparalelo($id)	{

		$porciones = explode(";", $id);
		$idper = ($porciones[0]?$porciones[0]:'');
		$idcarr = ($porciones[1]?$porciones[1]:'');
		$idnivel = ($porciones[2]?$porciones[2]:'');
		$idasig = ($porciones[3]?$porciones[3]:'');
		
		//echo "<option>".$idasig."</option>";
		$paralelos = Detallematricula::find()
				->joinWith(['factura'])
				//->joinWith(['asignatura'])
				->where(['factura.idper' => $idper])
				->andwhere(['idcarr' => $idcarr])
				->andwhere(['nivel' => $idnivel])
				->andwhere(['idasig' => $idasig])
				->groupBy(['paralelo'])
				->orderBy('paralelo ASC')
				->all();
			//echo var_dump($idcarr); exit;
			if ($paralelos){
				echo "<option></option>";
				foreach($paralelos as $paralelo){
					//echo "<option value='".$paralelo->matricula->idParalelo."'>"."</option>";
					echo "<option value='".$paralelo->paralelo."'>".$paralelo->paralelo."</option>";
				}
			}
	}

	public function actionListacurso($idcurso)	{

		$curso = CursoOfertado::findOne($idcurso);
		$idmalla = $curso?$curso->iddetallemalla:0;
		$idper = $curso?$curso->idper:0;
		//echo var_dump($curso); exit;
		//echo "<option>".$idmalla."</option>";

		$cursos = CursoOfertado::find()
				->where(['iddetallemalla' => $idmalla, 'idper' => $idper])
				->orderBy('paralelo ASC')
				->all();
			//echo var_dump($idcarr); exit;
			if ($cursos){
				echo "<option>-</option>";
				foreach($cursos as $curso){
					//echo "<option value='".$paralelo->matricula->idParalelo."'>"."</option>";
					echo "<option value='".$curso->paralelo."'>".$curso->paralelo."</option>";
				}
			}
	}

	//enviar mail
	public function enviarMail($email, $texto)
	{
		$emaildamarys = 'damarys.garcia@utelvt.edu.ec';
		$emailtics = 'tics@utelvt.edu.ec';
		$emailacademico = 'viceacademico@utelvt.edu.ec';
		$emailacademico1 = 'vicedama@utelvt.edu.ec';

		if ($email === NULL) {
			$email = $emailtics;
		}
				
		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($email)
				//->setCc($emailtics)
				->setSubject('Eliminación de asignatura')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailtics)
				->setSubject('Eliminación de asignatura')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailacademico)
				->setSubject('Eliminación de asignatura')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailacademico1)
				->setSubject('Eliminación de asignatura')
				->setTextBody($texto)
				->send();

		#$message = Yii::$app->mailer->compose();
		#$message->setFrom(Yii::$app->params['adminEmail'])
		#		->setTo($emaildamarys)
		#		->setCc($emailmarco)
		#		->setSubject('Eliminación de nota')
		#		->setTextBody($texto)
		#		->send();
			
	}

	public function actionImprimir_cp($idfactura) {

		$query = new Query;
		// compose the query
		$query->select('detalle_matricula.id, notasalumnoasignatura.CIInfPer, ApellInfPer, ApellMatInfPer,NombInfPer,
				carrera.NombCarr, asignatura.NombAsig, , periodolectivo.DescPerLec, 
				notasalumnoasignatura.CalifFinal, notasalumnoasignatura.observacion, 
				notasalumnoasignatura.asistencia, notasalumnoasignatura.idAsig, 
				notasalumnoasignatura.aprobada, detalle_matricula.nivel, detalle_matricula.idcarr,
				')
			->from('notasalumnoasignatura')
			->join('INNER JOIN', 'detalle_matricula', 'detalle_matricula.id = notasalumnoasignatura.iddetalle')
			->join('INNER JOIN', 'carrera', 'carrera.idCarr = detalle_matricula.idcarr')
			->join('INNER JOIN', 'asignatura', 'asignatura.IdAsig = notasalumnoasignatura.IdAsig')
			->join('INNER JOIN', 'periodolectivo', 'periodolectivo.idper = notasalumnoasignatura.idper')
			->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = notasalumnoasignatura.CIInfPer')
			//->join('right JOIN', 'malla_curricular', 'malla_curricular.idAsig = notasalumnoasignatura.idAsig')
			->Where(['detalle_matricula.idfactura'=>  $idfactura])
			->andwhere(['<>', 'carrera.optativa', 1])
			->orderBy([
				'detalle_matricula.idcarr'=>SORT_ASC, 'detalle_matricula.nivel'=>SORT_ASC, 
				'asignatura.NombAsig'=>SORT_ASC,
				//'idmatriculas.idsemestre' => SORT_ASC,
				//'asignatura.NombAsig' => SORT_ASC,
			]);

		// build and execute the query
		$rows = $query->all();
		$row = $query->one();

		$pdf = new MYPDF();	 
		#echo var_dump($row, $idfactura); exit;
		if ($row) {
			$img_file = K_PATH_IMAGES.'logo.jpg';

			// set document information
			$pdf->SetCreator(PDF_CREATOR);  
			$pdf->SetAuthor('tics');
			$pdf->SetTitle("Notas publicadas");                
			$pdf->SetHeaderData("CERTIFICADO DE PROMOCIÓN");
			$pdf->setFooterData(array(0,64,0), array(0,64,128));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->SetFont('helvetica', '', 8);
			$pdf->SetTextColor(0,0,0);
		
				// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage();

			$fecha = date('d-m-Y');
			//$row = $query->one();
			$cedula = $row["CIInfPer"];
			$alumno = $row["ApellInfPer"] . ' ' . $row["ApellMatInfPer"]. ' ' . $row["NombInfPer"];
			$periodo = $row["DescPerLec"];
			//$carrera = $row["NombCarr"];
			//$periodo = $row["DescPerLec"];
			$html = "<div style='margin-bottom:12px;'>
				
				<br><br><br>
			Esmeraldas, $fecha <br>

			 <br><address>
				Período: $periodo <br><br>
				Cédula: $cedula <br><br>
				Alumno: <b>$alumno </b><br>
				
				</address></b><br>
				</div>";
			//Convert the Html to a pdf document
			$pdf->writeHTML($html, true, false, true, false, '');
		 
			$header = array('Matrícula No', 'carrera', 'nivel', 'asignatura',  'Nota Final', 'Asistencia', 'Observación'); 
		 
			// print colored table
			$this->ColoredTable($pdf,$header, $rows);
			$pdf->setY(263);
			$style = array(
				'border' => true,
				'vpadding' => 'auto',
				'hpadding' => 'auto',
				'fgcolor' => array(0,0,0),
				'bgcolor' => false, //array(255,255,255)
				'module_width' => 1, // width of a single module in points
				'module_height' => 1 // height of a single module in points
			);
			//if(isset($matricula)){
			#$pdf->write2DBarcode($cedula, 'RAW', 80, 30, 30, 20, $style, 'N');
			$pdf->write1DBarcode($idfactura, 'C39', '', '', '', 5, 0.2, $style, 'N');
			$pdf->Cell(0, 0, $idfactura, 0, 1);
			

			// reset pointer to the last page
			$pdf->lastPage();
			$file = $cedula . '_' . $fecha . '.' . 'pdf';
			//Close and output PDF document
			$pdf->Output($file, 'D');
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	// Colored table
    public function ColoredTable($pdf,$header,$data) {
        // Colors, line width and bold font
        $pdf->SetFillColor(120, 185, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(120, 185, 120);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B', '8');
        // Header
        $w = array(18, 15, 15, 80, 15, 15, 25);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;
		$suma = 0;
		$total = 0;
		$carreras = array();
        foreach($data as $row) {
		
			//echo var_dump($row['NombCarr']);
			//exit;
			$suma += round($row['CalifFinal'], 2);
			$observacion = '';
			if (!in_array($row['NombCarr'], $carreras)) {
				array_push($carreras, $row['NombCarr'] );
			}
			if ($row['aprobada'] == 1)
				$observacion = 'APROBADA';
			else if  ($row['aprobada'] == 0)
				$observacion = 'REPROBADA';
			$pdf->Cell($w[0], 6, $row['id'], 'LR', 0, 'L', $fill);
			//$pdf->Cell($w[1], 6, number_format($row['idsemestre']), 'LR', 0, 'C', $fill);
			$pdf->Cell($w[1], 6, $row['idcarr'], 'LR', 0, 'L', $fill);
			$pdf->Cell($w[2], 6, $row['nivel'], 'LR', 0, 'C', $fill);
			$pdf->Cell($w[3], 6, $row['NombAsig'], 'LR', 0, 'L', $fill);
			$pdf->Cell($w[4], 6, $row['CalifFinal'], 'LR', 0, 'C', $fill);
			$pdf->Cell($w[5], 6, $row['asistencia'].'%', 'LR', 0, 'C', $fill);
			$pdf->Cell($w[6], 6, $row['observacion'], 'LR', 0, 'C', $fill);
			$pdf->Ln();
			$fill=!$fill;
        }
		$total = round($suma/count($data), 2);
		$pdf->SetFont('', 'B', '9');
		$pdf->Cell($w[0], 6, '', 'LR', 0, 'L', $fill);
		$pdf->Cell($w[1], 6, '', 'LR', 0, 'L', $fill);
		$pdf->Cell($w[2], 6, '', 'LR', 0, 'L', $fill);
		$pdf->Cell($w[3], 6, 'Promedio:', 'LR', 0, 'R', $fill);
        $pdf->Cell($w[4], 6, $total, 'LR', 0, 'C', $fill);
		$fill=!$fill;
		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFont('', 'B', '7');
		$cadena = implode(",",$carreras);
		$pdf->Write(0, $cadena, '', 0, 'L', true, 0, false, false, 0);
		
		#echo var_dump($carreras); exit;
		#$pdf->Cell($w, 0, $cedula, 'T');
    }

    /**
     * Finds the DetalleMatricula model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DetalleMatricula the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DetalleMatricula::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

//**********************************************************************************************************************************
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		
	// Set font
	$this->SetFont('helvetica', 'B', 11);
	// Title
	$titulo = "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS";
		
	$this->Cell(0, 10, $titulo, 0, false, 'C', 0, '', 0);
	$this->Cell(0, 20, "Secretaría Académico                                                             . ", 0, false, 'R', 0, '', 0);
	$this->Cell(0, 30, "Esmeraldas Ecuador                                                              . ", 0, false, 'R', 0, '', 0);

#$this->Cell(0, 40, "CERTIFICADO DE PROMOCIÓN", 0, false, 'C', 0, '', 0);
$this->Cell(0, 40, "CERTIFICADO DE PROMOCIÓN                                   						        	    	 . ", 0, false, 'R', 0, '', 0, false, 'T', 'M');
	
	$imager_file = K_PATH_IMAGES.'logo.jpg';
	$imagel_file = K_PATH_IMAGES.'sello_Ecuador.png';
	$this->Image($imagel_file, 15, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
	$this->Image($imager_file, 175, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
    }

    // Page footer
	
    public function Footer() {
	// Position at 15 mm from bottom
		$texto = "F. :__________________________";
	$this->SetY(-15);
	// Set font
	$this->SetFont('helvetica', 'I', 8);
	$this->Cell(0, 1, $texto, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	//$pdf->write1DBarcode($this->$iddocente, 'C39', '', '', '', 5, 0.2, '', 'N');
	// Page number
	$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
}
