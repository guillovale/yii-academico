<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Factura */

$this->title = 'Actualizar Documente: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Documento', 'url' => ['index', 'FacturaSearch[cedula]'=> $model->cedula ]];
#$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Actualizar';
?>
<div class="factura-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
