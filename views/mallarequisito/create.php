<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MallaRequisito */

$this->title = 'Crear malla requisito';
$this->params['breadcrumbs'][] = ['label' => 'Malla Requisitos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-requisito-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
