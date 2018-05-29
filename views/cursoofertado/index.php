<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CursoOfertadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Distributivo';
$this->params['breadcrumbs'][] = $this->title;
?>
<div style = "font-size:11px" class="curso-ofertado-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Crear distributivo', ['crearcurso'], ['class' => 'btn btn-success']) ?>
    </p>
	 <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            //'idper',
			[
				'attribute'=>'periodo',
				'value'=>'periodo.DescPerLec',
				'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
            //'iddetallemalla',
			
			//'detallemalla0.malla0.idcarrera',
			[
				'attribute' => 'carrera',
				'value' => 'detallemalla.malla.carrera.NombCarr'
			],
			//'detallemalla0.malla0.carrera0.NombCarr',
			'detallemalla.idasignatura',
			//'detallemalla0.asignatura0.NombAsig',
			[
				'attribute' => 'asignatura',
				'value' => 'detallemalla.asignatura.NombAsig'
			],
			[
				'attribute' => 'nivel',
				'value' => 'detallemalla.nivel'
			],
			//'detallemalla.nivel',
			[
				'attribute'=>'paralelo',
				//'label'=>'id Carrera',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
            //'paralelo',
			[
				'attribute'=>'cupo',
				//'label'=>'Nombre Carrera',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
            //'cupo',
			
            //'idhorario',
			[
				'attribute'=>'iddocente',
				//'label'=>'Id docente',
				'format'=>'text',//raw, html
				//'filter'=>false,
				'enableSorting' => false,
			
	        ],
			//'iddocente',
			[
			 'attribute' => 'docente',
			 'value' => 'docente.nombre'
			 ],
			[
				'attribute'=>'idhorario',
				//'label'=>'Nombre Carrera',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
			//'docente.nombre',
			'restringido',
			'fecha_inicio',
			'fecha_fin',
			

            ['class' => 'yii\grid\ActionColumn', 'template' => '{horario} {update} {delete}',

				'buttons' => [
		        'horario' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-calendar"></span>', $url, [
		                        'title' => Yii::t('app', 'crear horario'),
		            ]);
		        },

		        'update' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
		                        'title' => Yii::t('app', 'actualizar curso'),
		            ]);
		        },
		        'delete' => function($url, $model){
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
						    'class' => '',
						    'data' => [
						        'confirm' => 'Está seguro de borrar el registro ?',
						        'method' => 'post',
						    ],
           		 	]);
       			 }

         	 ],

			'urlCreator' => function($action, $model, $key, $index) {
	            //if ($action == 'delete') {
	              //  return Url::toRoute(['cursoofertado/delete', 'id' => $key]);
	            //}
				if ($action == 'update') {
	                return Url::toRoute(['cursoofertado/update', 'id' => $key]);
	            }
				if ($action == 'horario') {
		                return Url::toRoute(['detallehorario/create', 'idcurso' => $key, 'idhorario'=>$model->idhorario]);
		        }
		    },
	
			],
        ],
    ]); ?>

</div>
