<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Informacionpersonal */

$this->title = 'Actualizar Alumno: ' . ' ' . $model->CIInfPer;
$this->params['breadcrumbs'][] = ['label' => 'Alumno', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->CIInfPer, 'url' => ['view', 'id' => $model->CIInfPer]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="informacionpersonal-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
