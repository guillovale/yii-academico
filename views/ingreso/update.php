<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ingreso */

$this->title = 'Actualizar Ingreso: ';
$this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index', 'IngresoSearch[CIInfPer]'=> $model->CIInfPer]];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="ingreso-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
