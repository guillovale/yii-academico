<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\models\Informacionpersonal;

/* @var $this yii\web\View */
/* @var $model app\models\Ingreso */

$this->title = 'Crear Ingreso';
$this->params['breadcrumbs'][] = ['label' => 'Ingresos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-xs-4">

		<h4><?= Html::encode($this->title) ?></h4>

		<?= $this->render('_form', [
		    'model' => $model,
		]) ?>
	</div>

	<div class="col-xs-8">
		<?php
		//$cedula = $_GET['CIInfPer'];
		$cedula = isset($_GET['CIInfPer']) ? $_GET['CIInfPer'] : 'ok';
            Modal::begin([
                'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-plus"></i> Ver alumno',
                    'class' => 'btn btn-success'
                ],
                'closeButton' => [
                  'label' => 'Cerrar',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-lg',
            ]);
			echo 'Say hello...';
           // $myModel =Informacionpersonal::find()
			//		->where(['CIInfPer'=> $cedula]);
            //echo $this->render('/informacionpersonal/view', ['model' => $myModel, 'id'=>$cedula]);
			echo $cedula;
            Modal::end();
        ?>
	</div>

</div>
