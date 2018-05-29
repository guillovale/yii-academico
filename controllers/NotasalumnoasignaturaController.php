<?php

namespace app\controllers;

use Yii;
use app\models\Asignatura;
use app\models\Matricula;
use app\models\Notasalumnoasignatura;
use app\models\NotasalumnoasignaturaSearch;
use app\models\Informacionpersonal;
use app\models\Ingreso;
use app\models\MallaEstudiante;
use app\models\MallaCurricular;
use app\models\MallaCarrera;
use app\models\DetalleMalla;
use app\models\Carrera;
use app\models\Equivalencia;
use app\models\Periodolectivo;
use app\models\Factura;
use app\models\Usuario;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//requiere extensión TCPDF
require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;
//require_once(dirname(__FILE__).'/tcpdf_autoconfig.php');
//use app\vendor\tcpdf\tcpdf;
//use app\widgets\Item;

//use app\vendor\tcpdf;

/**
 * NotasalumnoasignaturaController implements the CRUD actions for Notasalumnoasignatura model.
 */
class NotasalumnoasignaturaController extends Controller
{

	public $layout = "/main";

    public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete','update', 'create', 'homologar'],
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
     * Lists all Notasalumnoasignatura models.
     * @return mixed
     */
    public function actionIndex()
    {
		$this->layout = "/column3";
		$params = Yii::$app->request->queryParams;
        $searchModel = new NotasalumnoasignaturaSearch();
		$searchModel->aprobada = 1; 
		#echo var_dump($params['NotasalumnoasignaturaSearch']['CIInfPer']); exit;
		#$searchModel->CIInfPer = 
		#		isset($params['NotasalumnoasignaturaSearch']['CIInfPer'])?$params['NotasalumnoasignaturaSearch']['CIInfPer']:'';
        $dataProvider = $searchModel->search($params);

	// app\widgets\Mallas::widget(['carrera' => $carrera,'cedula' => $cedula ]))
	
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notasalumnoasignatura model.
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
     * Creates a new Notasalumnoasignatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	public function actionCreate($cedula)
	{
		//return $this->goHome();
		$alumno = Informacionpersonal::find()
			->where(['CIInfPer' => $cedula])
			->one();
		$this->layout = "/columna_recientes";	
		$usuario = Yii::$app->user->identity;
		$userid = $usuario?$usuario->id:'';
		$usercar = ArrayHelper::getColumn(Usuario::find()->Where(['LoginUsu' => $userid])->all(), 'idcarr');
		$carreras = str_replace("'", '', implode(",",$usercar));
		$porciones = explode(",", $carreras);
		//yii::$app->request->referrer;

		if ($carreras == "%")
			$dataCarrera=ArrayHelper::map(Carrera::find()
				->Where(['culminacion' => 1,])
				->orderBy(['nombcarr'=>SORT_ASC])
				->all(), 'idCarr', 'NombCarr');
				
		else	{
				//$carreras = ArrayHelper::getColumn(Carrera::find()
				//	->where(['idCarr'=> $porciones])->all(), 'idCarr');
				$dataCarrera=ArrayHelper::map(Carrera::find()
					->Where(['culminacion' => 1, 'idCarr'=> $porciones ])
					->orderBy(['nombcarr'=>SORT_ASC])
					->all(), 'idCarr', 'NombCarr');
		}


		$model = new Notasalumnoasignatura();
		$modelmatricula = new Matricula();
		$this->view->params['cedula'] = $cedula;
		$this->view->params['carrera'] = $dataCarrera;	

		if ($alumno) {
			$model->CIInfPer = $alumno->CIInfPer;
			$modelmatricula->CIInfPer = $alumno->CIInfPer;
			$email = $alumno->mailPer;
		}
	
		// quitar && Yii::$app->user->identity->LoginUsu=='0800428849' para crear a todos nota 
		if ( $alumno && $model->load(Yii::$app->request->post()) && 
				$modelmatricula->load(Yii::$app->request->post())&& ($usuario->idperfil == 'diracad'))
		{
			$matricula = Matricula::find()
					->where(['CIInfPer'=>$alumno->CIInfPer, 'idPer'=>$model->idPer, 
					'idCarr'=>$modelmatricula->idCarr, 'idsemestre'=>$modelmatricula->idsemestre])
					->one();

			if (!empty($matricula)) {
				$model->idMatricula = $matricula->idMatricula;
			}
			else {
				
				//***********************************************************
				$i = 1;
				$noexiste = 0;
				$periodo = Periodolectivo::find()
						->where(['idper'=>$model->idPer])
						->one();
				$matricula=Matricula::findBySql("select idMatricula, 
					max(cast(SUBSTRING(idMatricula,14) as unsigned)) as maximo from matricula  
					where matricula.idCarr = ".$modelmatricula->idCarr." 
					and idper = ". $model->idPer. " and idsemestre = ". $modelmatricula->idsemestre. 
					" order by maximo desc limit 1")
					->one();


				
				if (!empty($matricula)) 
				{
					//$matriculaid = intval(substr($matricula->idMatricula,13)) + 1;
					
					$maximo = substr($matricula->idMatricula,13);
					$maximo = $maximo + 1;
					
					$maximo = (strlen($maximo) >= 3) ? $maximo : ((strlen($maximo) == 2) ? 
						('0'.$maximo) : ('00'.$maximo));

					//$matriculaid = substr($matricula->idMatricula,0,13).$matriculaid;
				}
				else
				{
					$maximo = '001';
				}
				

				while ($i <= 1000 && $noexiste == 0){
					
					$matriculaid = $periodo->DescPerLec.$modelmatricula->idCarr.
						($modelmatricula->idsemestre >= 10 ?$modelmatricula->idsemestre:('0'.$modelmatricula->idsemestre)).$maximo;
					$id_mat = Matricula::find()
						->where(['idMatricula'=>$matriculaid])
						->one();

					if (empty($id_mat)) $noexiste = 1;
					
					$i = $i + 1;
					$maximo = $maximo + 1;
				}

				if (!empty($id_mat)){var_dump($id_mat, '--', $matriculaid); exit;}
				
				//***********************************************************

				$model->idMatricula = $matriculaid;
				//completar matrícula
				$modelmatricula->idPer = $model->idPer;
				$modelmatricula->idMatricula = $matriculaid;
				$modelmatricula->idanio = 0;
				$modelmatricula->FechaMatricula = date('Y-m-d H:i:s');
				$modelmatricula->statusMatricula = 'APROBADA';
				$modelmatricula->observMatricula = 'Creada por memorando';
				$modelmatricula->Usu_registra = $usuario->LoginUsu;
				
				if(!$modelmatricula->save())
					return $this->redirect(\Yii::$app->request->getReferrer());	
					//Yii::app()->user->setFlash('success',$modelmatricula->idMatricula);
			}

			//completar notas
			//$model->CIInfPer = $modelmat->CIInfPer;
			//$model->idPer = $modelmat->idPer;
			$model->StatusCalif = 3;
			$model->VRepite = 1;
			$model->aprobada = 1;
			$model->registro = date('Y-m-d H:i:s');
			$model->usu_pregistro = $usuario->LoginUsu;
			$model->observacion_efa = 'Subido por memorando';
			
			if($model->save())
			{
				$hoyhora = date("Y-m-d H:i:s");
				$texto = 'De acuerdo a lo solicitado,  
							Vicerrectorado Académico ha procedido con la Creación de nota con '. 
							'Cédula: '. $model->CIInfPer . ' Asignatura: ' .$model->idAsig . 
							' nota: '.$model->CalifFinal . ' Asistencia: ' . $model->asistencia . ' Fecha: ' . $hoyhora;
				try {
					$this->enviarMail($email, $texto);
				}catch (Exception $e) {
					echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				}
				//echo var_dump($model->idnaa, $model->CIInfPer, $model->idMatricula, $model->CalifFinal, 
				//$model->observacion, $model->asistencia, $model->idPer, $model->idMc, $model->getErrors()); exit;
				return $this->redirect(Url::previous());
				//return $this->redirect(Yii::app()->request->urlReferrer);
				//return Url::previous();
				//$this->redirect(Url::previous());
				//$model=new Notasalumnoasignatura;
				//$modelmat->unsetAttributes(array('idsemestre'));
				//Yii::app()->user->setFlash('success',$model->CIInfPer);
				//Yii::app()->request->redirect(Yii::app()->user->returnUrl);
			}

		}

		return $this->render('create', [
			'model' => $model,
			'modelmatricula' => $modelmatricula,
		]);
		
	}

    
	/**
     * Updates an existing Notasalumnoasignatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
	public function actionCancel()
    {
	return $this->redirect(Url::previous());
    }

    public function actionUpdate($id)
    {
	//return $this->goHome();	
	
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;

        if ($model->load(Yii::$app->request->post()) && ($usuario->idperfil == 'diracad')) {
			$model->save();
            return $this->redirect(['index', 'NotasalumnoasignaturaSearch[CIInfPer]'=>$model->CIInfPer]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
	
    }

    /**
     * Deletes an existing Notasalumnoasignatura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;
		//return $this->goHome();	
		if ($usuario->idperfil == 'diracad')
		    	$this->findModel($id)->delete();
		return $this->redirect(\Yii::$app->request->getReferrer());
        //return $this->redirect(['index']);
	
    }

	public function actionHistorico()
    {
		#$this->layout = "/column3";
		$params = Yii::$app->request->queryParams;
        $searchModel = new NotasalumnoasignaturaSearch();
        $dataProvider = $searchModel->search($params);
		
	// app\widgets\Mallas::widget(['carrera' => $carrera,'cedula' => $cedula ]))
	
        return $this->render('historico', [
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

		$searchModel = new NotasalumnoasignaturaSearch();
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->searchaprobados(Yii::$app->request->queryParams);
		#echo var_dump($dataProvider->getModels()); exit;
		return $this->render('veraprobados', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }

	public function actionSnna() {
		#$usuario = Yii::$app->user->identity;
		#if ($usuario) {
		$periodo = Periodolectivo::find()
						->where(['StatusPerLec'=> 1])
						->one();
			
			$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			$periodos = ArrayHelper::map(Periodolectivo::find()
															->orderBy(['idper'=>SORT_DESC])
															->limit(12)
															->all(), 'idper', 'DescPerLec');
			$this->view->params['carreras'] = $carreras;
			$this->view->params['periodos'] = $periodos;
		#}
		#else{
		#	$this->view->params['carreras'] = [];
		#	$this->view->params['periodos'] = [];

		#}
		#$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		//$notas_detalle = $this->getNotas(23718);
		//echo var_dump($notas_detalle); exit;
		//$this->layout = "/cupos";
		//$this->getPublicar();

		$searchModel = new NotasalumnoasignaturaSearch();
		if ($periodo) {
			$searchModel->idPer = $periodo->idper;
		}
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->searchsnna(Yii::$app->request->queryParams);
		
		#echo var_dump($periodo->idper); exit;
		return $this->render('versnna', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }

	public function actionVermejores() {
		$usuario = Yii::$app->user->identity;
		if ($usuario) {
			
			$carreras_user = explode("'", $usuario->idcarr);
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


			#$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
			#						->orderBy(['nombcarr'=>SORT_ASC])
			#						->all(), 'idCarr', 'NombCarr');
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

		$searchModel = new NotasalumnoasignaturaSearch();
		//$searchModel->idfactura = $idfactura;
		$dataProvider = $searchModel->searchmejores(Yii::$app->request->queryParams);
		#echo var_dump($dataProvider->getModels()); exit;
		return $this->render('vermejores', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
    }

    /**
     * Finds the Notasalumnoasignatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Notasalumnoasignatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notasalumnoasignatura::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	
    public function actionCreapdf($cedula, $idCarr){
 
	$alumno = Informacionpersonal::find()
			->where(['CIInfPer' => $cedula])
			->one();
	$nombre = '';
	if (!empty($alumno)) $nombre = $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer. ' ' . $alumno->NombInfPer;

	$nomcarr = Carrera::find()
			->where(['idcarr' => $idCarr])
			->one();
	$carrera = '';
	$facultad = '';
	$nomfacultad = '';
	if (!empty($nomcarr)) {$carrera = $nomcarr->NombCarr; $facultad = $nomcarr->idfacultad; $nomfacultad = $nomcarr->getNombreFacultad();}

	$user = Yii::$app->user->identity;
	//echo var_dump($user); exit;
	$usuario = ($user)?$user->NombUsu:'';
	$titulo = ($user)?$user->titulo:'';
	$perfil = ($user)?$user->idperfil:'';
	
	
	
	$img_file = K_PATH_IMAGES.'sello_Ecuador.png';
	$pdf = new tcpdf();
		 
	//echo var_dump(K_PATH_IMAGES);
	//exit;

	// set document information
	$pdf->SetCreator(PDF_CREATOR);  
	$pdf->SetAuthor('gvp');
	$pdf->SetTitle("Culminación");                
	$pdf->SetHeaderData('sello_Ecuador.png',PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS", "" .
			 "\n" . "Esmeraldas-Ecuador", PDF_HEADER_LOGO);
	$pdf->setFooterData(array(0,64,0), array(0,64,128));

	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->SetFont('helvetica', '', 9);
	$pdf->SetTextColor(0,0,0);
	
		//$path = K_PATH_IMAGES . "images/logo.jpg";

		//$pdf->Image($path, 0, 0, 210, 297, '', '', '', false, 0, '', false, false, 0);		
		//$pdf->setBarcode('matricula', 'C128B');
	

		// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set a barcode on the page footer
		//$pdf->setBarcode(date('Y-m-d H:i:s'));


	$pdf->AddPage();
		//$img = $pdf->barras(); 
		//Write the html

		// $alumno = Informacionpersonal::model()->find(array('condition'=>"CIInfPer=$id"));


	$fecha = date('d-m-Y');
	$html = '<br/>'.'<br/>'.'<br/>'.'<div style="text-align:center"><h1>CERTIFICADO DE CULMINACIÓN</h1><br/><br/> </div>' .
			'<div style="margin-bottom:15px;">'.
			'<p>Esmeraldas, '. $fecha . '</p>' . 
			'<p>La Universidad Técnica Luis Vargas Torres de Esmeraldas Certifica:</p>' .
			
			 '<p>Que el Alumno(a):<br/></p>' . '<br/>'. '<br/>'.
				'<p>CÉDULA: ........................... '.$alumno->CIInfPer . '</p>'.'<br/>' .
				'<p>APELLIDO PATERNO: ...... '. $alumno->ApellInfPer . '</p>'. '<br/>' .
				'<p>APELLIDO MATERNO: ..... '. $alumno->ApellMatInfPer .'</p>'.'<br/>' .
				'<p>NOMBRES: ....................... '. $alumno->NombInfPer .'</p>'.'<br/>'.
				'<p>FACULTAD: ....................... '.$nomfacultad .'<br/><br/></p>'.

			'<p>Ha culminado con todo el plan de estudios de la carrera de:</p><br/><p style="font-weight: bold;">' . '"' . $carrera . '"'. '</p><br/>' .
			'</div>';
			

		//Convert the Html to a pdf document
	$pdf->writeHTML($html, true, false, true, false, '');


	$html1 = '<div style="text-align:center;"><p>'. $titulo . '  ' . $usuario .'</p><p>' . $carrera. '</p></div>';
	$pdf->setY(200);
	$pdf->writeHTML($html1, true, false, true, false, '');
	 	
	$header = array('Carrera', 'Semestre', 'Código Asg.', 'Asignatura', 'Período', 'Nota Final', 'Asistencia', 'Observación'); 
	 	// $pdf->SetXY(110, 200);

		
		//$pdf->SetAutoPageBreak(false, 0);
	$img_file = K_PATH_IMAGES.'logo.jpg';
	$pdf->setY(250);
	$pdf->write1DBarcode($alumno->CIInfPer, 'C39', '', '', '', 10, 0.2, '', 'N');
	$pdf->Cell(0, 0, $alumno->CIInfPer, 0, 1);
		//$pdf->setBarcode(date('Y-m-d H:i:s'));
	$pdf->Image($img_file, 165, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
		
	$pdf->lastPage();

	$pdf->Output('I');
//		Yii::app()->end();
 
    	//}


    }
	

// ************************************************************************************************************************************

	public function actionNotaspdf($cedula, $idCarr){


		//Order::find()->joinWith(['books b'], true, 'INNER JOIN')->where(['b.category' => 'Science fiction'])->all();
		#$mallaestudiante = MallaEstudiante::find()
		#		->where(['cedula' => $cedula, 'carrera' => $idCarr])
		#		->one();
		#$malla = '';
		$mallaestudiante = Ingreso::find()
				->where(['CIInfPer' => $cedula, 'idcarr' => $idCarr])
				->orderBy(['fecha'=>SORT_DESC])
				->one();
		$idmalla = $mallaestudiante?$mallaestudiante->idmalla:'';
		$malla = $mallaestudiante?$mallaestudiante->malla0->detalle:'';
		//echo var_dump($malla->anio_habilitacion); exit;
		#if (!empty($mallaestudiante)) $malla = $mallaestudiante->anio_habilitacion;
		
		//$mallacurricular = MallaCurricular::find()
		//		->where(['idCarr' => $idCarr, 'anio_habilitacion' => $malla])
		//		->all();

	
		$query = new Query;
		// compose the query
		$query->select('carrera.NombCarr, matricula.idsemestre, asignatura.NombAsig, periodolectivo.DescPerLec, 
				notasalumnoasignatura.CalifFinal, notasalumnoasignatura.observacion, detalle_matricula.nivel,
				notasalumnoasignatura.asistencia, notasalumnoasignatura.idAsig, notasalumnoasignatura.aprobada')
			->from('notasalumnoasignatura')
			->join('LEFT JOIN', 'ingreso', 'ingreso.CIInfPer = notasalumnoasignatura.CIInfPer')
			->join('LEFT JOIN', 'matricula', 'matricula.idMatricula = notasalumnoasignatura.idMatricula')
			->join('LEFT JOIN', 'detalle_matricula', 'detalle_matricula.id = notasalumnoasignatura.iddetalle')
			->join('LEFT JOIN', 'carrera', 'carrera.idCarr = ingreso.idcarr')
			->join('LEFT JOIN', 'asignatura', 'asignatura.IdAsig = notasalumnoasignatura.IdAsig')
			->join('LEFT JOIN', 'periodolectivo', 'periodolectivo.idper = notasalumnoasignatura.idper')
			//->join('right JOIN', 'malla_curricular', 'malla_curricular.idAsig = notasalumnoasignatura.idAsig')
			->where(['notasalumnoasignatura.CIInfPer' => $cedula,
					'notasalumnoasignatura.aprobada' => 1, 
					'carrera.idCarr' => $idCarr,
					//'malla_curricular.anio_habilitacion' => $malla,
			])
			->orderBy([
				'carrera.idCarr' => SORT_ASC,
				'detalle_matricula.nivel' => SORT_ASC,
				'matricula.idsemestre' => SORT_ASC,
				'asignatura.NombAsig' => SORT_ASC,
				'ingreso.fecha' => SORT_DESC,
			])
			->groupBy('notasalumnoasignatura.idAsig');

		// build and execute the query
		$rows = $query->all();
		//echo var_dump($rows);
		//exit;

		
		//**************************************************

 
		$alumno = Informacionpersonal::find()
				->where(['CIInfPer' => $cedula])
				->one();
		$nombre = '';
		if (!empty($alumno)) $nombre = $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer. ' ' . $alumno->NombInfPer;

		$nomcarr = Carrera::find()
				->where(['idcarr' => $idCarr])
				->one();
		$carrera = '';
		$facultad = '';
		$nomfacultad = '';
		if (!empty($nomcarr)) {$carrera = $nomcarr->NombCarr; $facultad = $nomcarr->idfacultad; 
			$nomfacultad = $nomcarr->getNombreFacultad();}
	
	
		//$img_file = K_PATH_IMAGES.'sello_Ecuador.png';
		//$pdf = new tcpdf();
		$pdf = new MYPDF();	 
		//echo var_dump(K_PATH_IMAGES);
		//exit;
		$img_file = K_PATH_IMAGES.'logo.jpg';

		// set document information
		$pdf->SetCreator(PDF_CREATOR);  
		$pdf->SetAuthor('gvp');
		$pdf->SetTitle("Culminación");                
		//$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS"., "" .
		//		 "\n" . "Esmeraldas-Ecuador");
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetTextColor(0,0,0);
	
			// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();

	//**********************************************************************************************************
		$fecha = date('d-m-Y');
		$html = "<div style='margin-bottom:15px;'> <br><address>
			  Esmeraldas, $fecha <br>
			  Cédula: $alumno->CIInfPer<br>
			  Alumno: $alumno->ApellInfPer $alumno->ApellMatInfPer  $alumno->NombInfPer <br> 
			Carrera: $carrera.......................: $malla<br>
			</address>  
			</div>";
		//Convert the Html to a pdf document
		$pdf->writeHTML($html, true, false, true, false, '');
	 
		$header = array('Carrera', 'Semestre', 'Asignatura', 'Período', 'Nota Final', 'Asistencia', 'Observación'); 
	 
		// print colored table
		$this->ColoredTable($pdf,$header, $rows);
		$pdf->setY(263);
		//if(isset($matricula)){
		#$pdf->write1DBarcode($alumno->CIInfPer, 'C39', '', '', '', 5, 0.2, '', 'N');
		$pdf->Cell(0, 0, $alumno->CIInfPer, 0, 1);
		

		// reset pointer to the last page
		$pdf->lastPage();
		$file = $alumno->CIInfPer . '.' . 'pdf';
		//Close and output PDF document
		$pdf->Output($file, 'I');

    }

//******************************************************************************************************************************
	public function actionMallapdf($cedula, $idCarr){


		//Order::find()->joinWith(['books b'], true, 'INNER JOIN')->where(['b.category' => 'Science fiction'])->all();
		$mallaestudiante = Ingreso::find()
				->where(['CIInfPer' => $cedula, 'idcarr' => $idCarr])
				->orderBy(['fecha'=>SORT_DESC])
				->one();
		$idmalla = $mallaestudiante?$mallaestudiante->idmalla:'';
		$malla = $mallaestudiante->malla0?$mallaestudiante->malla0->detalle:'';
		//echo var_dump($malla->anio_habilitacion); exit;
		#if (!empty($mallaestudiante)) $malla = $mallaestudiante->anio_habilitacion;

		/*
		$mallas = MallaCurricular::find()
				->where("anio_habilitacion = $malla and idCarr = $idCarr and imp = 1")
				->orderBy(['idSemestre'=>SORT_ASC])
				->all();
		*/

	
		$query = new Query;
		// compose the query
		$query->select('idasignatura, nivel, asignatura.NombAsig')
			->from('detalle_malla')
			->join('INNER JOIN', 'asignatura', 'asignatura.IdAsig = detalle_malla.idasignatura')
			->where(['idmalla' => $idmalla,
					'estado' => 1
					//'malla_curricular.anio_habilitacion' => $malla,
			])
			->orderBy([
				'nivel' => SORT_ASC,
				'asignatura.NombAsig' => SORT_ASC,
			]);
			//->groupBy('notasalumnoasignatura.idAsig');

		// build and execute the query
		$rows = $query->all();
		//echo var_dump($rows);
		//exit;
		
 
		$alumno = Informacionpersonal::find()
				->where(['CIInfPer' => $cedula])
				->one();
		$nombre = '';
		if (!empty($alumno)) $nombre = $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer. ' ' . $alumno->NombInfPer;

		$nomcarr = Carrera::find()
				->where(['idcarr' => $idCarr])
				->one();
		$carrera = '';
		$facultad = '';
		$nomfacultad = '';
		if (!empty($nomcarr)) {$carrera = $nomcarr->NombCarr; $facultad = $nomcarr->idfacultad; 
			$nomfacultad = $nomcarr->getNombreFacultad();}
	
	
		//$img_file = K_PATH_IMAGES.'sello_Ecuador.png';
		//$pdf = new tcpdf();
		$pdf = new MYPDF();	 
		//echo var_dump(K_PATH_IMAGES);
		//exit;
		$img_file = K_PATH_IMAGES.'logo.jpg';

		// set document information
		$pdf->SetCreator(PDF_CREATOR);  
		$pdf->SetAuthor('gvp');
		$pdf->SetTitle("Culminación");                
		//$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS"., "" .
		//		 "\n" . "Esmeraldas-Ecuador");
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetMargins(20, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetTextColor(0,0,0);
	
			// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();

		//*****************************************
		$fecha = date('d-m-Y');
		$html = "<div style='margin-bottom:15px;'> <br><address>
			  Esmeraldas, $fecha <br>
			  Cédula: $alumno->CIInfPer<br>
			  Alumno: $alumno->ApellInfPer $alumno->ApellMatInfPer  $alumno->NombInfPer <br> 
			Carrera: $carrera.......................: $malla<br>
			</address> 
			</div>";
		//Convert the Html to a pdf document
		$pdf->writeHTML($html, true, false, true, false, '');
	 
		$header = array('Semestre', 'Asignatura', 'Período', 'Nota Final', 'Asistencia', 'Estado'); 
	 
		// print colored table
		$this->MallaTabla($pdf,$header, $rows, $cedula);
		$pdf->setY(263);
		//if(isset($matricula)){
		#$pdf->write1DBarcode($alumno->CIInfPer, 'C39', '', '', '', 5, 0.2, '', 'N');
		$pdf->Cell(0, 0, $alumno->CIInfPer, 0, 1);
		

		// reset pointer to the last page
		$pdf->lastPage();
		$file = $alumno->CIInfPer . '.' . 'pdf';
		//Close and output PDF document
		$pdf->Output($file, 'I');

    }
	


// ************************************************************************************************************************************


	// Colored table
    public function ColoredTable($pdf,$header,$data) {
        // Colors, line width and bold font
        $pdf->SetFillColor(120, 185, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(120, 185, 120);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B', '7');
        // Header
        $w = array(50, 12, 65, 15, 14,14, 15);
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
	
        foreach($data as $row) {
		
		//echo var_dump($row['NombCarr']);
		//exit;
		$observacion = '';
		if ($row['aprobada'] == 1)
			$observacion = 'APROBADA';
		else if  ($row['aprobada'] == 0)
			$observacion = 'REPROBADA';
		if ($row['nivel'] !== NULL)
			$nivel = $row['nivel'];
		else if  ($row['idsemestre'] !== NULL)
			$nivel = $row['idsemestre'];
		else
			$nivel ='';
		$pdf->Cell($w[0], 6, $row['NombCarr'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[1], 6, number_format($nivel), 'LR', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, $row['NombAsig'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[3], 6, $row['DescPerLec'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, $row['CalifFinal'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[5], 6, $row['asistencia'].'%', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[6], 6, $observacion, 'LR', 0, 'L', $fill);
		$pdf->Ln();
		$fill=!$fill;
        }
	
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }



	 public function MallaTabla($pdf,$header,$data, $cedula) {
		// Colors, line width and bold font
		$pdf->SetFillColor(120, 185, 120);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(120, 185, 120);
		$pdf->SetLineWidth(0.3);
		$pdf->SetFont('', 'B', '7');
		// Header
		$w = array(15, 90, 15, 15, 15, 25);
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

		//echo var_dump($data);
		//	exit;
		$notas_alumno = $this->getNotasaprobadas($cedula);
		//$notas_equivalencia = $this->getNotasequivalencia($idAsig);
		$id_notas = ArrayHelper::getColumn($notas_alumno, 'idAsig');
		$contador = 0;
		$promedio = 0.0;
		$suma = 0.0;
		// recorre malla curricular
		//total_malla = count($notas_alumno);
		foreach($data as $row) {
			//$notas = null;
			//$clave = array_search($row['idAsig'], $id_notas);
			
			$calif = '';
			$asist = '';
			$observacion = '';
			$periodo = '';
			foreach($notas_alumno as $nota)
			{
				if ( $nota->idAsig === $row['idasignatura'] ){
					$calif = $nota->CalifFinal;
					$asist = $nota->asistencia;
					$periodo = $nota->periodo0->DescPerLec;
					$observacion = 'APROBADA';
					//unset($notas_alumno[$nota->idnaa]);
					break;	
				}
				
			}
			
			/*if(!empty($clave)) {
				$notas = $notas_alumno[$clave];
				ArrayHelper::remove($id_notas, $clave);
			}*/

			if ($calif == '') {
				$equivalencia = Equivalencia::find()
						->where(['asignatura' => $row['idasignatura']])
						->andWhere(['equivalencia' => $id_notas])
						->one();
					
					if (!empty($equivalencia)) {
						
						//$clave = array_search($equivalencia->equivalencia, $id_notas);

						foreach($notas_alumno as $nota)
						{
							if ( $nota->idAsig === $equivalencia->equivalencia ){
								$calif = $nota->CalifFinal;
								$asist = $nota->asistencia;
								$periodo = $nota->periodo0->DescPerLec;
								$observacion = 'APROBADA';
								//unset($notas_alumno[$nota->idnaa]);
								break;	
							}
				
						}

						/*if(isset($clave)) {
								$notas = $notas_alumno[$clave];
								ArrayHelper::remove($id_notas, $clave);
								
						}*/
					}
			
			}
			
		
			//$nota_asignatura = (!empty($notas))?$notas->CalifFinal:$calif;
			//$asistencia = (!empty($notas))?$notas->asistencia:'';
			//$obs = '';
			//$estado = (!empty($notas))?$notas->aprobada:0;
			//if ($estado == 1) {
			//	$obs = 'APROBADA';
			if ($calif > 0) {
				$contador ++;
				$suma = $suma + $calif; //nota_asignatura;
				
			}
			//}
							
			$pdf->Cell($w[0], 6, number_format($row['nivel']), 'LR', 0, 'C', $fill);
			//$pdf->Cell($w[1], 6, $row['idAsig'], 'LR', 0, 'L', $fill);
			$pdf->Cell($w[1], 6, $row['NombAsig'], 'LR', 0, 'L', $fill);
			$pdf->Cell($w[2], 6, $periodo, 'LR', 0, 'C', $fill);
			$pdf->Cell($w[3], 6, $calif, 'LR', 0, 'C', $fill);
			$pdf->Cell($w[4], 6, $asist, 'LR', 0, 'C', $fill);		
			$pdf->Cell($w[5], 6, $observacion, 'LR', 0, 'C', $fill);
			//$pdf->Cell($w[5], 6, $row['idAsig'], 'LR', 0, 'L', $fill);
			//$pdf->Cell($w[4], 6, $row['CalifFinal'], 'LR', 0, 'C', $fill);
		
			$pdf->Ln();
			$fill=!$fill;
			
		}
	
		$promedio = round($suma/$contador, 1);
		//echo var_dump($contador, '-', $suma);
		//	exit;

		$pdf->Ln();
		$fill=!$fill;	
		$pdf->Cell($w[0], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[1], 6, 'PROMEDIO:', 'C', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, $promedio, 'LR', 0, 'C', $fill);
		$pdf->Cell($w[3], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, '', 'LR', 0, 'C', $fill);
	
		$pdf->Ln();
			$fill=!$fill;
		$pdf->Cell(array_sum($w), 0, '', 'T');
	}

	public function getNotas($cedula)
	{
				
		$notas = Notasalumnoasignatura::find()
			->where(['CIInfPer' => $cedula])
			->orderBy([
				'asignatura.NombAsig' => SORT_ASC,
			])
			->all();

		return $notas;
	}

	public function getNotasaprobadas($cedula)
	{
				
		$notas = Notasalumnoasignatura::find()
			->where(['CIInfPer' => $cedula, 'aprobada' => 1])
			->all();

		return $notas;
	}

	public function getNotasequivalencia($idAsig)
	{
		$equivalencias = Equivalencia::find()
					->where(['asignatura' => $idAsig])
					//->andWhere(['equivalencia' => $id_notas])
					->all();
		
		return $equivalencias;
	}

	public function actionListamalla($idcarr)	{
		#$countMallas = MallaCarrera::find()
         #       	->where(['idcarrera' => $idcarr])
          #      	->count();
 
		$mallas = MallaCarrera::find()
        		->where(['idcarrera' => $idcarr])
			#->groupBy('anio_habilitacion')
			->orderBy('detalle DESC')
        		->all();

		//echo var_dumps($posts); exit;
 
		if($mallas){
			echo "<option>-</option>";
			foreach($mallas as $malla){
				
				echo "<option value='".$malla->id."'>".$malla->detalle."</option>";
			}
		}
		else{
			echo "<option>-</option>";
		}
 
	}


	public function actionListavacia()	{
		echo "<option>-</option>";
		$niveles = array('1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
		foreach($niveles as $nivel){
				
			echo "<option value='".$nivel."'>".$nivel."</option>";
		}
 
	}

	public function actionListasignaturas($nivel)	{

		$porciones = explode(";", $nivel);
		if ($porciones[0]) $idnivel = $porciones[0];
		if ($porciones[1]) $idMc = $porciones[1];

		#$malla = MallaCarrera::find()
         #       	->where(['id' => $idMc])
          #      	->one();

		#if ($malla) {
 
			$asignmallas = DetalleMalla::find()
				->where(['idmalla' => $idMc, 'nivel' => $idnivel])
				->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion', 'idsemestre'])
				->orderBy('asignatura.NombAsig ASC')
				->all();
			if ($asignmallas){
				echo "<option>-</option>";
				foreach($asignmallas as $asignaturamalla){
					$nombreasig = Asignatura::find()
							->where(['idAsig' => $asignaturamalla->idasignatura])
							->one();
					$asignatura = $nombreasig?$nombreasig->NombAsig:'';


					echo "<option value='".$asignaturamalla->idasignatura."'>"
						.$asignaturamalla->idasignatura.'-'.$asignatura."</option>";
				}
			}
		#}
		//echo var_dump($idnivel); exit;
 
		
		else{
			echo "<option>-</option>";
		}
 
	}

	public function enviarMail($email, $texto)
	{
		$emaildamarys = 'damarys.garcia@utelvt.edu.ec';
		$emailtics = 'tics@utelvt.edu.ec';
		$emailacademico = 'viceacademico@utelvt.edu.ec';

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
				->setTo($emaildamarys)
				->setCc($emailmarco)
				->setSubject('Eliminación de nota')
				->setTextBody($texto)
				->send();
			
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
	$this->Cell(0, 20, "Esmeraldas-Ecuador                                                                 . ", 0, false, 'R', 0, '', 0);
	
	$imager_file = K_PATH_IMAGES.'logo.jpg';
	$imagel_file = K_PATH_IMAGES.'sello_Ecuador.png';
	$this->Image($imagel_file, 15, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
	$this->Image($imager_file, 175, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
    }

    // Page footer
	/*
    public function Footer() {
	// Position at 15 mm from bottom
	$this->SetY(-15);
	// Set font
	$this->SetFont('helvetica', 'I', 8);
	// Page number
	$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	*/
}


