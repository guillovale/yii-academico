<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CursoOfertado */

$this->title = 'Actualizar Curso Ofertado: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Curso Ofertados', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="curso-ofertado-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
