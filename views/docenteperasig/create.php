<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Docenteperasig */

$this->title = 'Create Docenteperasig';
$this->params['breadcrumbs'][] = ['label' => 'Docenteperasigs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="docenteperasig-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
