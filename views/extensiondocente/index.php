<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExtensionDocenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Extensión Docentes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-docente-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear extensión docente', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            #'id',
            'idcurso',
			'curso.iddocente',
			'curso.nombreDocente',
            'fecha_inicio',
            'fecha_fin',
            'memo:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
