<?php

namespace app\controllers;
use yii;
use app\models\Periodolectivo;
use app\models\Carrera;
use app\models\Informacionpersonal;
use app\models\Asignatura;
use app\models\Ingreso;
use app\models\MallaCurricular;
use app\models\MallaCarrera;
//use app\models\MallaEstudiante;
//use app\models\Equivalencia;
use app\models\Factura;
use app\models\Configuracion;
use app\models\DetalleMatricula;
use app\models\DetalleMalla;
use app\models\Notasalumnoasignatura;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\db\Query;
require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;
//require_once(__DIR__ . '/../vendor/yii2-editable-master/Editable.php');
//use Editable;

class HomologarController extends \yii\web\Controller
{

	public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'homologar'],
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

    public function actionCrear()
    {
	//$this->layout = "/column3";
	$query = MallaCurricular::find()
			->where(['idCarr' => '001', 'anio_habilitacion' => '2014', 'imp' => 1])
			->orderBy(['idSemestre'=> SORT_ASC]);

	$dataProvider = new ActiveDataProvider([
			    'query' => $query,
				'pagination' => [
					'pageSize' => 100,
				    ],
			]);
	//$searchModel = new MallaCurricularSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		
        return $this->render('crear', [
            'searchModel' => $query,
            'dataProvider' => $dataProvider,
        ]);
	
       //return $this->render('crear');
    }

	public function actionHomologar($id)
	{

		$usuario = Yii::$app->user->identity;
		$modelingreso = Ingreso::find()->where(['id'=>$id])->one();
		if ( $modelingreso && $usuario ) {
			if ( ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'snna') ) {
				$cedula = $modelingreso->CIInfPer;
				$idcarr = $modelingreso->idcarr;
				$idmalla = $modelingreso->idmalla;
				$nombre = $modelingreso->cedula0?$modelingreso->getNombreAlumno():'';
				$nombrecarrera =  $modelingreso->carrera?$modelingreso->carrera->NombCarr:'';
				$malla =  $modelingreso->malla0?$modelingreso->malla0->detalle:'';
				$periodos = ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=> SORT_DESC])->all(), 'idper','DescPerLec');

				$this->view->params['periodos'] = $periodos;
				$this->view->params['alumno'] = $nombre;
				$this->view->params['cedula'] = $cedula;
				$this->view->params['carrera'] = $nombrecarrera;
				$this->view->params['idcarr'] = $idcarr;
				$this->view->params['malla'] = $malla;
				$this->view->params['idmalla'] = $idmalla;
				$query = new Query;
					// compose the query
				$query->select('detalle_matricula.id, idfactura,idcarr,detalle_matricula.idasig,factura.observacion, 
							asignatura.NombAsig,nivel, credito,costo, (costo*credito) as total')
						->from('detalle_matricula')
						->join('JOIN','factura','factura.id = detalle_matricula.idfactura')
						->join('JOIN','asignatura','asignatura.idAsig = detalle_matricula.idasig')
						->where(['cedula' => $cedula, 'detalle_matricula.estado' => 1])
						->andWhere(['factura.tipo_documento' => 'HOMOLOGACION'])
						->orderby(['nivel' => 'DESC', 'detalle_matricula.idasig' => 'ASC'])
						->all();
				$dataProvider = new ActiveDataProvider([
								'query' => $query,
								'pagination' => [
									'pageSize' => 100,
									],
							]);
				$this->view->params['detallematricula'] = $dataProvider;
				$suma = $query->sum('costo*credito');
				$this->view->params['suma'] = $suma;
				
				#$periodo=Periodolectivo::find()->where(['StatusPerLec' => 1])->one();
				#if (!empty($periodo))	{
				#	$idper = $periodo->idper;
				#	$nombreper = $periodo->DescPerLec;
				#}
				$factura = Factura::find()
							->where(['cedula'=>$cedula, 
							'tipo_documento'=>'HOMOLOGACION'])
							->one();
				
				$this->view->params['memo'] = $factura?($factura->observacion):'';
				$this->view->params['periodo'] = $factura?($factura->idper):'';
				$modeldetalle = new DetalleMatricula();
				$modelnota = new Notasalumnoasignatura();
				$modelnota->idMc = $idmalla;
				$modelnota->idPer = $factura?($factura->idper):0;
				$modelnota->CIInfPer = $cedula;
				$modeldetalle->idcarr = $idcarr;	

				if ( $modelnota->load(Yii::$app->request->post()) && $modeldetalle->load(Yii::$app->request->post()) )
				{
					
					#$modelnota->idMc = $malla;				
					$config = Configuracion::find()
									->where(['dato' => 'VH'])
									->one();
					$valor = $config?$config->valor:0;
					$existeidasig = 0;
					$total = 0;
					$creditos = 0;
					$idper = isset($_POST['Notasalumnoasignatura']['idPer'])?$_POST['Notasalumnoasignatura']['idPer']:0;
					#echo var_dump($_POST['Notasalumnoasignatura']['idPer']); exit;
					if (!$factura) {
						$modelfactura = new Factura();
						$modelfactura->idper = $idper;
						$modelfactura->cedula = $modelnota->CIInfPer;
						$modelfactura->tipo_documento = 'HOMOLOGACION';
						$modelfactura->fecha = date('Y-m-d H:i:s');
						$modelfactura->total = 0;
						$modelfactura->usuario = $usuario->id;
						$modelfactura->observacion = $modelnota->observacion_efa;
						if(!$modelfactura->save()){	
							return $this->redirect(\Yii::$app->request->getReferrer());
						}
						$idfactura = $modelfactura->id;	
					}
					else {
						$idfactura = $factura->id;
						$detalles = DetalleMatricula::find()
							->where(['idfactura'=>$idfactura, 'estado'=> 1])->all();
						if ($detalles) {
							
							$sumacredito = 0;
							foreach ($detalles as $detalle){
								$sumacredito = $sumacredito + $detalle->credito;
								if ($modelnota->idAsig == $detalle->idasig) 
									$existeidasig = 1;
							}
							$total = round($sumacredito*$valor, 2);
						}
					}
		
					if ($existeidasig == 0) {
						#$malla = MallaCurricular::find()
						#			->where(['idCarr' => $modeldetalle->idcarr, 'idAsig' => $modelnota->idAsig, 
						#				'imp' => 1])
						#			->orderBy('anio_habilitacion DESC')
						#			->one();
						$detallemalla = Detallemalla::find()
									->where(['idmalla' => $idmalla, 'idasignatura' => $modelnota->idAsig])
									->one();
						if ($detallemalla) 
							$creditos = $detallemalla->credito;
						
						$modeldetalle->idfactura = $idfactura;	
						$modeldetalle->idasig = $modelnota->idAsig;
						//$modeldetalle->nivel = $_POST['DetalleMatricula']['nivel'];
						$modeldetalle->credito = $creditos;
						$modeldetalle->costo = $valor;
						$modeldetalle->fecha = date('Y-m-d');
						$modeldetalle->estado = 1;
						
						if(!$modeldetalle->save()){
							return $this->redirect(\Yii::$app->request->getReferrer());
						}

						$total = round($total + ($valor*$creditos), 2);
						
						if ($factura) {
							$factura->total = $total;
							$factura->save();
						}
						else {
							$modelfactura->total = $total;
							$modelfactura->save();
						}
					}

					//////////////////////////////////////////////
					//completar notas
					$notas = Notasalumnoasignatura::find()
									->where(['CIInfPer' => $modelnota->CIInfPer, 'idPer' => $idper,	'idAsig' => $modelnota->idAsig])
									->one();
					if ($notas) {
						//Yii::app()->user->setFlash('success',$model->CIInfPer);
						//return $this->redirect(Url::previous());
							#$modelnota->idPer = $idper;
							$notas->CalifFinal = $modelnota->CalifFinal;
							$notas->iddetalle = $modeldetalle->id;
							$notas->StatusCalif = 3;
							$notas->VRepite = 1;
							$notas->aprobada = 1;
							$notas->registro = date('Y-m-d H:i:s');
							$notas->asistencia = 80;
							$notas->convalidacion = 0;
							$notas->usu_pregistro = Yii::$app->user->identity->id;
							//$modelnota->observacion_efa = 'nota homologada';
							$notas->save();
					}
					else {
						if ($modeldetalle) {
							//echo var_dump($notas); exit;
							$modelnota->idPer = $idper;
							$modelnota->iddetalle = $modeldetalle->id;
							$modelnota->StatusCalif = 3;
							$modelnota->VRepite = 1;
							$modelnota->aprobada = 1;
							$modelnota->registro = date('Y-m-d H:i:s');
							$modelnota->asistencia = 80;
							$modelnota->convalidacion = 0;
							$modelnota->usu_pregistro = Yii::$app->user->identity->id;
							//$modelnota->observacion_efa = 'nota homologada';
							$modelnota->save();
							//echo var_dump($modelnota->errors); exit;
						}
					}
					//echo var_dump($_POST['DetalleMatricula'], '-', $modelnota->CIInfPer); exit;
					Yii::$app->session->setFlash('success', 'Thank you ');
					return $this->redirect(\Yii::$app->request->getReferrer());
					
				}
				else {
					//echo var_dump($cedula, '-', $modelnota->CIInfPer); exit;
					return $this->render('homologar', [
					'modelnota' => $modelnota,
					'modeldetalle' => $modeldetalle,	
					]);

				}
			}
			
		}
		return $this->redirect(\Yii::$app->request->getReferrer());		
	}



	public function actionAgregar() {
		$keys = unserialize(Yii::$app->request->post('editableKey'));
		echo var_dump($_REQUEST['value'], '-' , $_REQUEST['nota'], '-' , $keys);
		if (isset($_REQUEST['nota'])) {
			$selection=(array)$_REQUEST['nota'];//typecasting
			foreach($selection as $id){
				//$e=Evento::findOne((int)$id);//make a typecasting
				//do your stuff
				//$e->save();
				echo var_dump($selection);
			}
			 exit;
		}
		else {
			echo var_dump('not'); exit;
		}
	}


	public function actionListamalla($idcarr)	{
		#$countMallas = MallaCurricular::find()
         #       	->where(['idcarr' => $idcarr, 'imp' => 1])
          #      	->count();
 
		#$malla = MallaCurricular::find()
        #		->where(['idCarr' => $idcarr, 'imp' => 1])
		#	->groupBy('anio_habilitacion')
		#	->orderBy('anio_habilitacion DESC')
        #		->one();

		//echo var_dumps($posts); exit;
		$mallas = MallaCarrera::find()
				->where(['idcarrera' => $idcarr])
				->orderBy('detalle DESC')
				->all();
 
		if($mallas){
			//echo "<option>-</option>";
			//foreach($mallas as $malla){
				
			#	echo "<option value='".$malla->idMc."'>".$malla->anio_habilitacion."</option>";
			//}
			echo "<option>-</option>";
				foreach($mallas as $malla){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$malla->id."'>".$malla->detalle."</option>";
				}
		}
		#else{
		#	echo "<option>-</option>";
		#}
 
	}


	public function actionListavacia()	{
		echo "<option>-</option>";
		$niveles = array('0'=>'0','1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
		foreach($niveles as $nivel){
				
			echo "<option value='".$nivel."'>".$nivel."</option>";
		}
 
	}

	public function actionListasignaturas($nivel)	{
		
		$idnivel = 0;
		$malla = 0;
		$porciones = explode(";", $nivel);
		
		if ($porciones[0]) $idnivel = $porciones[0];
		#if ($porciones[1]) $idcarr = $porciones[1];
		if ($porciones[1]) $malla = $porciones[1];
		#echo var_dump($nivel, $porciones[0]);exit;
		$asignmallas = DetalleMalla::find()
				->where(['idmalla' => $malla, 'nivel'=> $idnivel])
				->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion', 'idsemestre'])
				->orderBy('asignatura.NombAsig ASC')
				->all();
			if ($asignmallas){
				echo "<option>---</option>";
				foreach($asignmallas as $asignaturamalla){
					$nombreasig = Asignatura::find()
							->where(['idAsig' => $asignaturamalla->idasignatura])
							->one();
					$asignatura = $nombreasig?$nombreasig->NombAsig:'';


					echo "<option value='".$asignaturamalla->idasignatura."'>"
						.$asignaturamalla->idasignatura.'-'.$asignatura."</option>";
				}
			}
		//}
		//echo var_dump($idnivel); exit;
 
		
		else{
			echo "<option>-$malla</option>";
		}
 
	}

	public function actionDelete($id)
    {
		$porciones = explode(";", $id);
		$iddetalle = '';
		$total = '';
		if ($porciones[0]) $iddetalle = $porciones[0];
		if ($porciones[1]) $total = $porciones[1];
		if ($modeldetalle = $this->findModel($iddetalle)) {
			
			$factura = Factura::find()
						->where(['id'=>$modeldetalle->idfactura])
						->one();
			if ($factura) {
				$factura->total = $factura->total - $total;
				$factura->save();
			}
			
			if (($modelnota = Notasalumnoasignatura::find()->where(['iddetalle'=>$modeldetalle->id])->one()) !== null) {
				$modelnota->delete();
			}
			$modeldetalle->delete();
		}
        return $this->redirect(\Yii::$app->request->getReferrer());
    }


	//******************************************************************************************************************************
	public function actionMallapdf($cedula, $idCarr, $malla){

		$query = new Query;
				// compose the query
				$query->select('detalle_matricula.id, idfactura,idcarr,detalle_matricula.idasig, 
						asignatura.NombAsig,nivel, credito,costo, (costo*credito) as total')
					->from('detalle_matricula')
					->join('JOIN','factura','factura.id = detalle_matricula.idfactura')
					->join('JOIN','asignatura','asignatura.idAsig = detalle_matricula.idasig')
					->where(['cedula' => $cedula])
					->andWhere(['factura.tipo_documento' => 'HOMOLOGACION'])
					->orderby(['nivel' => 'DESC', 'detalle_matricula.idasig' => 'ASC']);
				
		// build and execute the query
		$rows = $query->all();
		$factura = $query->one();
		//echo var_dump($factura);
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
		$pdf->putFactura($factura ["idfactura"]);
		//echo var_dump(K_PATH_IMAGES);
		//exit;
		$img_file = K_PATH_IMAGES.'logo.jpg';

		// set document information
		$pdf->SetCreator(PDF_CREATOR);  
		$pdf->SetAuthor('gvp');
		$pdf->SetTitle("Homologación");                
		//$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS"., "" .
		//		 "\n" . "Esmeraldas-Ecuador");
		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetMargins(20, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetFont('helvetica', '', 8);
		$pdf->SetTextColor(0,0,0);
	
			// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->AddPage();

		//*****************************************
		$fecha = date('d-m-Y');
		$html = "<div style='margin-bottom:10px;'>
			<br><br> 
			<p>Esmeraldas, $fecha </p> 			
			<p>Alumno: $alumno->ApellInfPer $alumno->ApellMatInfPer  $alumno->NombInfPer</p> 
			<p>Cédula:	$cedula</p>
			<p>Carrera: $carrera</p>
			<p>Malla: $malla</p> 
			</div><br><br>";
		//Convert the Html to a pdf document
		$pdf->writeHTML($html, true, false, true, false, '');
	 
		$header = array('Nivel', 'Código', 'Asignatura', 'Créditos', 'Costo', 'Total'); 
	 
		// print colored table
		$this->ColoredTable($pdf,$header, $rows, $cedula);
		//$pdf->setY(220);
		//if(isset($matricula)){
		
		$html = " <div>
				<br><br><br><br>
				<address style='font-size:80%;'>
				<p>F.)----------------------------</p></br>
				Solicitante
				 </address>
				</div>";
		//Convert the Html to a pdf document
		$pdf->writeHTML($html, true, false, true, false, '');
		
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
        $w = array(13, 13, 73, 15, 20, 20);
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
		$suma = 0.00;
        foreach($data as $row) {
			$suma += floatval($row['costo']*$row['credito']);
		//echo var_dump($row);
		//exit;
		$pdf->Cell($w[0], 6, $row['nivel'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[1], 6, $row['idasig'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, $row['NombAsig'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[3], 6, $row['credito'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, $row['costo'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[5], 6, $row['total'], 'LR', 0, 'C', $fill);
		$pdf->Ln();
		$fill=!$fill;
        }

		//$pdf->Ln();
		//$fill=!$fill;	
		$pdf->Cell($w[0], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[1], 6, '', 'C', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[3], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, 'Total a Pagar: $', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[5], 6, $suma, 'LR', 0, 'C', $fill);
	
		$pdf->Ln();
		$fill=!$fill;
        $pdf->Cell(array_sum($w), 0, '', 'T');
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
	public $idfactura;
    public function Header() {
		
	// Set font
	$this->SetFont('helvetica', 'B', 11);
	// Title
	$titulo = "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS";
		
	$this->Cell(0, 10, $titulo, 0, false, 'C', 0, '', 0);
	$this->Cell(0, 20, "Esmeraldas-Ecuador                                                                 . ", 0, false, 'R', 0, '', 0);
	$this->Cell(0, 30, "HOMOLOGACIÓN                                                                    . ", 0, false, 'R', 0, '', 0);
	$imager_file = K_PATH_IMAGES.'logo.jpg';
	$imagel_file = K_PATH_IMAGES.'sello_Ecuador.png';
	$this->Image($imagel_file, 15, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
	$this->Image($imager_file, 175, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
    }

    // Page footer
	
    public function Footer() {
	// Position at 15 mm from bottom
	//echo var_dump($this->idfactura); exit;
	$this->SetY(-35);
	// Set font
	$this->SetFont('helvetica', 'I', 8);
	$html = " <div>
				<address style='font-size:80%;'>
				Entregar firmado en la Tesorería.
				Sírvase realizar el depósito de los valores en:
				Cuenta BanEcuador No. 0090102815 - Sublínea 30200 de la Universidad Técnica Luis Vargas Torres de Esmeraldas.
				  </address><br>
				</div>";
		//Convert the Html to a pdf document
		$this->writeHTML($html, true, false, true, false, '');
		$this->write1DBarcode($this->idfactura, 'C39', '', '', '', 5, 0.2, '', 'N');
		$this->Cell(0, 0, $this->idfactura, 0, 1);
	// Page number
	//$this->Cell(0, 10, 'Pag. '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	public function putFactura($factura) {
		$this->idfactura = $factura;
	}	
}


