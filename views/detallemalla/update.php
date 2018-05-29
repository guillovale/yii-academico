<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleMalla */
$malla = $model->malla->detalle;
$carrera = $model->malla->carrera->NombCarr;

$this->title = 'Actualizar asignatura en: '. $malla.'-'.$carrera;
#$this->title = 'Actualizar Detalle Malla: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Malla: '. $model->idmalla , 'url' => ['index', 'id'=> $model->idmalla]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="detalle-malla-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
