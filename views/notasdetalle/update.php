<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NotasDetalle */

$this->title = 'Notas Detalle: ' . ' ' . $model->idnota;
$this->params['breadcrumbs'][] = ['label' => 'Notas Detalles', 'url' => ['index', 'id' => $model->iddetallematricula]];
//$this->params['breadcrumbs'][] = ['label' => $model->idnota, 'url' => ['view', 'id' => $model->idnota]];
$this->params['breadcrumbs'][] = 'Grabar';
?>
<div class="notas-detalle-update">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I: <?= $this->params['cedula'] ?><br>
		Alumno: <?= $this->params['alumno'] ?><br>
		Asignatura: <?= $this->params['asignatura'] ?><br>
		Hemisemestre: <?= $this->params['hemi'] ?> <br>
		Componente: <?= $this->params['comp'] ?>
	</address>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
