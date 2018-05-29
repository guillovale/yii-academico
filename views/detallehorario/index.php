<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleHorarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle Horarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detalle-horario-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Detalle Horario', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idhorario',
            'idcurso',
            'dia',
            'hora_inicio',
            // 'hora_fin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
