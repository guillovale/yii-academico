<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Notasalumnoasignatura */

$this->title = 'Actualizar notas asignatura: ' . ' ' . $model->idAsig;
$this->params['breadcrumbs'][] = ['label' => 'Notasalumnoasignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idnaa, 'url' => ['view', 'id' => $model->idnaa]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notasalumnoasignatura-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_formnota', [
        'model' => $model,
    ]) ?>

</div>
