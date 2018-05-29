<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ExtensionDocente */

$this->title = 'Crear Extensión Docente';
$this->params['breadcrumbs'][] = ['label' => 'Extensión Docentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-docente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
