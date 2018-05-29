<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ingreso */

$this->title = 'Carga Masiva';
$this->params['breadcrumbs'][] = ['label' => 'Ingreso', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingreso-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_upload', [
		    'model' => $model,
		]) ?>

</div>
