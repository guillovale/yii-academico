<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Informacionpersonal */

$this->title = 'Crear Alumno';
$this->params['breadcrumbs'][] = ['label' => 'Alumno', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacionpersonal-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
