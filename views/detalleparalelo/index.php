<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleparaleloSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalleparalelos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detalleparalelo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Detalleparalelo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'iddetalleparalelo',
            'idparalelo',
            'nivel',
            //'idper',
            'idcarr',
            'cupo',
            'habilitado',
            'idasig',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
