<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MallaCarrera */

$this->title = 'Crear Malla';
$this->params['breadcrumbs'][] = ['label' => 'Malla Carreras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-carrera-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
