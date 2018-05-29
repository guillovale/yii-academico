<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Equivalencia */
/* @var $form yii\widgets\ActiveForm */

$url = yii::$app->session->get('url');

?>

<div class="notasalumnoasignatura-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'CalifFinal')->textInput(['maxlength' => true])->input('CalifFinal', ['placeholder' => "Entre 7.0 y 10.0"]) ?>

    <?= $form->field($model, 'asistencia')->textInput()->input('asistencia', ['placeholder' => "Entre 80 y 100"]) ?>

    


    <div class="form-group">   
	<?= Html::submitButton($model->isNewRecord ? 'Grabar' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', [$url], ['class' => 'btn btn-warning']) ?>	
    </div>

    <?php ActiveForm::end(); ?>

</div>
