<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Equivalencia */

$this->title = 'Crear Equivalencia';
$this->params['breadcrumbs'][] = ['label' => 'Equivalencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equivalencia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
