<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EquivalenciaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="equivalencia-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idequivalencia') ?>

    <?= $form->field($model, 'asignatura') ?>

    <?= $form->field($model, 'equivalencia') ?>

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
