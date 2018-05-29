<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExtensionMatriculasearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extension-matricula-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idper') ?>

    <?= $form->field($model, 'cedula') ?>

    <?= $form->field($model, 'fechain') ?>

    <?= $form->field($model, 'fechafin') ?>

    <?php // echo $form->field($model, 'idcarr') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
