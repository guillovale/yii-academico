<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMatriculaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listar notas';
//$this->params['breadcrumbs'][] = ['label' => 'Documento Matrícula', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style = "font-size:10px" class="col-xs-12 sidebar">
<div class="detalle-matricula-index">

    <h4><?= Html::encode($this->title) ?></h4>
	
	<?php echo $this->render('_searchnotas', ['model' => $searchModel]); ?>
	<?php  //echo Html::a('Publicar notas', ['/detallematricula/publicar'], ['class'=>'btn btn-primary btn-ms']);  ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idfactura',
            //'idmatricula',
            //'idasig',
		
	    //'factura.idper',
		[
			'attribute'=>'factura.periodo.DescPerLec',
			'label'=>'Período',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		'factura.cedula',

		[
			//'attribute'=>'idMatricula',
			'label'=>'Nombre',
			'format'=>'text',//raw, html
			'content'=>function($data){
				//echo var_dump($data->matricula->getNombreCarrera()); exit;
					return $data->factura->getNombreAlumno();
				},
			'enableSorting' => true,
	        ],

		'idCarr0.NombCarr',
		[
			//'attribute'=>'idMatricula',
			'label'=>'Asignatura',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->idAsig->NombAsig;
	                }
	        ],
		[
			'attribute'=>'nivel',
			//'label'=>'Nivel',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
	        ],

		[
			'attribute'=>'paralelo',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
	        ],
		
		[
			//'attribute'=>'A',
			'label'=>'A1',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getSumanotas('A', 1);
	                }
	        ],
		[
			'label'=>'B1',
			//'label'=>'Nivel',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('B', 1);
	                }
	        ],

		[
			'label'=>'C1',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('C', 1);
	                }
	        ],
		[
			'label'=>'Ex1',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('X', 1);
	                }
	        ],
		[
			'label'=>'As1',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			//'filter'=>false,
			//'enableSorting' => false,
			'content'=>function($data){
				$asist = $data->getSumanotas('T', 1);
				if ($asist >= 0 || $asist <= 10)
					$asistencia = (string)($asist*10).'%';
				elseif ($asist > 10 || $asist <= 100)
					$asistencia = (string)($asist).'%';
				return $asistencia;
				#return $data->getSumanotas('T', 1);
	                }
	        ],
		[
			'label'=>'Nota1',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'contentOptions'=> function($data){	
				
					return ['style'=>'color: blue;']; // <-- right here
				},
			'content'=>function($data){
				//echo var_dump($data['A']); exit;
				return $data->getPromedionotas(1);
	                }
	        ],

			[
			//'attribute'=>'A',
			'label'=>'A2',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getSumanotas('A', 2);
	                }
	        ],
		[
			'label'=>'B2',
			//'label'=>'Nivel',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('B', 2);
	                }
	        ],

		[
			'label'=>'C2',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('C', 2);
	                }
	        ],
		[
			'label'=>'Ex2',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('X', 2);
	                }
	        ],
		[
			'label'=>'As2',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			//'filter'=>false,
			//'enableSorting' => false,
			'content'=>function($data){
				$asist = $data->getSumanotas('T', 2);
				if ($asist >= 0 || $asist <= 10)
					$asistencia = (string)($asist*10).'%';
				elseif ($asist > 10 || $asist <= 100)
					$asistencia = (string)($asist).'%';
				return $asistencia;
				#return $data->getSumanotas('T', 2);
	         }
	        ],
		[
			'label'=>'Nota2',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'contentOptions'=> function($data){	
				
					return ['style'=>'color: blue;']; // <-- right here
				},
			
	    
			'content'=>function($data){
				//echo var_dump($data['A']); exit;
				return $data->getPromedionotas(2);
	                }
	        ],

		[
			//'attribute'=>'R',
			'label'=>'Rec.',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				return $data->getSumanotas('R', 0);
	                }
			
	        ],

			[
			'label'=>'Final',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'contentOptions'=> function($data){	
					$n1 = $data->getPromedionotas(1);
					$n2 = $data->getPromedionotas(2);
					$rec = $data->getSumanotas('R', 0);
					if (($n1 + $n2) >= 14) {
						return ['style'=>'color: black;']; // <-- right here
					}
					elseif (($n1 + $n2) >= 10 && ($n1 + $n2) < 14) {
						if(($n1 + $n2 + $rec) >= 20)
							return ['style'=>'color: black;']; // <-- right here
						else
							return ['style'=>'color: red;'];	
					}
					else {
						return ['style'=>'color: red;']; // <-- right here
					}
				},
			
	    
			'content'=>function($data){
				//echo var_dump($data['A']); exit;
				$n1 = $data->getPromedionotas(1);
				$n2 = $data->getPromedionotas(2);
				$rec = $data->getSumanotas('R', 0);
				$promedio = round(($n1 + $n2)/2, 2);
				if (($n1 + $n2) >= 14) {
						return $promedio;
				}
				elseif (($n1 + $n2) >= 10 && ($n1 + $n2) < 14) {
					if(($n1 + $n2 + $rec) >= 20)
						return 7.0;
					else
						return $promedio;	
				}
				else {
					return $promedio;
				}
			}
	        ],

            //'cnt',
            // 'vrepite',
            // 'costo',
            // 'horario',
            // 'fecha',

            //['class' => 'yii\grid\ActionColumn',
	//	'template' => '{delete}',		
	//	], 


        ],
    ]); ?>

</div>
</div>
