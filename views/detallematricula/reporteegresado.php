<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Matricula;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasSicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
#$idper = $this->params['customParam'];

//var_dump($this->params['customParam']);

$nombreperiodo = $this->params['nombreperiodo'];
$nombrecarrera = $this->params['nombrecarrera'];
if ( substr($nombreperiodo, 0,4) == substr($nombreperiodo, 4,4) ){
	$per = '1S-'.substr($nombreperiodo, 0,4);
}
else {
	$per = '2S-'.substr($nombreperiodo, 0,4);
}

$this->title = 'Egresados: '. $per . ' Carrera: '. $nombrecarrera;#. ' '. 'período :'. $idper;
$this->params['breadcrumbs'][] = $this->title;
$total = 0;

$totalotros = 0;
$totalsindato = 0;
$fecha = 'fecha: '.date('Y-m-d');
if (!empty($dataProvider->models)) {
        foreach($dataProvider->models as $item){
	        $total+= 1;#$item['total'];
		//$totalsindato+=$item['EtniaPer'];
		
	}
}    


?>

<div class="row">
<div class="col-xs-12">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_searchegresado', ['model' => $searchModel]); ?>

    <p>
        <?php echo $fecha;//= Html::a('Verificar notas Sic con Siad', ['#'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'showFooter' =>true,
        'columns' => [
		
		['attribute'=>'CIInfPer',
			'label'=>'Cédula',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		['attribute'=>'ApellInfPer',
			'label'=>'Apellido',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		['attribute'=>'ApellMatInfPer',
			'label'=>'',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		['attribute'=>'NombInfPer',
			'label'=>'Nombre',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		
		//'tet_nombre',
		//'HOMBRES',
		/*
		['attribute'=>'INDIGENAS',
			'label'=>'Indígenas',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalindigenas,
		],

		['attribute'=>'AFROECUATORIANOS',
			'label'=>'Afroecuatorianos',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalafros,
		],

		['attribute'=>'NEGROS',
			'label'=>'Negros',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalnegros,
		],

		['attribute'=>'MULATOS',
			'label'=>'Mulatos',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalmulatos,
		],
		
		['attribute'=>'MONTUBIOS',
			'label'=>'Montubios',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalmontubios,
		],
		
		['attribute'=>'MESTIZOS',
			'label'=>'Mestizos',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalmestizos,
		],
	
		['attribute'=>'BLANCOS',
			'label'=>'Blancos',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalblancos,
		],		
	
		['attribute'=>'otros',
			'label'=>'Otros',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalotros,
		],

		['attribute'=>'sindato',
			'label'=>'Sin dato',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalsindato,
		],
		
		*/
		
		
       ],
    
]); ?>

</div>

</div>
