<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Factura */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="factura-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cedula')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'idper')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput(['disabled' => true]) ?>
	<?= $form->field($model, 'valor_matricula')->textInput() ?>
	<?= $form->field($model, 'valor_credito')->textInput() ?>
	<?= $form->field($model, 'valor_otro')->textInput() ?>
    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'usuario')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?php print \yii\helpers\Html::a( 'Cancelar', Yii::$app->request->referrer, ['class' =>'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
