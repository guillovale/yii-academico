<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MallaCarrera */
/* @var $form yii\widgets\ActiveForm */
$carrera =  $this->params['carrera'];
?>

<div class="malla-carrera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idcarrera')->dropDownList($carrera, ['prompt'=>'Selecione una carrera']) ?>

    <?= $form->field($model, 'detalle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'anio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado')->checkBox(['uncheck' => 0, 'checked' => 1]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
