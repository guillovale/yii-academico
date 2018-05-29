<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Equivalencia */

$this->title = $model->idequivalencia;
$this->params['breadcrumbs'][] = ['label' => 'Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equivalencia-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'idequivalencia' => $model->idequivalencia], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'idequivalencia' => $model->idequivalencia], [
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
            'idequivalencia',
            'asignatura',
            'equivalencia',
            'fecha',
            'usuario',
        ],
    ]) ?>

</div>
