<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */

$this->title = $model->getNombreEstudianate();
$this->params['breadcrumbs'][] = ['label' => 'Malla Estudiantes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="malla-estudiante-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->id_malla], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_malla], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_malla',
            'cedula',
            //'carrera',
		
		[
			'attribute'=>'carrera',
			'label'=>'Carrera',
			'format'=>'text',//raw, html
			'value' => $model->getNombreCarrera(),
	        ],

            'anio_habilitacion',
            'fecha',
        ],
    ]) ?>

</div>
