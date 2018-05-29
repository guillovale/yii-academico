<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\HabilitarDocente */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="habilitar-docente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'iddocenteperasig')->textInput() ?>

    <?= $form->field($model, 'hemisemestre')->textInput() ?>

    <?= $form->field($model, 'componente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fechaini')->textInput() ?>

    <?= $form->field($model, 'fechafin')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
