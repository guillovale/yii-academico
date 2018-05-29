<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AbonoFacturaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="abono-factura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['abonar'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idfactura') ?>

    <?= $form->field($model, 'cedula') ?>

    <?php // echo $form->field($model, 'usuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
