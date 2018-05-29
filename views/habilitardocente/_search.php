<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HabilitarDocenteSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="habilitar-docente-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'iddocenteperasig') ?>

    <?= $form->field($model, 'hemisemestre') ?>

    <?= $form->field($model, 'componente') ?>

    <?= $form->field($model, 'fechaini') ?>

    <?php // echo $form->field($model, 'fechafin') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
