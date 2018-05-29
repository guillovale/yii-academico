<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMallaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$malla = $searchModel->malla->detalle;

$this->title = 'Asignaturas en: '. $malla;
$this->params['breadcrumbs'][] = ['label' => 'Malla:' . $searchModel->idmalla, 
			'url' => ['mallacarrera/index', 'MallaCarreraSearch[id]'=> $searchModel->idmalla]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detalle-malla-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a('Agregar asignatura a malla', ['create', 'idmalla'=> $searchModel->idmalla], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idmalla',
			'malla.carrera.NombCarr',
            'idasignatura',
			'asignatura.NombAsig',
            'nivel',
            'credito',
			'peso',
            // 'caracter',
            'estado',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>
