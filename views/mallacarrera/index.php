<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MallaCarreraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Malla Carreras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-carrera-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Malla Carrera', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idcarrera',
			[
				'attribute' => 'nombrecarr',
				'label' => 'Carrera',
				'value' => 'carrera.NombCarr'
			],
			//'carrera.NombCarr',
            'detalle',
            'fecha',
            'anio',
            'estado',

            ['class' => 'yii\grid\ActionColumn', 'template'=> '{asignatura} {update} {delete}',

				'buttons' => [
		        'asignatura' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
						['detallemalla/index', 'id' => $model->id], [
		                        'title' => Yii::t('app', 'ver asignaturas'),
		            ]);
		        },

		        'update' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], [
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

			],
        ],
    ]); ?>

</div>
