<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\AbonoFactura;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AbonoFacturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documentos:';
$this->params['breadcrumbs'][] = ['label' => 'Detalle pagos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$suma = 0;#(float) $this->params['suma'];
$total = 0;#(float) $this->params['total'];
$saldo = $total - $suma;
?>
<div class="row">
	<h3><?= Html::encode($this->title) ?></h3>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'showFooter' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

			'periodo.DescPerLec',
			'id',
            'cedula',
			'nombreAlumno', 
			[
				'attribute'=>'fecha',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
			[
				'attribute'=>'tipo_documento',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
			[
				'attribute'=>'valor_matricula',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
    		[
				'attribute'=>'valor_credito',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
			[
				'attribute'=>'valor_otro',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
			[
				'attribute'=>'total',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			],
			[
				//'attribute'=>'idMatricula',
				'label'=>'Abono',
				'format'=>'text',//raw, html
				'content'=>function($data){
					return $data->sumaAbono();
			    }
			 ],
			#[
			#	'attribute' => 'valor',
			#	'footer' => $suma,//AbonoFactura::getTotal($dataProvider->models, 'valor'),       
			#],
            //'valor',
            // 'usuario',

            ['class' => 'yii\grid\ActionColumn',
				'template' => '{pago} {print}',

				'buttons' => [
		        'pago' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-usd"></span>', $url, [
		                        'title' => Yii::t('app', 'crear pago'),
		            ]);
		        },

		        'print' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-print"></span>', $url, [
		                        'title' => Yii::t('app', 'imprimir'),
		            ]);
		        },
		        'delete' => function($url, $model){
            		return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
						    'class' => '',
						    'data' => [
						        'confirm' => 'Está seguro de borrar el registro ?',
						        'method' => 'post',
						    ],
           		 	]);
       			 }

         	 ],

				'urlCreator' => function ($action, $model, $key, $index) {
		    	if ($action === 'pago') {
		    		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
					$url = Url::to(['abonofactura/create', 'idfactura'=>$model->id], true);
		    		        return $url;
		    		}

				},

			],
        ],
    ]); ?>

</div>
