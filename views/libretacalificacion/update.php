<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\LibretaCalificacion */

$this->title = 'Lista alumnos';
$hemi = $this->params['hemi'];
$componente = $this->params['componente'];
$docente = $this->params['docente'];
$dato = $this->params['dato'];
//echo var_dump($model); exit;
$this->params['breadcrumbs'][] = ['label' => 'Lista componentes', 'url' => ['libretacalificacion/index', 
									'idcurso'=> $modelLibreta->idcurso]];
$this->params['breadcrumbs'][] = $this->title;
//$model = $dataProvider->getModels();
//echo var_dump($alumnos); exit;
?>

<div class="col-xs-4">
	<h3><?= Html::encode($this->title) ?></h3>
	<address>
		<b>Hemisemestre: <?= $hemi ?></b><br>
		<b>Componente: <?= $componente ?> </b><br>
		Docente: <?= $this->params['docente'] ?> <br>
		<?= $this->params['dato'] ?>
	</address>

    <?php $form = ActiveForm::begin(); ?>
	
	<fieldset>
        	<legend></legend>
		<?= $form->field($model, 'iddetallematricula')->dropDownList($alumnos, ['prompt'=>'Elija...']) ?>
	
	  	
		<?= $form->field($model, 'nota')->textInput(); ?>

	</fieldset>
	<div class="form-group">
        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
    </div>
</div>

<div class="col-xs-8">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			[
				//'attribute'=>'idMatricula',
				'label'=>'Cédula',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return ($data->detallematricula)?$data->detallematricula->factura->cedula:'';
	                }
	        ],
            [
				//'attribute'=>'idMatricula',
				'label'=>'Alumno',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return ($data->detallematricula)?$data->detallematricula->factura->getNombreAlumno():'';
	                }
	        ],
	/*
			[
				//'attribute'=>'idMatricula',
				'label'=>'Asignatura',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->detallematricula->asignatura;
	                }
	        ],
			[
				//'attribute'=>'idMatricula',
				'label'=>'Nivel',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->detallematricula->nivel;
	                }
	        ],
			[
				//'attribute'=>'idMatricula',
				'label'=>'Paralelo',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->detallematricula->paralelo;
	                }
	        ],
	*/
			//	'idlibreta',
			//	'iddetallematricula',
		    'nota',
	        ],
    ]) ?>

</div>
