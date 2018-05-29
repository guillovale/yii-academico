<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Carrera;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */
/* @var $form yii\widgets\ActiveForm */

$usuario=Yii::$app->user->identity;
if ($usuario) {
	$rep = str_replace("'", "", explode(',',$usuario->idcarr));
	if(!in_array('%', $rep)) 
		$dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1, 'idCarr' => $rep])
											->orderBy(['NombCarr'=>SORT_ASC])
											->all(), 'idCarr', 'NombCarr');

	else $dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1])
												->orderBy(['NombCarr'=>SORT_ASC])
												->all(), 'idCarr', 'NombCarr');
}
//echo var_dump($rep);exit;
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['StatusCarr' => 1, 'idCarr' => $rep])->all(), 'idCarr', 'NombCarr');
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1])
//						->orderBy(['NombCarr'=>SORT_ASC])
//						->all(), 'idCarr', 'NombCarr');
// $model->fecha = getdate("Y-m-d H:i:s");
//$model->fecha = date('Y-m-d H:i:s');
?>

<div class="malla-estudiante-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cedula')->textInput(['maxlength' => true]) ?>

    	<?= $form->field($model, 'carrera')->dropDownList($dataPost, ['prompt'=>'Selecione una carrera']) ?>

    <?= $form->field($model, 'anio_habilitacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha')->textInput(['readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
