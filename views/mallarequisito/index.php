<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MallaRequisitoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Malla requisitos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-requisito-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear requisito', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idmalla',
			//'mallarequisito.malla.detalle',
			[
				 'attribute' => 'detalle',
				 'value' => 'mallarequisito.malla.detalle'
			 ],
			[
				 'attribute' => 'carrera',
				 'value' => 'mallarequisito.malla.carrera.NombCarr'
			 ],

			[
				'label'  => 'Nivel pre.',
				 'attribute' => 'nivel',
				 'value' => 'mallarequisito.nivel'
			 ],		
			[
				'label'  => 'Id pre.',
				 'attribute' => 'idasig',
				 'value' => 'mallarequisito.asignatura.IdAsig'
			 ],
			#'detallemalla.idasignatura',
			 [
				'label'  => 'Asignatura pre.',
				 'attribute' => 'asignatura',
				 'value' => 'mallarequisito.asignatura.NombAsig'
			 ],
			//'detallemalla.malla.carrera.NombCarr',
			//'detallemalla.asignatura.NombAsig',
            //'idmallarequisito',
			#[
			#	'label'  => 'Carrera pre.',
			#	 'attribute' => 'detallemalla.malla.carrera.NombCarr',
			#	 'value' => 'detallemalla.malla.carrera.NombCarr'
			# ],
			
			//'mallarequisito.malla.carrera.NombCarr',
			'detallemalla.nivel', 
			'detallemalla.idasignatura',
			
			//'mallarequisito.asignatura.NombAsig',
			[
				'label'  => 'Asignatura',
				 'attribute' => 'detallemalla.asignatura.NombAsig',
				 'value' => 'detallemalla.asignatura.NombAsig'
			 ],
            'tipo',

            ['class' => 'yii\grid\ActionColumn', 'template'=> '{delete}'],
        ],
    ]); ?>

</div>
