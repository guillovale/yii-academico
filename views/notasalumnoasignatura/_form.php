<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Carrera;
use app\models\Matricula;
use app\models\Periodolectivo;
use app\models\MallaCarrera;
use yii\helpers\Url;
//use app\models\MallaEstudiante;
//use app\models\Mallacurricular;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

$dataCarrera = $this->params['carrera'];
$dataPeriodo=ArrayHelper::map(Periodolectivo::find()
			->where(['<=','idper', 109])
			->orderBy(['DescPerLec' => SORT_DESC])
			->limit(24)
			->all(), 'idper', 'DescPerLec');

$nivel = array('1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');

?>

<div class="notasalumnoasignatura-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($model, 'CIInfPer')->hiddenInput()->label(false); ?>
		
		 <?= $form->field($model, 'idPer')->dropDownList($dataPeriodo) ?>

		<?= $form->field($modelmatricula, 'idCarr')->dropDownList($dataCarrera, 
			['id'=>'idCarr',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('notasalumnoasignatura/listamalla?idcarr=').'"+$(this).val(),
					function( data ){
						$("select#idMc").html( data );
					});',
			]); ?>

		<?= $form->field($model, 'idMc')->dropDownList(array(), 
			['id'=>'idMc',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('notasalumnoasignatura/listavacia').'",
					function( data ){
						$("select#idsemestre").html( data );
					});',
			]);

		?>

		<?= $form->field($modelmatricula, 'idsemestre')->dropDownList($nivel, 
			['id'=>'idsemestre',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('notasalumnoasignatura/listasignaturas?nivel=').
						'"+$(this).val()+";"+$("#idMc").val(),
					function( data ){
						$("select#idAsig").html( data );
					});'
			]); ?>


    <?= $form->field($model, 'idAsig')->dropDownList(array(), 
			['id'=>'idAsig'],
			['prompt'=>'']) ?>


    <?= $form->field($model, 'CalifFinal')->textInput(['maxlength' => true])->input('CalifFinal', ['placeholder' => "Entre 7.0 y 10.0"]) ?>

    <?= $form->field($model, 'asistencia')->textInput()->input('asistencia', ['placeholder' => "Entre 80 y 100"]) ?>


    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true])->input('observacion', ['placeholder' => "MEMORANDO No."]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', ['/notasalumnoasignatura/cancel'], ['class'=>'btn btn-warning']) ?>
    </div>

   

    <?php ActiveForm::end(); ?>

</div>
