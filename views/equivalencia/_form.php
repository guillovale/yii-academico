<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Equivalencia */
/* @var $form yii\widgets\ActiveForm */

$url = yii::$app->session->get('url');

?>

<div class="equivalencia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asignatura')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'equivalencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput(['readonly' => true]) ?>

   
	
    <?= Html::activeHiddenInput($model, 'usuario') ?>

    <div class="form-group">   
	<?= Html::submitButton($model->isNewRecord ? 'Grabar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', [$url], ['class' => 'btn btn-warning']) ?>	
    </div>

    <?php ActiveForm::end(); ?>

</div>
