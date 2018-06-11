<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExtensionMatriculasearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Extensión Matrícula';
$this->params['breadcrumbs'][] = $this->title;
?>

<div style="font-size:12px;">
<div class="extension-matricula-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?php if (Yii::$app->user->identity->idperfil == 'diracad' || Yii::$app->user->identity->idperfil == 'sa' 
					|| Yii::$app->user->identity->idperfil == 'dist' ) {
        		echo Html::a('Crear extensión matrícula', ['create'], ['class' => 'btn btn-success']) ;
			}
		?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'idper',
            'cedula',
        //    'fechain',
        //    'fechafin',
            //'idcarr',
		[
			'attribute'=>'fechain',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],
		[
			'attribute'=>'fechafin',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],
		[
			'attribute'=>'idcarr',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],
		['attribute'=>'nombreCarrera',
			'label'=>'Carrera',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		['attribute'=>'memorandum',
			//'label'=>'Carrera',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
			'filter'=>false,
			'enableSorting' => false,
		],
		['attribute'=>'exonerado',
			//'label'=>'Carrera',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
			'filter'=>false,
			'enableSorting' => false,
		],

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}'],
        ],
    ]); ?>

</div>
</div>
