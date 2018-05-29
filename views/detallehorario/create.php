<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DetalleHorario */

$this->title = 'Crear Horario';
$this->params['breadcrumbs'][] = ['label' => 'Distributivo', 'url' => Url::previous('cursoofertado')];
$this->params['breadcrumbs'][] = $this->title;
?>

<div style = "font-size:11px" class="row">
	<div class="col-xs-4">
		<h4><?= Html::encode($this->title) ?></h4>
		<address>
			<b>Asignatura: <?= $model->curso->detallemalla->asignatura->NombAsig ?>.</b><br>
			<b>Nivel: <?= $model->curso->detallemalla->nivel?>.</b><br>
			<b>Paralelo: <?= $model->curso->paralelo?>.</b><br>
		</address>

		<?= $this->render('_form', [
		    'model' => $model,
		]) ?>

	</div>

	<div class="col-xs-8">

			<p> <b>Horario carrera: <?= $model->curso->detallemalla->malla->carrera->NombCarr ?></b><br> </p> 
		<?= GridView::widget([
		    'dataProvider' => $dataProvider,
		    //'filterModel' => $searchModel,
		    'columns' => [
		        ['class' => 'yii\grid\SerialColumn'],

		        //'id',
		        'idhorario',
		        //'idcurso',
				'curso.detallemalla.nivel',
				'curso.paralelo',
				'curso.detallemalla.asignatura.NombAsig',
		        'dia',
		        'hora_inicio',
		        'hora_fin',

		        ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}',],
		    ],
		]); ?>

	
	</div>
</div>
