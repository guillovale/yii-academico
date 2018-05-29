<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Asignatura;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Equivalencias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="equivalencia-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Crear Equivalencia', ['create', 'idAsig' => '000'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'idequivalencia',
            //'asignatura',

		[
			'attribute'=>'asignatura',
			'label'=>'Asignatura',
			'format'=>'text',//raw, html
			//'filter'=>true,
	        ],

		[
			'label'=>'Asignatura',
			'format'=>'text',//raw, html
			'content'=>function($data){
				$nombre = '';
				$modelo = Asignatura::findOne($data->asignatura);
				if(!empty($modelo)) $nombre = $modelo->NombAsig;
				return $nombre;
	                }
		],
            'equivalencia',
		[
			'label'=>'Asignatura',
			'format'=>'text',//raw, html
			'content'=>function($data){
				$nombre = '';
				$modelo = Asignatura::findOne($data->equivalencia);
				if(!empty($modelo)) $nombre = $modelo->NombAsig;
				return $nombre;
	                }
		],
            'fecha',
            'usuario',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
