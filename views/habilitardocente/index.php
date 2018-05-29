<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\HabilitarDocenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Habilitar Docentes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="habilitar-docente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Habilitar Docente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'iddocenteperasig',
            'hemisemestre',
            'componente',
            'fechaini',
            // 'fechafin',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
