<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleMatricula */

$cedula = $this->params['cedula'];
$alumno = $this->params['alumno'];

$this->title = 'Actulizar Matrícula: ' . ' ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Detalle Matriculas', 'url' => Url::previous()];
$this->params['breadcrumbs'][] = ['label' => 'Detalle Matriculas', 
				'url' => ['index', 'idfactura' => $model->idfactura, 'idper' => $model->factura->idper,
					'cedula' => $cedula, 'alumno' => $alumno
				]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="detalle-matricula-update">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I: <?= $this->params['cedula'] ?><br>
		Alumno: <?= $this->params['alumno'] ?><br>
		Carrera: <?= $model->curso?$model->curso->detallemalla->malla->carrera->NombCarr:''; ?><br>
		Asignatura: <?= $model->idAsig->NombAsig ?>
	</address>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
