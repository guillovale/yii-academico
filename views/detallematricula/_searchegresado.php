<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
#use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\models\Periodolectivo;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

$dataPost=ArrayHelper::map(Periodolectivo::find()
					->andWhere(['>','idper', 107 ])
					->orderBy(['idper' => SORT_DESC])->all(), 'idper', 'DescPerLec');
$carrera = $this->params['carrera'];
?>

<div class="notassic-search">
	<div style="font-size:11px;">

		<?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['reporte_egresados'],
        'method' => 'get',
    ]); ?>
	<?php $items = \yii\helpers\ArrayHelper::map(Periodolectivo::find()->orderBy(['idper'=>SORT_DESC])->all(), 
			'idper', 'DescPerLec'); ?>

    <?= $form->field($model, 'idfactura')->dropDownList($dataPost, ['id'=>'periodo','prompt' => ''])->hint('Período') ?>

    <?= $form->field($model, 'idcarr')->dropDownList($carrera, ['id'=>'idCarr','prompt' => ''])->hint('Carrera')?>

   	

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


