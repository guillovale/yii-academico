<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\LibretaCalificacion */
/* @var $form yii\widgets\ActiveForm */
$componente =  $this->params['componente'];
$parametro =  $this->params['parametro'];
$periodo =  $this->params['periodo'];
?>

<div class="libreta-calificacion-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?php // echo $form->field($model, 'idper')->dropDownList($periodo, ['prompt'=>'Elija...']) ?>

    <?= $form->field($model, 'hemisemestre')->radioList([0 => 'Recuperación o SNNA',1 => 'Primero', 2 => 'Segundo']) ?>

    <?= $form->field($model, 'idparametro')->dropDownList($parametro, ['prompt'=>'Elija...',
						
							'onchange'=>'
		                $.get( "'.Url::toRoute('/libretacalificacion/listar').'", { id: $(this).val() } )
		                    .done(function( data ) {
		                        $( "#'.Html::getInputId($model, 'idcomponente').'" ).html( data );
		                    }
		                );
		            '

						]) ?>

    <?= $form->field($model, 'idcomponente')->dropDownList($componente, ['prompt'=>'Elija...']) ?>

    <?= $form->field($model, 'tema')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
       <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
