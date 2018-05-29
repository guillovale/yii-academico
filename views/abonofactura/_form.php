<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AbonoFactura */
/* @var $form yii\widgets\ActiveForm */
$id = array('id'=> $this->params['factura']);
?>

<div class="abono-factura-form">

    <?php $form = ActiveForm::begin(); ?>

   
    <?= $form->field($model, 'fecha')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'documento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valor')->textInput() ?>

   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', 
			['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	<?php print \yii\helpers\Html::a( 'Cancelar', ['abonofactura/abonar', 'FacturaSearch'=>$id], ['class' =>'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
