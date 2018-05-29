<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CursoOfertadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Docente por asignatura';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curso-ofertado-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
           
			'idper',
			[
				'attribute'=>'periodo',
				'value'=>'periodo.DescPerLec',
				'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
			[
				'attribute'=>'iddocente',
				'label'=>'Cédula',
				'format'=>'text',//raw, html
				//'filter'=>false,
				'enableSorting' => false,
			
	        ],
			//'iddocente',
			[
			 'attribute' => 'docente',
			 'value' => 'docente.nombre'
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
			
			
            //'cupo',
			
            //'idhorario',
			
			[
				'attribute'=>'idhorario',
				//'label'=>'Nombre Carrera',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
			//'docente.nombre',
			//'restringido',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{horario} {nota}',

				'buttons' => [
		        'horario' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-calendar"></span>', $url, [
		                        'title' => Yii::t('app', 'ver horario'),
		            ]);
		        },

		        'nota' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
		                        'title' => Yii::t('app', 'ver notas'),
		            ]);
		        },
		        

         	 ],

			'urlCreator' => function($action, $model, $key, $index) {
	            //if ($action == 'delete') {
	              //  return Url::toRoute(['cursoofertado/delete', 'id' => $key]);
	            //}
				if ($action == 'nota') {
	                return Url::toRoute(['libretacalificacion/index', 'idcurso' => $key]);
	            }
				if ($action == 'horario') {
		                return Url::toRoute(['detallehorario/ver', 'idcurso' => $key]);
		        }
		    },
	
			],
        ],
    ]); ?>

</div>
