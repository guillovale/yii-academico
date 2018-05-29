<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Detalleparalelo */

$this->title = $model->iddetalleparalelo;
$this->params['breadcrumbs'][] = ['label' => 'Detalleparalelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detalleparalelo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->iddetalleparalelo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->iddetalleparalelo], [
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
            'iddetalleparalelo',
            'idparalelo',
            'nivel',
            'idper',
            'idcarr',
            'cupo',
            'habilitado',
            'idasig',
        ],
    ]) ?>

</div>
