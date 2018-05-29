<!-- CSS media query within a stylesheet -->
<style>
@media print {
  @page {
  size: auto;
  margin: 0;
       }
.footer, .header { display:none;} 
  #printPageButton {
    display: none;
  }
  a[href] {
    display: none;
  }
 
}
</style>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Resumen de notas:';
//$this->params['breadcrumbs'][] = ['label' => 'Libreta', 'url' => Yii::$app->request->referrer];
//$this->params['breadcrumbs'][] = $this->title;
$periodo =  $this->params['periodo'];
$carrera =  $this->params['carrera'];
$docente = $this->params['docente'];
$iddocente = $this->params['cedula'];
$asignatura =  $this->params['asignatura'];
$paralelo =  $this->params['paralelo'];
$nivel =  $this->params['nivel'];
$idper = $this->params['idper'];
$idcarr = $this->params['idcarr'];
$idasig = $this->params['idasig'];
$idcurso = $this->params['idcurso'];
$publicar = $this->params['publicar'];
$ca = $this->params['ca'];
$cb = $this->params['cb'];
$cc = $this->params['cc'];
$ex = $this->params['ex'];
$as = $this->params['as'];
$ct = $this->params['ct'];

?>
<div style = "font-size:11px" class="row">
	<div class="row">
		<?= Html::img('@web/uploads/encabezado.png', ['alt'=>'some', 'class'=>'thing']);?>
		<?= Html::a( 'Regresar', Yii::$app->request->referrer, ['class' => 'btn btn-warning btn-ms']);	?>
		<?php if ($publicar == 1) {
			  echo Html::a('Imprimir notas', ['/notasdetalle/publicarnota', 'idcurso'=>$idcurso],
								 ['class'=>'btn btn-primary btn-ms']); 
			}
			else {
				 echo ('<button id="printPageButton", onclick="window.print()">Imprimir</button>');
			}
		 ?>
		
	</div>

	<div class="notas-detalle-index">
		
		<h4>  Asignatura: <?= $this->params['asignatura'] ?> </h4>
		<address>
			Fecha: <?= date("Y-m-d"); ?> <br>
			Docente: <?= $this->params['docente'] ?> <br>
			Período: <?= $this->params['periodo'] ?> <br>
			Carrera: <?= $this->params['carrera'] ?> <br>
			Nivel: <?= $this->params['nivel'] ?> 
			Paralelo: <?= $this->params['paralelo'] ?> </b>
		</address>
		<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

		<?= GridView::widget([
		    'dataProvider' => $dataProvider,
		    'filterModel' => $searchModel,
		    'columns' => [
		        //['class' => 'yii\grid\SerialColumn'],
			//'cedula',
			[
				'attribute'=>'cedula',
				'label'=>'Cédula',
				'format'=>'text',//raw, html
			],
			//'hemisemestre',
			//'parametro',
			'estudiante',
			//'componente',
		        //'idnota',
			//'idlibreta',
		       // 'iddetallematricula',
		        //'nota',
			'A1',
			'B1',
			'C1',
			'Ex1',
			'As1',
			[
				//'attribute'=>'idMatricula',
				'label'=>'Nt1',
				'format'=>'text',//raw, html
				'contentOptions' => ['style' => 'color:blue;'],
				'content'=>function($data) use ($ca, $cb, $cc, $ex, $as, $ct) {
					//echo var_dump($data); exit;
					$promedio_1 = ($data['A1']*$ca + $data['B1']*$cb + $data['C1']*$cc)*$ct + $data['Ex1']*$ex;
					return round($promedio_1);
			            }
			],
			'A2',
			'B2',
			'C2',
			'Ex2',
			'As2',
			[
				//'attribute'=>'idMatricula',
				'label'=>'Nt2',
				'format'=>'text',//raw, html
				'contentOptions' => ['style' => 'color:blue;'],
				'content'=>function($data) use ($ca, $cb, $cc, $ex, $as, $ct) {
					//echo var_dump($data); exit;
					$promedio_2 = ($data['A2']*$ca + $data['B2']*$cb + $data['C2']*$cc)*$ct + $data['Ex2']*$ex;
					return round($promedio_2);
			            }
			],

			//'Suf',
			[
				'attribute'=>'Suf',
				'label'=>'Rec',
				'format'=>'text',//raw, html
			],
				
			[
				//'attribute'=>'idMatricula',
				'label'=>'Ast',
				'format'=>'text',//raw, html
				//'contentOptions' => ['style' => 'color:black;'],
				'content'=>function($data) use ($as, $publicar) {
					if ($publicar == 1) {
						//echo var_dump($promedio_1); exit;
						$promedio = ($data['As1'] + $data['As2'])/2;
						#if ($promedio >= 14 && $promedio <= 20)
							$promedio = $promedio;
						//elseif ($promedio > 10 && $promedio <= 100)
						//	$promedio = $promedio;
						return $promedio .'%';
					}
			    },
				'contentOptions'=> function($data)  use ($ca, $cb, $cc, $ex, $as, $ct){	
					$promedio = ($data['As1'] + $data['As2'])/2;
					if (($promedio >= 70 && $promedio <= 100)){
						return ['style'=>'color: black;']; // <-- right here
					}
				
					else
						return ['style'=>'color: red;'];
					},
			],
		
			[
				//'attribute'=>'idMatricula',
				'label'=>'Final',
				'format'=>'text',//raw, html
				//'contentOptions' => ['style' => 'color:black;'],
				'content'=>function($data) use ($ca, $cb, $cc, $ex, $as, $ct, $publicar) {
					//echo var_dump($promedio_1); exit;
					if ($publicar == 1) {
						$promedio_1 = ($data['A1']*$ca + $data['B1']*$cb + $data['C1']*$cc)*$ct + $data['Ex1']*$ex;
						$promedio_2 = ($data['A2']*$ca + $data['B2']*$cb + $data['C2']*$cc)*$ct + $data['Ex2']*$ex;
						$nota = round($promedio_1) + round($promedio_2);
						if ($nota >= 14){
							return $nota/2;
						}
						elseif ($nota >= 10 && $nota < 14){
							if ($nota + $data['Suf'] >= 20)
								return 7.0;
							else
								return $nota/2;
						}
						else
							return $nota/2;
					}
			     },
				'contentOptions'=> function($data)  use ($ca, $cb, $cc, $ex, $as, $ct){	
					$promedio_1 = ($data['A1']*$ca + $data['B1']*$cb + $data['C1']*$cc)*$ct + $data['Ex1']*$ex;
					$promedio_2 = ($data['A2']*$ca + $data['B2']*$cb + $data['C2']*$cc)*$ct + $data['Ex2']*$ex;
					$nota = round($promedio_1) + round($promedio_2);
					if ( $nota >= 14){
						return ['style'=>'font-weight:bold;  color: black;']; // <-- right here
					}
					elseif ($nota >= 10 && $nota < 14){
						if ($nota + $data['Suf'] >= 20)
							return ['style'=>'font-weight:bold;  color: black;'];
						else
							return ['style'=>'font-weight:bold;  color: red;'];
					}
					else
						return ['style'=>'font-weight:bold;  color: red;'];
				},
			],

			[
				//'attribute'=>'idMatricula',
				'label'=>'Est.',
				'format'=>'text',//raw, html
				'content'=>function($data) use ($ca, $cb, $cc, $ex, $as, $ct, $publicar){
					if ($publicar == 1) {
						$promedio = ($data['As1'] + $data['As2'])/10;
						$promedio_1 = round(($data['A1']*$ca + $data['B1']*$cb + $data['C1']*$cc)*$ct + $data['Ex1']*$ex);
						$promedio_2 = round(($data['A2']*$ca + $data['B2']*$cb + $data['C2']*$cc)*$ct + $data['Ex2']*$ex);
						$estado = '';
						$nota = round($promedio_1) + round($promedio_2);
						#echo var_dump($nota, '-', $promedio ); exit;
						if ( $nota >= 14 && ($promedio >= 14 && $promedio <= 20) )
							$estado = 'APROBADA';
						elseif ( $nota >= 10 && $nota < 14 && ($promedio >= 14 && $promedio <= 20) )
							if ($nota + $data['Suf'] >= 20)
								$estado = 'APROBADA';
							else
								$estado = 'REPROBADA';
						else
							$estado = 'REPROBADA';
						return $estado;
					}
			    },
				'contentOptions'=> function($data) use ($ca, $cb, $cc, $ex, $as, $ct) {	
					$promedio = ($data['As1'] + $data['As2'])/10;
					$promedio_1 = round(($data['A1']*$ca + $data['B1']*$cb + $data['C1']*$cc)*$ct + $data['Ex1']*$ex);
					$promedio_2 = round(($data['A2']*$ca + $data['B2']*$cb + $data['C2']*$cc)*$ct + $data['Ex2']*$ex);
					$nota = round($promedio_1) + round($promedio_2);
					//echo var_dump($nota, $promedio); exit;
					if ($nota >= 14 && ($promedio >= 14 && $promedio <= 20) ){
						return ['style'=>'color: black;']; // <-- right here
					}
					elseif ( $nota >= 10 && $nota < 14 && ($promedio >= 14 && $promedio <= 20) ){
						if ($nota + $data['Suf'] >= 20)
							return ['style'=>'color: black;'];
						else
							return ['style'=>'color: red;'];
					}
					else
						return ['style'=>'color: red;'];
					},
			],

		        // 'tema',

		        ['class' => 'yii\grid\ActionColumn', 'template'=> ''],
		    ],
		]); ?>
	</div>

<footer>
  <address style="font-size:90%;">
	</br>
	</br>
	</br>
	F.)-------------------------------------
	</br>
	Docente
	</br></br>
	Entregar firmado en la Secretaría Técnica Académica de su Facultad.

  </address>
</footer>
</div>
