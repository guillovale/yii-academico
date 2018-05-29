<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Carrera;
use app\models\Matricula;
use app\models\Periodolectivo;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

//$matriculas = Matricula::find()->where(['CIInfPer' => '0802252833'])->asArray()->all();
//$matriculas = ArrayHelper::getColumn(MallaEstudiante::find()->Where(['cedula' => $model->cedula])->all(), 'carrera');
//$carreras = ArrayHelper::getColumn(Carrera::find()->where(['idCarr'=> $matriculas])->all(), 'idCarr');
//$dataPost=ArrayHelper::map(Carrera::find()->Where(['culminacion' => 1, 'idCarr'=> $carreras ])->all(), 'idCarr', 'NombCarr');
$dataPost=ArrayHelper::map(Periodolectivo::find()
					->andWhere(['>','idper', 107 ])
					->orderBy(['idper' => SORT_DESC])->all(), 'idper', 'DescPerLec');
//echo var_dump($dataPost);
//exit;
?>

<div class="notassic-search">
	<div style="font-size:11px;">

    <?php $form = ActiveForm::begin([
        'action' => ['reporte_promedio'],
        'method' => 'get',
    ]); ?>

	<?php
	   // $cedula = Html::getInputId($model, 'CIInfPer');
		//$cedula = (isset($_GET['NotasSicSearch']['cedula']) ? $_GET['NotasSicSearch']['cedula'] : '');
		//$carrera = (isset($_GET['NotasSicSearch']['carrera']) ? $_GET['NotasSicSearch']['carrera'] : '');
		if (isset($_GET['MatriculaSearch']['idperiodo']))
			$model->idperiodo = $_GET['MatriculaSearch']['idperiodo'];
	?>

	<?php //echo $form->field($model, 'idperiodo')->textInput(['maxlength' => true]) ?>

	<?php echo $form->field($model, 'idperiodo')->dropDownList($dataPost, ['prompt'=>'Selecione una período']) ?>

    
    <div class="form-group">
        <?php echo Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
	<?php //if(!empty($cedula)) echo Html::a('Imprimir Notas', ['notaspdf', 'cedula' => $cedula, 'idCarr' => $carrera],['class' => 'btn btn-success', 'target'=>'_blank']) ?>
        
    </div>
 </div>
    <?php ActiveForm::end(); ?>

</div>


