<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Detalleparalelo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detalleparalelo-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idparalelo')->textInput() ?>

    <?= $form->field($model, 'nivel')->textInput() ?>

    <?= $form->field($model, 'idper')->textInput() ?>

    <?= $form->field($model, 'idcarr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cupo')->textInput() ?>

    <?= $form->field($model, 'habilitado')->textInput() ?>

    <?= $form->field($model, 'idasig')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
