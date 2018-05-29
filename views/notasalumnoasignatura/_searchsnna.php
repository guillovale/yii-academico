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
        'action' => ['snna'],
        'method' => 'get',
    ]); ?>
	<?php $items = \yii\helpers\ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=>SORT_DESC])->all(), 
			'idper', 'DescPerLec'); ?>

    <?= $form->field($model, 'periodo')->dropDownList($this->params['periodos'], ['id'=>'periodo','prompt' => ''])->hint('Período') ?>

    <?= $form->field($model, 'carrera')->dropDownList($this->params['carreras'], ['id'=>'idCarr','prompt' => ''])->hint('Carrera')?>

    

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary'])?>
       
    </div>

    <?php ActiveForm::end(); ?>

</div>
