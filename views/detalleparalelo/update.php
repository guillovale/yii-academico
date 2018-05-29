<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Detalleparalelo */

$this->title = 'Update Detalleparalelo: ' . ' ' . $model->iddetalleparalelo;
$this->params['breadcrumbs'][] = ['label' => 'Detalleparalelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->iddetalleparalelo, 'url' => ['view', 'id' => $model->iddetalleparalelo]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="detalleparalelo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
