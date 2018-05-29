<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleHorario */

$this->title = 'Ver Horario';
$this->params['breadcrumbs'][] = ['label' => 'Docente por asignatura', 'url' => 
				['cursoofertado/docente', 'CursoOfertadoSearch[iddocente]'=>$modelCurso->iddocente]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div style = "font-size:11px" class="row">
	<div class="col-xs-4">
		<h4><?= Html::encode($this->title) ?></h4>
		<address>
			<b>Docente: <?= $modelCurso->docente->nombre ?>.</b><br>
			<b>Asignatura: <?= $modelCurso->detallemalla->asignatura->NombAsig ?>.</b><br>
			<b>Nivel: <?= $modelCurso->detallemalla->nivel?>.</b><br>
			<b>Paralelo: <?= $modelCurso->paralelo?>.</b><br>
		</address>

	</div>

	<div class="col-xs-8">

			<p> <b>Horario carrera: <?= $modelCurso->detallemalla->malla->carrera->NombCarr ?></b><br> </p> 
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

		        ['class' => 'yii\grid\ActionColumn', 'template' => '',],
		    ],
		]); ?>

	
	</div>
</div>
