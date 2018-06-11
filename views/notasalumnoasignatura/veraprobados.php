<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMatriculaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listar aprobados-reprobados';
//$this->params['breadcrumbs'][] = ['label' => 'Documento Matrícula', 'url' => Yii::$app->request->referrer];
$this->params['breadcrumbs'][] = $this->title;
?>
<div style = "font-size:10px" class="col-xs-12 sidebar">
<div class="detalle-matricula-index">

    <h4><?= Html::encode($this->title) ?></h4>
	
	<?php echo $this->render('_searchaprobadas', ['model' => $searchModel]); ?>
	<?php # echo Html::a('Guardar', ['/detallematricula/publicar'], ['class'=>'btn btn-primary btn-ms']);  ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => ['style' => 'font-size:11px;'],
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            
            //'idfactura',
            //'idmatricula',
            //'idasig',
		'periodo',
	    'carrera',
		#'CIInfPer',
		[
			'attribute'=>'CIInfPer',
			#'label'=>'Estado',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],
		'nombre',
		'asignatura',
		'nivel',
		'paralelo',
		
		//'curso.docente.nombre',
		//'idcurso',
		[
			'attribute'=>'aprobadas',
			'label'=>'Estado',
			'format'=>'text',//raw, html
			'filter'=>false,
			'contentOptions'=> function($data){	
				if ($data->aprobadas == 'aprobada'){
					return ['style'=>'color: black;']; // <-- right here
				}
				else
					return ['style'=>'color: red;'];
				},
	        
	        ],

		
		#'aprobadas',
		#'reprobadas',
		

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
