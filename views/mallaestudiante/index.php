<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MallaEstudianteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Malla Estudiantes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-estudiante-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Malla Estudiante', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id_malla',
            'cedula',
		
		[
			'attribute'=>'carrera',
			'label'=>'Cod.',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],

		[
			//'attribute'=>'nombreCarrera',
			'label'=>'Carrera',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getNombreCarrera();}
	        ],


            // 'carrera',
            // 'anio_habilitacion',

		[
			'attribute'=>'anio_habilitacion',
			//'label'=>'Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],


		

            //'fecha',


		[
			'attribute'=>'fecha',
			//'label'=>'Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
