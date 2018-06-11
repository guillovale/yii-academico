<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Carrera;
use yii\helpers\ArrayHelper;
//use app\models\Usuario;
use app\models\Periodolectivo;
//use yii\jui\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\ExtensionMatricula */
/* @var $form yii\widgets\ActiveForm */
$carreras = $this->params['carreras'];
$idperiodo = $this->params['idperiodo'];
$usuario = $this->params['usuario'];
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['statuscarr' => 1])->all(), 'idCarr', 'NombCarr');
//$userid = Yii::$app->user->identity->id;
//$dataPeriodo=Periodolectivo::find()->where(['StatusPerLec' => 1])->one();
$date = date('Y-m-d');
$datef = date('Y-m-d', strtotime($date. ' + 3 days'));
$model->idper = $idperiodo;
$model->usuario = $usuario;
$model->fechain = $date;
$model->fechafin = $datef;
?>

<div class="extension-matricula-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'idper')->textInput(['readonly' => true]) ?>

	<?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'fechain')->textInput()->input('fecha', ['placeholder' => "2017-05-15"]) ?>

	<?= $form->field($model, 'fechafin')->textInput()->input('fecha', ['placeholder' => "2017-05-15"]) ?>

	<?= $form->field($model, 'idcarr')->dropDownList($carreras, ['prompt'=>'Selecione una carrera']) ?>

	<?= $form->field($model, 'memorandum')->textInput() ?>

	<?= $form->field($model, 'exonerado')->checkBox(['uncheck' => 0, 'checked' => 1]) ?>

	<?= $form->field($model, 'usuario')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
