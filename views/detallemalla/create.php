<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DetalleMalla */
$malla = $model->malla->detalle;
$carrera = $model->malla->carrera->NombCarr;

$this->title = 'Agregar asignatura en: '. $malla.'-'.$carrera;
$this->params['breadcrumbs'][] = ['label' => 'Detalle Mallas', 
					'url' => ['index', 'id'=> $model->idmalla]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detalle-malla-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
