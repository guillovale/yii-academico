<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ExtensionDocente */
/* @var $form yii\widgets\ActiveForm */
$dataCarrera = $this->params['carrera'];
$nivel = array('0'=>'0', '1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
?>

<div class="extension-docente-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'carrera')->dropDownList($dataCarrera, 
			['id'=>'carrera',
			'prompt'=>'',
			'onchange'=>'$.post( "'.Url::toRoute('extensiondocente/listasignatura?nivel=').
						'"+$("#nivel").val()+";"+$(this).val(),
					function( data ){
						$("select#idcurso").html( data );
					});'
			]); ?>

	<?= $form->field($model, 'nivel')->dropDownList($nivel, 
			['id'=>'nivel',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('extensiondocente/listasignatura?nivel=').
						'"+$(this).val()+";"+$("#carrera").val(),
					function( data ){
						$("select#idcurso").html( data );
					});'
			]); ?>
	 <?= $form->field($model, 'idcurso')->dropDownList(array(), 
			['id'=>'idcurso'],
			['prompt'=>'']) ?>

   
    <?= $form->field($model, 'fecha_inicio')->textInput() ?>

    <?= $form->field($model, 'fecha_fin')->textInput() ?>

    <?= $form->field($model, 'memo')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
