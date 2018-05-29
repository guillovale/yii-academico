<?php

use yii\helpers\Html;
use app\models\Informacionpersonal;
/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */
$this->title = 'Crear nota estudiante:';
//$url = 'index/NotasalumnoasignaturaSearch'.'['.$this->params['cedula'].']';
//echo var_dump($_GET['NotasalumnoasignaturaSearch']); exit;
$this->params['breadcrumbs'][] = ['label' => 'Calificaciones', 
			'url' => ['index', 'NotasalumnoasignaturaSearch[CIInfPer]'=>$this->params['cedula']]];
$this->params['breadcrumbs'][] = $this->title;
$nombre = '';
if (!empty($model)) {
	$alumno = Informacionpersonal::find()->where(['CIInfPer'=>$model->CIInfPer])->one();
	if (!empty($alumno)) $nombre =  $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' . $alumno->NombInfPer;

}

//$this->title = 'Crear nota estudiante: '. $nombre;

?>
<div class="malla-estudiante-create">

    <h4><?= Html::encode($nombre) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
	'modelmatricula' => $modelmatricula,
    ]) ?>

</div>

