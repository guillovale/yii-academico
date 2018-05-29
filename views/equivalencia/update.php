<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Equivalencia */

$this->title = 'Actualizar Equivalencia: ' . ' ' . $model->idequivalencia;
$this->params['breadcrumbs'][] = ['label' => 'Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idequivalencia, 'url' => ['view', 'idequivalencia' => $model->idequivalencia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="equivalencia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
