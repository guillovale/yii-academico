<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LibretaCalificacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="libreta-calificacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idper') ?>

    <?= $form->field($model, 'iddocenteperasig') ?>

    <?= $form->field($model, 'iddocente') ?>

    <?= $form->field($model, 'fecha') ?>

    <?php // echo $form->field($model, 'hemisemestre') ?>

    <?php // echo $form->field($model, 'idparametro') ?>

    <?php // echo $form->field($model, 'idcomponente') ?>

    <?php // echo $form->field($model, 'tema') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
