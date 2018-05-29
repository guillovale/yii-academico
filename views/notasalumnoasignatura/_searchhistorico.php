<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
#use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\models\Carrera;
use app\models\Matricula;
use app\models\MallaEstudiante;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

//$matriculas = Matricula::find()->where(['CIInfPer' => '0802252833'])->asArray()->all();
$matriculas = ArrayHelper::getColumn(MallaEstudiante::find()->Where(['cedula' => $model->CIInfPer])->all(), 'carrera');
$carreras = ArrayHelper::getColumn(Carrera::find()->where(['idCarr'=> $matriculas])->all(), 'idCarr');
$dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1, 'idCarr'=> $carreras ])->all(), 'idCarr', 'NombCarr');
$identity = Yii::$app->user->identity;
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['IN','idcarr', $carreras])->all(), 'idCarr', 'NombCarr');
//echo var_dump($dataPost);
//exit;
$alumno = '';
?>

<div class="notasalumnoasignatura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['historico'],
        'method' => 'get',
		'layout' => 'inline',
    ]); ?>

	<?php
	   // $cedula = Html::getInputId($model, 'CIInfPer');
		$cedula = (isset($_GET['NotasalumnoasignaturaSearch']['CIInfPer']) ? $_GET['NotasalumnoasignaturaSearch']['CIInfPer'] : '');
		$carrera = (isset($_GET['NotasalumnoasignaturaSearch']['carrera']) ? $_GET['NotasalumnoasignaturaSearch']['carrera'] : '');
	?>

	<?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true])->hint('Cédula') ?>

	<?= $form->field($model, 'carrera')->dropDownList($dataPost, ['prompt'=>'Selecione una carrera'])->hint('Carrera') ?>

    

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
	
	        
    </div>
	<br>
	<?php echo $alumno ?>
    <?php ActiveForm::end(); ?>

</div>


