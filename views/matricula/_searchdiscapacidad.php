<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Periodolectivo;

/* @var $this yii\web\View */
/* @var $model app\models\NotasalumnoasignaturaSearch */
/* @var $form yii\widgets\ActiveForm */

$dataPost=ArrayHelper::map(Periodolectivo::find()
					->andWhere(['>','idper', 107 ])
					->orderBy(['idper' => SORT_DESC])->all(), 'idper', 'DescPerLec');
?>

<div class="notassic-search">
	<div style="font-size:11px;">

		<?php $form = ActiveForm::begin([
		    'action' => ['reporte_discapacidad'],
		    'method' => 'get',
		]); ?>

		<?php
		  
			if (isset($_GET['MatriculaSearch']['idperiodo']))
				$model->idperiodo = $_GET['MatriculaSearch']['idperiodo'];
		?>


		<?php echo $form->field($model, 'idperiodo')->dropDownList($dataPost, ['prompt'=>'Selecione una período']) ?>

    
    <div class="form-group">
        <?php echo Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        
    </div>
 </div>
    <?php ActiveForm::end(); ?>

</div>


