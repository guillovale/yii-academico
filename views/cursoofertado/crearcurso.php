<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\models\Periodolectivo;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DetalleMatriculaSearch */
/* @var $form yii\widgets\ActiveForm */

#echo var_dump($searchModel); exit;

$this->title = 'Crear Distributivo';
$this->params['breadcrumbs'][] = ['label' => 'Curso Ofertado', 
			'url' => ['index'] ]; 
$this->params['breadcrumbs'][] = $this->title;
$carreras = $this->params['carreras'];
$paralelos = $this->params['paralelos'];
$nivel = array('0'=>'0', '1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');

?>

<div class="detalle-matricula-search">
	<h3><?= Html::encode($this->title) ?></h3>
    <?php $form = ActiveForm::begin([
        //'layout' => 'inline',
        'action' => ['crearcurso'],
        'method' => 'get',
    ]); ?>
	
   <?= $form->field($model, 'idcarr')->dropDownList($carreras, ['id'=>'idCarr',
					'prompt' => '',
					'onchange'=>'$.post( "'.Url::toRoute('cursoofertado/listamalla?id=').
								'"+$(this).val(),
					function( data ){
						//alert(data);
						$("select#malla").html( data );
					});',
				]) ?>

    <?= $form->field($model, 'idmalla')->dropDownList(array(), ['id'=>'malla',
				'prompt'=>'',
					'onchange'=>'$.post( "'.Url::toRoute('cursoofertado/setearnivel').
						'",
					function( data ){
						$("select#nivel").html( data );
					});']) ?>

	<?= $form->field($model, 'nivel')->dropDownList($nivel, 
			['id'=>'nivel',
				'prompt'=>'',
					'onchange'=>'$.post( "'.Url::toRoute('cursoofertado/listasignatura?nivel=').
						'"+$(this).val()+";"+$("#malla").val(),
					function( data ){
						$("select#idasig").html( data );
					});'
				]) ?>


    <?= $form->field($model, 'iddetallemalla')->dropDownList(array(), 
			['id'=>'idasig'],
			['prompt'=>'']) ?>

    

   

	<?= $form->field($model, 'paralelo')->dropDownList($paralelos, ['id'=>'paralelo',
					'prompt' => '' ]) ?>

	

    <?php echo $form->field($model, 'iddocente') ?>

    <?php echo $form->field($model, 'cupo') ?>
	<?php echo $form->field($model, 'fecha_inicio')->textInput() ?>
	<?php echo $form->field($model, 'fecha_fin')->textInput() ?>

    <?php // echo $form->field($model, 'costo') ?>

    <?php // echo $form->field($model, 'horario') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <div class="form-group">
        <?= Html::submitButton('Crear',['class' => 'btn btn-primary'])?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
