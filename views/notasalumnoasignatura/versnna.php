<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMatriculaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listar aprobados SNNA';
//$this->params['breadcrumbs'][] = ['label' => 'Documento Matrícula', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style = "font-size:10px" class="col-xs-12 sidebar">
<div class="detalle-matricula-index">

    <h4><?= Html::encode($this->title) ?></h4>
	
	<?php echo $this->render('_searchsnna', ['model' => $searchModel]); ?>
	<?php # echo Html::a('Guardar', ['/detallematricula/publicar'], ['class'=>'btn btn-primary btn-ms']);  ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => ['style' => 'font-size:11px;'],
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idfactura',
            //'idmatricula',
            //'idasig',
		#'idPer',
		'período',
		#'idcarrera',
		'carrera',
		'cédula',
		'nombre',
		[
				'attribute'=>'sumaNota',
				'label'=>'Nota(10)',
				'format'=>'text',//raw, html
				'contentOptions'=> function($data){	
					return ['style'=>'font-weight: bold;']; // <-- right here
				}
		],
		[
				#'attribute'=>'sumaAsistencia',
				'label'=>'Asistencia',
				'format'=>'text',//raw, html
				'value'=> function($model){	
					return $model['sumaAsistencia'] .'%'; // <-- right here
				},
				'contentOptions'=> function($data){	
					return ['style'=>'font-weight: bold;']; // <-- right here
				}
		],
		[
				'attribute'=>'contador',
				'label'=>'Num. Asignaturas',
				'format'=>'text',//raw, html
		],
		
		//'curso.docente.nombre',
		//'idcurso',
		[
				'attribute'=>'Estado',
				'label'=>'Estado',
				'format'=>'text',//raw, html
				//'contentOptions' => ['style' => 'color:black;'],
				'contentOptions'=> function($data){	
					
					if ($data['Estado'] == 'APROBADO'){
						return ['style'=>'color: black;']; // <-- right here
					}
				
					else
						return ['style'=>'color: red;'];
					},
			],
		#'Estado',
		

            //'cnt',
            // 'vrepite',
            // 'costo',
            // 'horario',
            // 'fecha',

            #['class' => 'yii\grid\ActionColumn',
		#'template' => '{view}',		
		#], 


        ],
    ]); ?>

</div>
</div>
