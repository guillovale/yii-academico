<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
#use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\CursoOfertado */
/* @var $form yii\widgets\ActiveForm */
$paralelos = $this->params['paralelos'];
?>

<div class="curso-ofertado-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'iddocente')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paralelo')->dropDownList($paralelos, ['id'=>'paralelo',
					'prompt' => '' ]) ?>

    <?= $form->field($model, 'cupo')->textInput() ?>
	<?= $form->field($model, 'idhorario')->textInput() ?>
	<?= $form->field($model, 'restringido')->checkBox(['uncheck' => 0, 'checked' => 1]) ?>
	<?= $form->field($model, 'fecha_inicio')->textInput() ?>
	<?= $form->field($model, 'fecha_fin')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
