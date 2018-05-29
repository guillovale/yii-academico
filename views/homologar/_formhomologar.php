<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$usuario = Yii::$app->user->identity;
if ($usuario->idperfil == 'snna'){
	$nivel = array('0'=>'0');
}
else {
	$nivel = array('1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
}
$periodos = $this->params['periodos'];

$observacion = array('HOMOLOGADA'=>'HOMOLOGADA','CONVALIDADA'=>'CONVALIDADA');
$modelnota->observacion_efa = $this->params['memo'];
$malla = $this->params['malla'];
$idmalla = $this->params['idmalla'];
?>

<div class="notasalumnoasignatura-form">

	<?php $form = ActiveForm::begin(); ?>

		<?= $form->field($modelnota, 'idPer')->dropDownList($periodos, ['prompt'=>'']) ?>
		<?= $form->field($modelnota, 'CIInfPer')->hiddenInput()->label(false)//->textInput(['readonly' => true]); ?>
		<?= $form->field($modelnota, 'observacion_efa')->textInput(['maxlength' => true])->label('Memorando'); ?>
		
		
		

		<?= $form->field($modeldetalle, 'idcarr')->hiddenInput()->label(false)//->textInput(['readonly' => true])
			/*
			->dropDownList($dataCarrera, 
			['id'=>'idcarr',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('homologar/listamalla?idcarr=').'"+$(this).val(),
					function( data ){
						$("select#idMc").html( data );
					});',
			])*/; ?>

		<?= $form->field($modelnota, 'idMc')->hiddenInput()->label(false)//->textInput(['readonly' => true])
			/*
			->dropDownList(array(), 
			['id'=>'idMc',
			'readonly' => true,
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('homologar/listavacia').'",
					function( data ){
						$("select#nivel").html( data );
					});',
			])*/;

		?>

		<?= $form->field($modeldetalle, 'nivel')->dropDownList($nivel, 
			['id'=>'nivel',
			'prompt'=>'', 
			'onchange'=>'$.post( "'.Url::toRoute('homologar/listasignaturas?nivel=').
						'"+$(this).val()+";"+$( "#'.Html::getInputId($modelnota, 'idMc').'").val(),
					function( data ){
						//alert(data);
						$("select#idAsig").html( data );
					});'
			]); ?>


    <?= $form->field($modelnota, 'idAsig')->dropDownList(array(), 
			['id'=>'idAsig'],
			['prompt'=>'']) ?>


    <?= $form->field($modelnota, 'CalifFinal')->textInput(['maxlength' => true])->input('CalifFinal', ['placeholder' => "Entre 7.0 y 10.0"]) ?>

    <?= $form->field($modelnota, 'observacion')->dropDownList($observacion, ['prompt'=>'']) ?>

    <div class="form-group">
        <?= Html::submitButton($modelnota->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $modelnota->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', ['/ingreso/index'], ['class'=>'btn btn-warning']) ?>
    </div>

   

    <?php ActiveForm::end(); ?>

</div>
