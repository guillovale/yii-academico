<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DocenteperasigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="docenteperasig-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dpa_id') ?>

    <?= $form->field($model, 'CIInfPer') ?>

    <?= $form->field($model, 'idPer') ?>

    <?= $form->field($model, 'idAsig') ?>

    <?= $form->field($model, 'idCarr') ?>

    <?php // echo $form->field($model, 'idAnio') ?>

    <?php // echo $form->field($model, 'idSemestre') ?>

    <?php // echo $form->field($model, 'idParalelo') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'idMc') ?>

    <?php // echo $form->field($model, 'tipo_orgmalla') ?>

    <?php // echo $form->field($model, 'id_actdist') ?>

    <?php // echo $form->field($model, 'id_contdoc') ?>

    <?php // echo $form->field($model, 'transf_asistencia') ?>

    <?php // echo $form->field($model, 'transf_frecuente') ?>

    <?php // echo $form->field($model, 'transf_parcial') ?>

    <?php // echo $form->field($model, 'transf_final') ?>

    <?php // echo $form->field($model, 'arrastre') ?>

    <?php // echo $form->field($model, 'extra') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
