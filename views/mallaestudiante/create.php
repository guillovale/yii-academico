<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */

$this->title = 'Crear malla estudiante';
$this->params['breadcrumbs'][] = ['label' => 'Malla Estudiantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-estudiante-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
