<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Informacionpersonal */

$this->title = $model->CIInfPer;
$this->params['breadcrumbs'][] = ['label' => 'Alumno', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="informacionpersonal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->CIInfPer], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'CIInfPer',
            'cedula_pasaporte',
            'TipoDocInfPer',
            'ApellInfPer',
            'ApellMatInfPer',
            'NombInfPer',
            'NacionalidadPer',
            'EtniaPer',
            'FechNacimPer',
            'LugarNacimientoPer',
            'GeneroPer',
            'EstadoCivilPer',
            'CiudadPer',
            'DirecDomicilioPer',
            'Telf1InfPer',
            'CelularInfPer',
            'TipoInfPer',
            'statusper',
            'mailPer',
            'mailInst',
            'GrupoSanguineo',
            'tipo_discapacidad',
            'carnet_conadis',
            'num_carnet_conadis',
            'porcentaje_discapacidad',
            //'fotografia',
            //'codigo_dactilar',
            //'hd_posicion',
            //'huella_dactilar',
            'ultima_actualizacion',
            //'codigo_verificacion',
        ],
    ]) ?>

</div>
