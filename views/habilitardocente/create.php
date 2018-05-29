<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\HabilitarDocente */

$this->title = 'Create Habilitar Docente';
$this->params['breadcrumbs'][] = ['label' => 'Habilitar Docentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habilitar-docente-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
