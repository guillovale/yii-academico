<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Asignatura */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asignatura-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'IdAsig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NombAsig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ColorAsig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'StatusAsig')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
