<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Carrera;
use app\models\Matricula;
use app\models\Ingreso;
use app\models\MallaEstudiante;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

//$matriculas = Matricula::find()->where(['CIInfPer' => '0802252833'])->asArray()->all();
#$matriculas = ArrayHelper::getColumn(MallaEstudiante::find()->Where(['cedula' => $model->CIInfPer])->all(), 'carrera');
$matriculas = ArrayHelper::getColumn(Ingreso::find()
			->Where(['CIInfPer' => $model->CIInfPer])
			->groupBy(['idcarr'])
			->orderBy(['fecha'=> 'DESC'])
			->all(), 'idcarr');
$carreras = ArrayHelper::getColumn(Carrera::find()->where(['idCarr'=> $matriculas])->all(), 'idCarr');
$dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1, 'idCarr'=> $carreras ])->all(), 'idCarr', 'NombCarr');
$identity = Yii::$app->user->identity;
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['IN','idcarr', $carreras])->all(), 'idCarr', 'NombCarr');
//echo var_dump($dataPost);
//exit;
?>

<div class="notasalumnoasignatura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

	<?php
	   // $cedula = Html::getInputId($model, 'CIInfPer');
		$cedula = (isset($_GET['NotasalumnoasignaturaSearch']['CIInfPer']) ? $_GET['NotasalumnoasignaturaSearch']['CIInfPer'] : '');
		$carrera = (isset($_GET['NotasalumnoasignaturaSearch']['carrera']) ? $_GET['NotasalumnoasignaturaSearch']['carrera'] : '');
	?>

	<?= $form->field($model, 'CIInfPer')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'carrera')->dropDownList($dataPost, ['prompt'=>'Elija una carrera']) ?>

    

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
	<?php if(!empty($cedula)) {
		echo Html::a('Imprimir Notas', ['notaspdf', 'cedula' => $cedula, 
			'idCarr' => $carrera],['class' => 'btn btn-success', 'target'=>'_blank']); 
		}
	?>
	<?php if(!empty($cedula) && !empty($carrera)) echo Html::a('Imprimir Malla', 
		['mallapdf', 'cedula' => $cedula, 'idCarr' => $carrera],['class' => 'btn btn-warning', 'target'=>'_blank']) ?>

	<?php //quitar &&Yii::$app->user->identity->LoginUsu=='0800428849' para crear nota a todos ?>
	<?php if( !empty($cedula)  && (isset($identity))) {
		if ($identity->idperfil == 'diracad')
			echo Html::a('Crear nota', ['create', 'cedula' => $cedula],['class' => 'btn btn-info']);
	}
	?>

        
    </div>

    <?php ActiveForm::end(); ?>

</div>


