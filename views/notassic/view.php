<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\NotasSic */

$this->title = $model->codigo;
$this->params['breadcrumbs'][] = ['label' => 'Notas Sics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notas-sic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'codigo' => $model->codigo, 'cedula' => $model->cedula], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'codigo' => $model->codigo, 'cedula' => $model->cedula], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idcarrera',
            'carrera',
            'cedula',
            'codigo',
            'asignatura',
            'calificacion',
            'estado',
            'fecha',
            'nivel',
        ],
    ]) ?>

</div>
