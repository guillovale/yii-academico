<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ExtensionMatricula */

$this->title = 'Actualizar Extensión Matrícula: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Extension Matriculas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="extension-matricula-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
