<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */

$this->title = 'Actualizar malla estudiante: ' . ' ' . $model->getNombreEstudianate();
$this->params['breadcrumbs'][] = ['label' => 'Malla Estudiantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_malla, 'url' => ['view', 'id' => $model->id_malla]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="malla-estudiante-update">

  
	<h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
