<?php

namespace app\controllers;

use Yii;
use app\models\Informacionpersonal;
use app\models\Factura;
use app\models\FacturaSearch;
use app\models\DetalleMatricula;
use app\models\Matricula;
use app\models\Carrera;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\db\Query;
use yii\helpers\ArrayHelper;
require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;
/**
 * FacturaController implements the CRUD actions for Factura model.
 */
class FacturaController extends Controller
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
     * Lists all Factura models.
     * @return mixed
     */
    public function actionIndex()
    {
	//$this->layout = "/matriculafactura";
	Url::remember();
	$cedula = (isset($_GET['FacturaSearch']['cedula']) ? $_GET['FacturaSearch']['cedula'] : '');
	$id = (isset($_GET['FacturaSearch']['id']) ? $_GET['FacturaSearch']['id'] : '');
	$detalle_matricula = '';
	if ($id != '' && !is_null($id)){
		$factura = Factura::find()
				->where(['id' => $id])
				->andFilterWhere(['cedula' => $cedula])
				->orderBy(['idper' => SORT_DESC])
				->one();
		if ($factura){
			$cedula = $factura->cedula;
			$detalle_matricula = DetalleMatricula::find()
						->where(['idfactura'=>$factura->id]);
		}
	}
	elseif ($cedula != '' && !is_null($cedula)) {
		$factura = Factura::find()
				->where(['cedula' => $cedula])
				//->andFilterWhere(['id' => $id])
				->orderBy(['idper' => SORT_DESC])
				->one();
		if ($factura){
			$detalle_matricula = DetalleMatricula::find()
						->where(['idfactura'=>$factura->id]);
		}
	}
			
	//$alumno = Informacionpersonal::find()
	//			->where(['CIInfPer' => $cedula])
	//			->one();
	//$this->view->params['alumno'] = (count($alumno))?($alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' .$alumno->NombInfPer):'';
									//$alumno?$alumno:null;
	$this->view->params['matricula'] = $detalle_matricula?$detalle_matricula:null;
	//echo var_dump($detalle_matricula); exit;	

        $searchModel = new FacturaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	return $this->redirect(['index']);
    }

    /**
     * Displays a single Factura model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
	//return $this->renderPartial('application.views.abc._jobList',array('value'=>$value));
	//return $this->redirect(['/abonofactura/create', 'idfactura'=>$id, 'total'=>$total]);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Factura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /*$model = new Factura();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
    }

    /**
     * Updates an existing Factura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
		
		if ( $model && ($usuario->idperfil == 'fin' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad')) {
		
			if ($model->load(Yii::$app->request->post()) ) {
				$model->usuario = $usuario->LoginUsu;
								 
				if ($model->validate() && $model->save()){
					
					$detalle_matricula = DetalleMatricula::find()
						->where(['idfactura'=>$model->id])->all();
					if (!empty($detalle_matricula)) {
						foreach($detalle_matricula as $detalle){
							$matricula = Matricula::find()
								->where(['idMatricula'=>$detalle->idmatricula])
								->one();
							if (!empty($matricula)) {
								//echo var_dump($matricula->statusMatricula); exit;
								if ($matricula->statusMatricula != 'APROBADA'){
									//echo var_dump($matricula->statusMatricula); exit;
									$matricula->statusMatricula = 'APROBADA';
									$matricula->save();
								}
							}
						}
			
					}					


					return $this->redirect(['index', 'FacturaSearch[cedula]' => $model->cedula]);
				}
			} else {
				return $this->render('update', [
				    'model' => $model,
				]);
			}
		}
		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Factura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       /* $this->findModel($id)->delete();

        return $this->redirect(['index']);*/
    }


	//******************************************************************************************************************************
	public function actionMallapdf($idfactura){

		#$query = new Query;
				// compose the query
		$detallematricula = DetalleMatricula::find();
				$detallematricula->select('detalle_matricula.id, idfactura,idcarr,detalle_matricula.idasig, 
						asignatura.NombAsig as asignatura,nivel, credito,costo, (costo*credito) as total')
					->from('detalle_matricula')
					->join('JOIN','factura','factura.id = detalle_matricula.idfactura')
					->join('JOIN','asignatura','asignatura.idAsig = detalle_matricula.idasig')
					->where(['idfactura' => $idfactura])
					->andWhere(['factura.tipo_documento' => 'MATRICULA'])
					->orderby(['idcarr' => 'ASC','nivel' => 'ASC', 'detalle_matricula.idasig' => 'ASC']);
				
		// build and execute the query
		if ($detallematricula) {
			$rows = $detallematricula->all();
			$detalle = $detallematricula->one();
			#echo var_dump($factura->factura->getNombreAlumno());
			#exit;
			#$alumno = Informacionpersonal::find()
			#	->where(['CIInfPer' => $factura["cedula"]])
			#	->one();
			$nombre = $detalle?$detalle->factura->getNombreAlumno():'';
			$cedula = $detalle?$detalle->factura->cedula:'';
			$periodo = $detalle?$detalle->factura->periodo->DescPerLec:'';
			#if (!empty($alumno)) $nombre = $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer. ' ' . $alumno->NombInfPer;
			$idcarreras = ArrayHelper::getColumn($detallematricula->groupBy(['idcarr'])->all(), 'idcarr');
			#echo var_dump( $detallematricula->groupBy(['idcarr'])->all() ); exit;
			$carreras = ArrayHelper::map(
					Carrera::find()
					#->select(['NombCarr'])
					->where(['idcarr' => $idcarreras])
					->all(), 'idCarr', 'NombCarr');
			#echo var_dump( json_encode($carreras, JSON_UNESCAPED_UNICODE ) ); exit;
			$nomcarreras = json_encode($carreras, JSON_UNESCAPED_UNICODE );
			
			$facultad = '';
			$nomfacultad = '';
			#if (!empty($nomcarr)) {$carrera = $nomcarr->NombCarr; $facultad = $nomcarr->idfacultad; 
			#	$nomfacultad = $nomcarr->getNombreFacultad();}
	
		
			//$img_file = K_PATH_IMAGES.'sello_Ecuador.png';
			//$pdf = new tcpdf();
			$pdf = new MYPDF();	 
			$pdf->putFactura($idfactura);
			//echo var_dump(K_PATH_IMAGES);
			//exit;
			$img_file = K_PATH_IMAGES.'logo.jpg';

			// set document information
			$pdf->SetCreator(PDF_CREATOR);  
			$pdf->SetAuthor('gvp');
			$pdf->SetTitle("Certificado Matrícula");                
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
				<p>La UNIVERSIDAD TECNICA LUIS VARGAS TORRES DE ESMERALDAS CERTIFICA QUE:</>			
				<p>El Alumno: $nombre C.C. $cedula</p> 
				<p>Se encuentra legalmente matriculado en el el período académico $periodo .</p>
				</div><br><br>";
			//Convert the Html to a pdf document
			$pdf->writeHTML($html, true, false, true, false, '');
		 
			$header = array('Nivel', 'Carrera', 'Código', 'Asignatura', 'Créditos'); 
		 
			// print colored table
			$this->ColoredTable($pdf,$header, $rows, $cedula);
			//$pdf->setY(220);
			//if(isset($matricula)){
		
			$html = " <div>
					<br>
					<address style='font-size:80%;'>
					Carreras: $nomcarreras
					 </address>
					</div>";
			//Convert the Html to a pdf document
			$pdf->writeHTML($html, true, false, true, false, '');
		
			// reset pointer to the last page
			$pdf->lastPage();
			$file = $cedula . '.' . 'pdf';
			//Close and output PDF document
			$pdf->Output($file, 'D');
		}
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
        $w = array(20, 20, 20, 80, 20);
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
		$pdf->Cell($w[0], 6, $row['nivel'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[1], 6, $row['idcarr'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, $row['idasig'], 'LR', 0, 'C', $fill);
		$pdf->Cell($w[3], 6, $row['asignatura'], 'LR', 0, 'L', $fill);
		$pdf->Cell($w[4], 6, $row['credito'], 'LR', 0, 'C', $fill);

		$pdf->Ln();
		$fill=!$fill;
        }

		//$pdf->Ln();
		//$fill=!$fill;	
		$pdf->Cell($w[0], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[1], 6, '', 'C', 0, 'C', $fill);
		$pdf->Cell($w[2], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[3], 6, '', 'LR', 0, 'C', $fill);
		$pdf->Cell($w[4], 6, '', 'LR', 0, 'C', $fill);
	
		$pdf->Ln();
		$fill=!$fill;
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }


    /**
     * Finds the Factura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Factura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Factura::findOne($id)) !== null) {
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
	$this->Cell(0, 20, "Secretaría Académica                                                                 . ", 0, false, 'R', 0, '', 0);
	$this->Cell(0, 30, "CERTIFICADO DE MATRÍCULA                                                           . ", 0, false, 'R', 0, '', 0);
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
				Firmado y sellado en ESMERALDAS, cantón ESMERALDAS.
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
