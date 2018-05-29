<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleparaleloSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="detalleparalelo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idcarr') ?>
	<?= $form->field($model, 'nivel') ?>
	<?= $form->field($model, 'idparalelo') ?>

    <?php // echo $form->field($model, 'cupo') ?>

    <?php // echo $form->field($model, 'habilitado') ?>

    <?php // echo $form->field($model, 'idasig') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
