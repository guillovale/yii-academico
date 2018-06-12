<?php

namespace app\controllers;

use Yii;
use app\models\NotasDetalle;
use app\models\NotasDetalleSearch;
use app\models\Informacionpersonal;
use app\models\InformacionpersonalD;
use app\models\Notasalumnoasignatura;
use app\models\CursoOfertado;
use app\models\Periodolectivo;
use app\models\DetalleMatricula;
use app\models\Configuracion;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;

/**
 * NotasdetalleController implements the CRUD actions for NotasDetalle model.
 */
class NotasdetalleController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'enviarMail', 'view', 'index', 'publicar'],
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
     * Lists all NotasDetalle models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new NotasDetalleSearch();
		$searchModel->iddetallematricula = $id;       
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		if ($searchModel && $searchModel->detallematricula ) {
			$this->view->params['idfactura'] = $searchModel->detallematricula->idfactura; 
			$this->view->params['idasig'] = $searchModel->detallematricula->curso?
							$searchModel->detallematricula->curso->detallemalla->idasignatura:0;
			$this->view->params['asignatura'] = $searchModel->detallematricula->curso?
							$searchModel->detallematricula->curso->detallemalla->asignatura->NombAsig:'';
			$this->view->params['cedula'] = $searchModel->detallematricula->factura->cedula;
			$this->view->params['alumno'] = $searchModel->detallematricula->factura->getNombreAlumno();
			$this->view->params['idper'] = $searchModel->detallematricula->factura->idper;
		    return $this->render('index', [
		        'searchModel' => $searchModel,
		        'dataProvider' => $dataProvider,
		    ]);
		}
		return $this->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Displays a single NotasDetalle model.
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
     * Creates a new NotasDetalle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$identidad = Yii::$app->user->identity?Yii::$app->user->identity:'';
		if ($identidad) {
			if ($identidad->crearnota == 1) {
				/*
				$modelLibreta = new LibretaCalificacion();
				$modelNota = new NotasDetalle();
				if ($model->load(Yii::$app->request->post()) && $model->save()) {
		        	return $this->redirect(['view', 'id' => $model->idnota]);
		    	} else {
		        	return $this->render('_formcrearnota', [
		      	      'model' => $model,
		        	]);
				}*/
			}
		}

		return $this->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Updates an existing NotasDetalle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $idfactura, $asignatura, $idasig, $hemi, $comp, $cedula, $alumno)
    {
		$this->view->params['idfactura'] = $idfactura;
		$this->view->params['idasig'] = $idasig;
		$this->view->params['asignatura'] = $asignatura;
		$this->view->params['hemi'] = $hemi;
		$this->view->params['comp'] = $comp;
		$this->view->params['cedula'] = $cedula;
		$this->view->params['alumno'] = $alumno;
		$model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;

		if ($model && ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') ) {
			$model->usuario = $usuario->LoginUsu;
			$model->fecha = date('Y-m-d');
			$model->peso = round($model->libreta->componente0->parametro->escala /100, 3);
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$cedulad = $model->libreta->iddocente;
				#$this->publicarnota($model->detallematricula->curso->id);
				//$idmatricula =  Yii::$app->runAction('detallematricula/goods', ['model_id' => $goods->id]);
				//echo var_dump($cedulad); exit;
				
				$texto = 'De acuerdo a lo solicitado,  
							Vicerrectorado Académico ha procedido con la actualización de : '. 
							'Cédula: '. $cedula . ' Asignatura: ' .$asignatura . ' hemisemestre: '. 
							$hemi. ' componente: '.	$comp. ' nota: '.$model->nota;
				try {
					$this->enviarMail($cedulad, $cedula, $texto);
				}catch (Exception $e) {
					echo 'Excepción capturada: ',  $e->getMessage(), "\n";
				}
				
				return $this->redirect(['index', 'id' => $model->iddetallematricula]);
			} else {
				return $this->render('update', [
				    'model' => $model,
				]);
			}
		}
		return $this->redirect(Yii::$app->request->getReferrer());
    }

    /**
     * Deletes an existing NotasDetalle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$usuario = Yii::$app->user->identity;

		if ( $usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' ) {
        	$this->findModel($id)->delete();
		}
		return $this->redirect(Yii::$app->request->getReferrer());
       # return $this->redirect(['index']);
    }



	//enviar mail
	public function enviarMail($cedulad, $cedula, $texto)
	{
		$emailtis = 'tics@utelvt.edu.ec';
		$emailacademico = 'viceacademico@utelvt.edu.ec';
		$emailacademico1 = 'vicedama@utelvt.edu.ec';
		$emaildamarys = 'damarys.garcia@utelvt.edu.ec';
		$emailmarco = 'marco.parreno@utelvt.edu.ec';
		$emaildocente = 'tics@utelvt.edu.ec';
		$emailalumno = 'tics@utelvt.edu.ec';

		$docente = InformacionpersonalD::find()
												->where(['CIInfPer'=> $cedulad])
												->one();
		$alumno = Informacionpersonal::find()
												->where(['CIInfPer'=> $cedula])
												->one();
		$emaildocente = (count($docente))?
					($docente->mailInst !== null?$docente->mailInst:'tics@utelvt.edu.ec'):'tics@utelvt.edu.ec';
		$emailalumno = count($alumno)?
					($alumno->mailPer !== null?$alumno->mailPer:'tics@utelvt.edu.ec'):'tics@utelvt.edu.ec';
		
		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emaildocente)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailalumno)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();

	
		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailtis)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailacademico)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailacademico1)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();


		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emaildamarys)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();


	}
	

	public function actionPublicar($idcurso)
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
			#echo var_dump($hoy, $periodo->finiciohemi2); exit;
			#if ($hoy >= $periodo->examsupletorio_ini && $hoy <= $periodo->examsupletorio_fin)
			if ($hoy >= $periodo->finiciohemi2 && $hoy <= $periodo->ffinhemi2)
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

	public function actionPublicarnota($idcurso)
    {
		
		$cursomodel = $this->findCurso($idcurso);
		$idcursomodel = $cursomodel?$cursomodel->id:0;
		$idper = $cursomodel?$cursomodel->idper:'';
		$periodo = $cursomodel?$cursomodel->periodo->DescPerLec:'';
		$asignatura = $cursomodel?$cursomodel->detallemalla->asignatura->NombAsig:'';
		$idasignatura = $cursomodel?$cursomodel->detallemalla->idasignatura:'';
		$docente = $cursomodel?$cursomodel->getNombreDocente():'';
		$iddocente = $cursomodel?$cursomodel->iddocente:'';
		$carrera = $cursomodel?$cursomodel->detallemalla->malla->carrera->NombCarr:'';
		$nivel = $cursomodel?$cursomodel->detallemalla->nivel:'';
		$paralelo = $cursomodel?$cursomodel->paralelo:'';

		$matriculas = DetalleMatricula::find()
				#->join('factura')
				#->join('INNER JOIN', 'factura', 'factura.id = detalle_matricula.idfactura')
				#->join('idCarr0')
				#->where(['estado'=> 1, 'idper'=> 109, 'idcarr'=>'056'])
				->where(['idcurso'=>$idcursomodel, 'estado'=> 1])
				->all();
		#$ids = explode(';', $idmatriculas);
		#$publicado = Docenteperasig::find()
		#							->where(['dpa_id'=> $iddpa])->one();
		#$start_time = microtime(true); 
		if ($matriculas) {
			$nota = [];
			$usuario = Yii::$app->user->identity;
			if ( $usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad' ) {
				foreach($matriculas as $matricula) {
				
					$nota = $this->getNotas($matricula->id);
					#if ($matriculas->factura->cedula == '0803336411')
					#	echo var_dump($nota); exit;
					if ($nota["aprobada"] == 1 ) {
						$notasalumno = Notasalumnoasignatura::find()
												->where(['iddetalle'=> $matricula->id])
												->one();
						#$alumno = $matricula?$matricula->idFactura0->cedula:'';
					
						if ($notasalumno) {
							#if ($notasalumno->aprobada == 0) {
								#$alumno = $notasalumno?$notasalumno->CIInfPer:'';
								#echo var_dump($matricula->id); exit;
								#$notasalumno->idPer = $idper;
								#$notasalumno->CIInfPer = $alumno;					
								$notasalumno->CalifFinal = $nota["nota"];
								$notasalumno->asistencia = $nota["asistencia"];
								$notasalumno->StatusCalif = 3;
								$notasalumno->observacion = $nota["estado"];
								$notasalumno->aprobada = $nota["aprobada"];
								$notasalumno->save();
							#}
							#if ($notasalumno->iddetalle == 74252) {
							#	echo var_dump($notasalumno->getErrors(), '-', $nota, '-', $notasalumno); exit;}
							//echo var_dump($notasalumno->getErrors(), $notasalumno->CalifFinal); exit;
						}
						else {
							#echo var_dump($matricula->id); exit;
							$alumno = $matricula?$matricula->factura->cedula:'';
							$modelnota = new Notasalumnoasignatura();
							$modelnota->idPer = $idper;
							$modelnota->CIInfPer = $alumno;
							$modelnota->idAsig = $idasignatura;
							$modelnota->iddetalle = $matricula->id;
							$modelnota->CalifFinal = $nota["nota"];
							$modelnota->asistencia = $nota["asistencia"];
							$modelnota->StatusCalif = 3;
							$modelnota->observacion = $nota["estado"];
							$modelnota->aprobada = $nota["aprobada"];
							$modelnota->VRepite = 1;
							$modelnota->registro = date('Y-m-d H:i:s');
							$modelnota->convalidacion = 0;
								//$modelnota->observacion_efa = 'nota homologada';
							$modelnota->save();
								#echo var_dump($modelnota->errors); exit;
						
						}
					}
				}
				
			}
			#$end_time = microtime(true);			
			#$execution_time = ($end_time - $start_time)/60;			
			#echo var_dump($execution_time); exit;		
		}

		//**********************************************************************************************************
		
		$idsmatricula = ArrayHelper::getColumn($matriculas,'id');
		#echo var_dump($idsmatricula); exit;
		$query = new Query;
		// compose the query
		$query->select('notasalumnoasignatura.CIInfPer, ApellInfPer, ApellMatInfPer,NombInfPer,
				carrera.NombCarr, asignatura.NombAsig, periodolectivo.DescPerLec, 
				notasalumnoasignatura.CalifFinal, notasalumnoasignatura.observacion, 
				notasalumnoasignatura.asistencia, notasalumnoasignatura.idAsig, notasalumnoasignatura.aprobada')
			->from('notasalumnoasignatura')
			->join('INNER JOIN', 'detalle_matricula', 'detalle_matricula.id = notasalumnoasignatura.iddetalle')
			->join('INNER JOIN', 'carrera', 'carrera.idCarr = detalle_matricula.idcarr')
			->join('INNER JOIN', 'asignatura', 'asignatura.IdAsig = notasalumnoasignatura.IdAsig')
			->join('INNER JOIN', 'periodolectivo', 'periodolectivo.idper = notasalumnoasignatura.idper')
			->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = notasalumnoasignatura.CIInfPer')
			//->join('right JOIN', 'malla_curricular', 'malla_curricular.idAsig = notasalumnoasignatura.idAsig')
			->Where(['in', 'detalle_matricula.id', $idsmatricula ])
			->orderBy([
				'ApellInfPer'=>SORT_ASC, 'ApellMatInfPer'=>SORT_ASC, 'NombInfPer'=>SORT_ASC,
				//'idmatriculas.idsemestre' => SORT_ASC,
				//'asignatura.NombAsig' => SORT_ASC,
			]);
			//->groupBy('notasalumnoasignatura.idAsig');


		// build and execute the query
		$rows = $query->all();

		$pdf = new MYPDF();	 
		//echo var_dump($rows, ' ', $ids); exit;
		$img_file = K_PATH_IMAGES.'logo.jpg';

		// set document information
		$pdf->SetCreator(PDF_CREATOR);  
		$pdf->SetAuthor('tics');
		$pdf->SetTitle("Notas publicadas");                
		//$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS"., "" .
		//		 "\n" . "Esmeraldas-Ecuador");
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
		//$asignatura = $row["NombAsig"];
		//$periodo = $row["DescPerLec"];
		//$carrera = $row["NombCarr"];
		//$periodo = $row["DescPerLec"];
		$html = "<div style='margin-bottom:12px;'>
		Esmeraldas, $fecha 	.	.	.	.	.	Período: $periodo
		 <br><address>
			<b>Asignatura: $asignatura  </b><br>
			Docente: $docente <br>
			Carrera: $carrera .	.	.
			Nivel: $nivel.	.	.
			Paralelo: $paralelo <br>
			</address>  
			</div>";
		//Convert the Html to a pdf document
		$pdf->writeHTML($html, true, false, true, false, '');
	 
		$header = array('Cédula', 'Alumno', 'Nota Final', 'Asistencia', 'Observación'); 
	 
		// print colored table
		$this->ColoredTable($pdf,$header, $rows);
		#$pdf->setY(263);
		//if(isset($matricula)){
		$style = array(
			'position' => '',
			'align' => 'C',
			'stretch' => false,
			'fitwidth' => true,
			'cellfitalign' => '',
			'border' => true,
			'hpadding' => 'auto',
			'vpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255),
			'text' => true,
			'font' => 'helvetica',
			'fontsize' => 8,
			'stretchtext' => 4
		);

		// PRINT VARIOUS 1D BARCODES

		// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
		#$pdf->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
		#$pdf->write1DBarcode($iddocente, 'C39', '', '', '', 18, 0.4, null, 'N');
		#$pdf->write1DBarcode($iddocente, 'C39', '', '', '', 5, 0.2, '','N');
		#$pdf->Cell(0, 0, $iddocente, 0, 1);
		

		// reset pointer to the last page
		$pdf->lastPage();
		$file = $iddocente . '_' . $idcursomodel . '.' . 'pdf';
		//Close and output PDF document
		$pdf->Output($file, 'D');
	
		//		$this->actionNotaspdf($matricula->idFactura0->cedula, $matricula->idcarr);

    }


	public function getQuerynotas($idper, $idcurso)
    {	
		$subquery = NotasDetalle::find()
					->select(['factura.cedula,componentescalificacion.idparametro, idlibreta, iddetallematricula, 
						hemisemestre, idfactura, libreta_calificacion.idcomponente, nota, 
						concat(ApellInfPer, " ", ApellMatInfPer, " ", NombInfPer) as estudiante,
						IF(hemisemestre = 1 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA1,
						IF(hemisemestre = 1 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB1,
						IF(hemisemestre = 1 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC1,
						IF(hemisemestre = 1 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM1,
						IF(hemisemestre = 1 && sigla = "T", (cast(sum(nota)*10/count(nota) as UNSIGNED)), "") AS Ast1,
						IF(hemisemestre = 2 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA2,
						IF(hemisemestre = 2 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB2,
						IF(hemisemestre = 2 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC2,
						IF(hemisemestre = 2 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM2,
						IF(hemisemestre = 2 && sigla = "T", (cast(sum(nota)*10/count(nota) as UNSIGNED)), "") AS Ast2,
						IF(hemisemestre = 0 && sigla = "R", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Rec
						'])
					->leftJoin('libreta_calificacion', 'libreta_calificacion.id = notas_detalle.idlibreta')
					->leftJoin('componentescalificacion', 'componentescalificacion.idcomponente = libreta_calificacion.idcomponente')
					->leftJoin('parametroscalificacion', 'parametroscalificacion.idparametro = componentescalificacion.idparametro')
					->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
					->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
					->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
					->where(['libreta_calificacion.idper'=>$idper, 'libreta_calificacion.idcurso' => $idcurso, 
							'detalle_matricula.estado'=> 1])
					->groupBy(['factura.cedula', 'libreta_calificacion.hemisemestre','componentescalificacion.idparametro'])
					->orderBy(['ApellInfPer'=>SORT_ASC, 'ApellMatInfPer'=>SORT_ASC, 'NombInfPer'=>SORT_ASC, 
								'libreta_calificacion.hemisemestre'=>SORT_ASC, 'componentescalificacion.idparametro'=>SORT_ASC]);
	

		$query = new Query();
		$query->select(['*'])
			->addSelect(['sum(c.CompA1) as A1, sum(c.CompB1) as B1, sum(c.CompC1) as C1, sum(c.EXM1) as Ex1, sum(c.Ast1) as As1,
						sum(c.CompA2) as A2, sum(c.CompB2) as B2, sum(c.CompC2) as C2, sum(c.EXM2) as Ex2, sum(c.Ast2) as As2,
						sum(c.Rec) as Suf
						'])
			->from(['c' => $subquery])
			->groupBy(['cedula'])
			->orderBy(['estudiante'=>SORT_ASC]);
		
		return $query;
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
					->select(['factura.cedula, idlibreta, iddetallematricula, 
						hemisemestre, idfactura, libreta_calificacion.idcomponente, nota, 
						
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
					->leftJoin('componentescalificacion', 'componentescalificacion.idcomponente = libreta_calificacion.idcomponente')
					->leftJoin('parametroscalificacion', 'parametroscalificacion.idparametro = componentescalificacion.idparametro')
					->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
					->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
					#->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
					->where(['iddetallematricula'=>$idmatricula])
					->groupBy(['libreta_calificacion.hemisemestre','parametroscalificacion.sigla'])
					->orderBy(['libreta_calificacion.hemisemestre'=>SORT_ASC, 'parametroscalificacion.idparametro'=>SORT_ASC]);
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
			$nota1 = ( ($row["A1"]*$compA + $row["B1"]*$compB + $row["C1"]*$compC)*$compT + $row["Ex1"]*$compEx );
			$nota2 = ( ($row["A2"]*$compA + $row["B2"]*$compB + $row["C2"]*$compC)*$compT + $row["Ex2"]*$compEx );
			$asis1 = ($row["As1"] >=0 && $row["As1"] <=10)?$row["As1"]:$row["As1"];
			$asis2 = ($row["As2"] >=0 && $row["As2"] <=10)?$row["As2"]:$row["As2"];
			$promedionota = round((round($nota1) + round($nota2))/2, 2);
			$promedioasistencia = round((round($asis1) + round($asis2))/2, 2);
			$recp = round($row["Suf"]?$row["Suf"]:0);
			if ( $promedionota >= 7 && ($promedioasistencia >= 7 && $promedioasistencia <= 10) ) {
				$estado = 'APROBADA';
				$aprobada = 1;	
			}
			elseif ( $promedionota >= 5 && $promedionota < 7 && ($promedioasistencia >= 7 && $promedioasistencia <= 10) )
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
			
			$notas = ['nota'=> $promedionota, 'asistencia'=> $promedioasistencia*10, 
					'estado'=> $estado, 'aprobada'=> $aprobada];
		}
		#if ($idmatricula == 74252) {
		#	echo var_dump($notas, '-', $idmatricula); exit;}
		return ($notas);
 
    }

	// Colored table
    public function ColoredTable($pdf,$header,$data) {
        // Colors, line width and bold font
        $pdf->SetFillColor(120, 185, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(120, 185, 120);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B', '7');
        // Header
        $w = array(20, 70, 15, 20, 50);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
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
		$pdf->Cell($w[0], 6, $row['CIInfPer'], 'LR', 0, 'L', $fill);
		//$pdf->Cell($w[1], 6, number_format($row['idsemestre']), 'LR', 0, 'C', $fill);
		$pdf->Cell($w[1], 6, $row['ApellInfPer'].' '.$row['ApellMatInfPer'].' '.$row['NombInfPer'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[2], 6, $row['CalifFinal'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[3], 6, $row['asistencia'].'%', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, $row['observacion'], 'LR', 0, 'C', $fill);
		//$pdf->Cell($w[6], 6, $observacion, 'LR', 0, 'L', $fill);
		$pdf->Ln();
		$fill=!$fill;
        }
	
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }

    /**
     * Finds the NotasDetalle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NotasDetalle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NotasDetalle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	protected function findCurso($idcurso)
    {
        if (($model = CursoOfertado::findOne($idcurso)) !== null) {
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
	$this->Cell(0, 20, "Vicerectorado Académico                                                             . ", 0, false, 'R', 0, '', 0);
	$this->Cell(0, 30, "Esmeraldas Ecuador                                                                 . ", 0, false, 'R', 0, '', 0);
	
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
	#echo var_dump($this); exit;
	#$this->write1DBarcode($this->iddocente, 'C39', '', '', '', 5, 0.2, '', 'N');
		#$pdf->Cell(0, 0, $idcursomodel, 0, 1);
	//$pdf->write1DBarcode($this->$iddocente, 'C39', '', '', '', 5, 0.2, '', 'N');
	// Page number
	$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
}
