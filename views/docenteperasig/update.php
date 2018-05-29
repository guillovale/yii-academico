<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Docenteperasig */

$this->title = 'Update Docenteperasig: ' . ' ' . $model->dpa_id;
$this->params['breadcrumbs'][] = ['label' => 'Docenteperasigs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dpa_id, 'url' => ['view', 'id' => $model->dpa_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="docenteperasig-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
