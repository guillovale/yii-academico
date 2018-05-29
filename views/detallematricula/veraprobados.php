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
	
	<?php echo $this->render('_searchaprobadas', ['model' => $searchModel]); ?>
	<?php  //echo Html::a('Publicar notas', ['/detallematricula/publicar'], ['class'=>'btn btn-primary btn-ms']);  ?>

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
		
	    #'factura',
		[
			'attribute'=>'factura.periodo.DescPerLec',
			'label'=>'Período',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
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
		'curso.docente.nombre',
		//'idcurso',
		
		'aprobada',
		'reprobada',
		

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
