<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MallaCarrera */

$this->title = 'Actualizar Malla: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Malla Carreras', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="malla-carrera-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
