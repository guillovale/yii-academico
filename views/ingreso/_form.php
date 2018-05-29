<script type="text/javascript">
        $(function() {
            $('#ingreso-ciinfper').on('keyup', function() {
                
				alert('0k');
            });
        });
</script>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Ingreso */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="ingreso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idper')->dropDownList($this->params['periodos'], ['prompt'=>'']) ?>

    <?= $form->field($model, 'idcarr')->dropDownList($this->params['carrera'], ['id'=>'idCarr',
					'prompt' => '',
					'onchange'=>'$.post( "'.Url::toRoute('ingreso/listamalla?id=').
								'"+$(this).val(),
					function( data ){
						//alert(data);
						$("select#malla").html( data );
					});',
				]) ?>

    <?= $form->field($model, 'idmalla')->dropDownList($this->params['mallas'], ['id'=>'malla']) ?>

    <?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'tipo_ingreso')->dropDownList($this->params['admision'], ['prompt'=>'']) ?>
	
    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'usuario')->textInput(['readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
