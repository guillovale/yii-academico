<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExtensionMatricula */

$this->title = 'Crear extensión Matrícula';
$this->params['breadcrumbs'][] = ['label' => 'extensión matrícula', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-matricula-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
