<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Docenteperasig */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="docenteperasig-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idPer')->textInput() ?>

    <?= $form->field($model, 'idAsig')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idCarr')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idAnio')->textInput() ?>

    <?= $form->field($model, 'idSemestre')->textInput() ?>

    <?= $form->field($model, 'idParalelo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'idMc')->textInput() ?>

    <?= $form->field($model, 'tipo_orgmalla')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_actdist')->textInput() ?>

    <?= $form->field($model, 'id_contdoc')->textInput() ?>

    <?= $form->field($model, 'transf_asistencia')->textInput() ?>

    <?= $form->field($model, 'transf_frecuente')->textInput() ?>

    <?= $form->field($model, 'transf_parcial')->textInput() ?>

    <?= $form->field($model, 'transf_final')->textInput() ?>

    <?= $form->field($model, 'arrastre')->textInput() ?>

    <?= $form->field($model, 'extra')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
