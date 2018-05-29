<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Informacionpersonal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="informacionpersonal-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ApellInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ApellMatInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NombInfPer')->textInput(['maxlength' => true]) ?>

	<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', 
				['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		<?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
