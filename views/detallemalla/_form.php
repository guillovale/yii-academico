<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleMalla */
/* @var $form yii\widgets\ActiveForm */

$asignatura = $this->params['asignatura']?$this->params['asignatura']:'';
$nivel = $this->params['nivel']?$this->params['nivel']:'';
$caracter = $this->params['caracter']?$this->params['caracter']:'';

?>

<div class="detalle-malla-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'idasignatura')->dropDownList($asignatura) ?>

    <?= $form->field($model, 'nivel')->dropDownList($nivel) ?>

    <?= $form->field($model, 'credito')->textInput() ?>
	 <?= $form->field($model, 'peso')->textInput() ?>

    <?= $form->field($model, 'caracter')->dropDownList($caracter)?>

    <?= $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
