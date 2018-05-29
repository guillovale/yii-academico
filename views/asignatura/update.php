<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Asignatura */

$this->title = 'Actualizar Asignatura: ' . ' ' . $model->IdAsig;
$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->IdAsig, 'url' => ['view', 'id' => $model->IdAsig]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asignatura-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
