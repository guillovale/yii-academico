<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleMallaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detalle-malla-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idmalla') ?>

    <?= $form->field($model, 'idasignatura') ?>

    <?= $form->field($model, 'nivel') ?>

    <?= $form->field($model, 'credito') ?>

    <?php // echo $form->field($model, 'caracter') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
