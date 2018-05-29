<?php

use yii\helpers\Html;
#use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\CursoOfertadoSearch */
/* @var $form yii\widgets\ActiveForm */
$carrera = $this->params['carrera'];
?>

<div style = "font-size:11px" class="curso-ofertado-search">

    <?php $form = ActiveForm::begin([
		'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcarr')->dropDownList($carrera, ['prompt'=>'Elija una carrera']) ?>

   
    <?php // echo $form->field($model, 'cupo') ?>

    <?php // echo $form->field($model, 'idhorario') ?>

    <?php // echo $form->field($model, 'estado') ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary btn-sm']) ?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
