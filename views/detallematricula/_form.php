<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleMatricula */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detalle-matricula-form">

    <?php $form = ActiveForm::begin(); ?>

	 
    <?= $form->field($model, 'idasig')->textInput(['maxlength' => true, 'disabled' => true]) ?>
	
	<?= $form->field($model, 'nivel')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'idcurso')->textInput() ?>

    <?= $form->field($model, 'credito')->textInput(['disabled' => true]) ?>


    <?= $form->field($model, 'costo')->textInput() ?>


    <?= $form->field($model, 'fecha')->textInput(['disabled' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
