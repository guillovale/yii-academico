<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DetalleHorario */
/* @var $form yii\widgets\ActiveForm */
$horas = $this->params['horas'];
$dias = $this->params['dias'];
?>

<div class="detalle-horario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dia')->dropDownList($dias, ['prompt' => '' ]) ?>

    <?= $form->field($model, 'hora_inicio')->dropDownList($horas, ['prompt' => '' ]) ?>

    <?= $form->field($model, 'hora_fin')->dropDownList($horas, ['prompt' => '' ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Guardar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
