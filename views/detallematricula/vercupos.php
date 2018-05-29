<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMatriculaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista Matrícula';
//$this->params['breadcrumbs'][] = ['label' => 'Documento Matrícula', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style = "font-size:10px" class="col-xs-12 sidebar">
<div class="detalle-matricula-index">

    <h4><?= Html::encode($this->title) ?></h4>
	<?php echo $this->render('_search', ['model' => $searchModel]); ?>
	

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idfactura',
            //'idmatricula',
            //'idasig',
		[
			'attribute'=>'factura.periodo.DescPerLec',
			'label'=>'Período',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		[
			'attribute'=>'idcurso',
			'label'=>'Curso',
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

		[
			'attribute'=>'idCarr0.NombCarr',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		
		[
			'attribute'=>'idasig',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
	    //'asignatura',
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
		
		/*
		[
			'attribute'=>'estado',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'contentOptions'=> function($data){	
									if ($data->estado == 1){
										return ['style'=>'color: black;']; // <-- right here
									}
									else
										return ['style'=>'color: red;'];
								},
	        ],
		*/
		[
			//'attribute'=>'idMatricula',
			'label'=>'Estado',
			'format'=>'text',//raw, html
			'content'=>function($data){
				$estado = '';
				if ($data->estado == 1)
					$estado = 'APROBADA';
				if ($data->estado == 0)
					$estado = 'ANULADA';
				return $estado;
	                },
			'contentOptions'=> function($data){	
				if ($data->estado == 1){
					return ['style'=>'color: black;']; // <-- right here
				}
				else
					return ['style'=>'color: red;'];
				},
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
