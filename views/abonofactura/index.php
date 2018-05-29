<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\AbonoFactura;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AbonoFacturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle de pagos';
$this->params['breadcrumbs'][] = $this->title;
$documento = isset($searchModel->idfactura)?$searchModel->idfactura:'';
$cedula = isset($searchModel->cedula)?$searchModel->cedula:'';
$usuario = Yii::$app->user->identity;
$template = '{view}';
if ($usuario) {
	if (($usuario->idperfil == 'sa' || $usuario->idperfil == 'fin')) 
		$template = '{view} {delete}';
}

?>

<div class="abonofactura-index" >

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?php Html::a('Pago documentos', ['abonar', 'documento'=>$documento,'cedula'=>$cedula], 
					['class' => 'btn btn-success']); ?>
    </p>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'showFooter' => true,
		'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'idfactura',
			[
				'label' => 'Tipo documento',
				'attribute' => 'tipodocumento',
				'value'    => 'factura.tipo_documento'
			],
			[
				'label' => 'Cédula',
				'attribute' => 'cedula',
				'value'    => 'factura.cedula'
			],
			[
				//'label' => 'Cédula',
				'attribute' => 'alumno',
				'value'    => 'factura.nombreAlumno'
			],
			//'factura.nombreAlumno',
			[
				'attribute' => 'fecha',
				'format'    => ['DateTime','php:Y-m-d']
			],
            //'fecha',
            'documento',
			'usuario',
            //'valor',
			[
				'attribute'=>'valor',
				//'label'=>'Nombre Carrera',
				'format'=>'text',//raw, html
				'footer' => Abonofactura::getTotal($dataProvider->models, 'valor'),
				'filter'=>false,
				'enableSorting' => false,
			
	        ],
			
            ['class' => 'yii\grid\ActionColumn',
		'template' => $template,
		'urlCreator' => function ($action, $model, $key, $index) {
        	if ($action === 'delete') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['abonofactura/delete', 'id'=>$model->id,], true);
        		        return $url;
        		}

			if ($action === 'view') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['abonofactura/view', 'id'=>$model->id], true);
        		        return $url;
        		}

		},

		],
        ],
    ]); ?>
	
</div>
