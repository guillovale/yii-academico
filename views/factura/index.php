<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\AbonoFactura;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FacturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Documento matrícula';
$this->params['breadcrumbs'][] = $this->title;
$usuario = Yii::$app->user->identity;
$template = '{ver} {cmatricula} {cpromocion}';
if ($usuario) {
	if ($usuario->idperfil == 'sa') 
		$template = '{ver} {update} {cmatricula} {cpromocion}';
}
?>
<div class="factura-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
		
		[
			'attribute'=>'periodo.DescPerLec',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
	    ],
		//'periodo.DescPerLec',

            'id',
		[
			'attribute'=>'cedula',
			'format'=>'text',//raw, html
			'enableSorting' => false,
	    ],
		[
			//'attribute'=>'idMatricula',
			'label'=>'Alumno',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getNombreAlumno();
	                }
	        ],
        //    'cedula',
         //   'idper',
		[
			'attribute'=>'fecha',
			'format'=>'text',//raw, html
			'filter'=>false,
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
	    
	    [
			'attribute'=>'tipo_documento',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
	    ],
            //'fecha',
            //'iva',
            // 'descuento',
             //'total',
             //'documento',
             //'pago',

            ['class' => 'yii\grid\ActionColumn', 'template'=> $template,

				'buttons' => [

					'cmatricula' => function ($url, $model) {
						$url = Url::toRoute(['factura/mallapdf', 'idfactura' => $model->id]);
						return Html::a('<span class="glyphicon glyphicon-print">c.matrícula</span>', $url, [
				                    'title' => Yii::t('app', 'certificado de matrícula'),
				    	    ]);
		        	},
				
        		'cpromocion' => function ($url, $model) {
					return Html::a(
		        		'<span class="glyphicon glyphicon-print">c.promoción</span>',
						['/detallematricula/imprimir_cp', 'idfactura' => $model->id], 
						['title' => Yii::t('app', 'certificado de promoción')]
		        	);
				
        		},
				

			'ver' => function ($url, $model){
				
				#$usuario = Yii::$app->user->identity;
				#if ($usuario->idperfil != 'fin' ){
            			return Html::a(
		        		'<span class="glyphicon glyphicon-eye-open"></span>',
					['/detallematricula/index', 'idfactura' => $model->id], //$abono_model->id
		        		
		        		[
		            			'title' => 'Ver',
		      			      'data-pjax' => '0',
		       			 ]
		    		);
				#}
        	},
			

    		],


	    ],
        ],
    ]); ?>

</div>
