<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\NotasDetalle */

$this->title = 'Create Notas Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Notas Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notas-detalle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
