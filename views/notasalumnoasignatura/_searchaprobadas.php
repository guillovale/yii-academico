<style>
@media print {
  #buscar {
    display: none;
  }
}
</style>

<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\models\Periodolectivo;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\DetalleMatriculaSearch */
/* @var $form yii\widgets\ActiveForm */

//echo var_dump($model); exit;
//if (isset($_GET["DetalleMatriculaSearch"]["periodo"]))
	//$model->periodo = ($_GET["DetalleMatriculaSearch"]["periodo"]?$_GET["DetalleMatriculaSearch"]["periodo"]:'');
//if (isset($_GET["DetalleMatriculaSearch"]["nivel"]))
//	$model->nivel = ($_GET["DetalleMatriculaSearch"]["nivel"]?$_GET["DetalleMatriculaSearch"]["nivel"]:'');
//if (isset($_GET["DetalleMatriculaSearch"]["carrera"]))
//$model->carrera = ($_GET["DetalleMatriculaSearch"]["carrera"]?$_GET["DetalleMatriculaSearch"]["carrera"]:'');
//if (isset($_GET["DetalleMatriculaSearch"]["paralelo"]))
//$model->paralelo = ($_GET["DetalleMatriculaSearch"]["paralelo"]?$_GET["DetalleMatriculaSearch"]["paralelo"]:'');
//if (isset($_GET["DetalleMatriculaSearch"]["idasig"]))
//$model->idasig = ($_GET["DetalleMatriculaSearch"]["idasig"]?$_GET["DetalleMatriculaSearch"]["idasig"]:'');

?>

<div class="detalle-matricula-search">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['veraprobados'],
        'method' => 'get',
    ]); ?>
	<?php $items = \yii\helpers\ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=>SORT_DESC])->all(), 
			'idper', 'DescPerLec'); ?>

    <?= $form->field($model, 'periodo')->dropDownList($this->params['periodos'], ['id'=>'periodo','prompt' => ''])->hint('Período') ?>

    <?= $form->field($model, 'carrera')->dropDownList($this->params['carreras'], ['id'=>'idCarr','prompt' => ''])->hint('Carrera')?>

    <?= $form->field($model, 'nivel')->dropDownList($this->params['nivel'], 
				[	'id'=>'nivel',
					'prompt' => '',
					'onchange'=>'$.post( "'.Url::toRoute('detallematricula/listaasignatura?id=').
								'"+$(this).val()+";"+$("#periodo").val()+";"+$("#idCarr").val(),
					function( data ){
						//alert(data);
						$("select#idAsig").html( data );
					});',
				])->hint('Nivel') ?>

    <?= $form->field($model, 'idAsig')->dropDownList(array(), 
				[	'id'=>'idAsig',
					'prompt' => '' ,
					'onchange'=>'$.post( "'.Url::toRoute('detallematricula/listaparalelo?id=').
								'"+$("#periodo").val()+";"+$("#idCarr").val()+";"+$("#nivel").val()+";"+$(this).val(),
					function( data ){
						//alert(data);
						$("select#paralelo").html( data );
					});',
					

				])->hint('Asignatura') ?>

	<?= $form->field($model, 'idMatricula')->dropDownList(array(), ['id'=>'paralelo',
					'prompt' => '' ])->hint('Paralelo') ?>

	

    <?php // echo $form->field($model, 'credito') ?>

    <?php // echo $form->field($model, 'vrepite') ?>

    <?php // echo $form->field($model, 'costo') ?>

    <?php // echo $form->field($model, 'horario') ?>

    <?php // echo $form->field($model, 'fecha') ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary'])?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
