<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\MallaRequisito */
/* @var $form yii\widgets\ActiveForm */
$carreras = $this->params['carreras'];
$nivel = array('1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
$tipo = array('PR'=>'PR','CO'=>'CO');
?>

<div class="malla-requisito-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'carrera')->dropDownList($carreras, ['id'=>'idCarr',
					'prompt' => '',
					'onchange'=>'$.post( "'.Url::toRoute('mallarequisito/listamalla?id=').
								'"+$(this).val(),
					function( data ){
						//alert(data);
						$("select#malla").html( data );
					});',
				]) ?>
	<?= $form->field($model, 'malla')->dropDownList(array(), ['id'=>'malla',
				'prompt'=>'',
					'onchange'=>'$.post( "'.Url::toRoute('mallarequisito/setearnivel').
						'",
					function( data ){
						$("select#nivel").html( data );
					});']) ?>
	<?= $form->field($model, 'nivel')->dropDownList($nivel, 
			['id'=>'nivel',
				'prompt'=>'',
					'onchange'=>'$.post( "'.Url::toRoute('mallarequisito/listasignatura?nivel=').
						'"+$(this).val()+";"+$("#malla").val(),
					function( data ){
						$("select#idmalla").html( data );
					});'
				]) ?>

    <?= $form->field($model, 'idmalla')->dropDownList(array(), ['id'=>'idmalla']) ?>
	<?= $form->field($model, 'nivel_prerequisito')->dropDownList($nivel, 
			['id'=>'nivel2',
				'prompt'=>'',
					'onchange'=>'$.post( "'.Url::toRoute('mallarequisito/listasignatura?nivel=').
						'"+$(this).val()+";"+$("#malla").val(),
					function( data ){
						$("select#idmallarequisito").html( data );
					});'
				]) ?>
    <?= $form->field($model, 'idmallarequisito')->dropDownList(array(), ['id'=>'idmallarequisito']) ?>

    <?= $form->field($model, 'tipo')->dropDownList($tipo) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
