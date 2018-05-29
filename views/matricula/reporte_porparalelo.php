<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Matricula;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasSicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
#$idper = $this->params['idper'];

//var_dump($this->params['customParam']);
$nombreperiodo = $this->params['nombreperiodo'];
if ( substr($nombreperiodo, 0,4) == substr($nombreperiodo, 4,4) ){
	$per = '1S-'.substr($nombreperiodo, 0,4);
}
else {
	$per = '2S-'.substr($nombreperiodo, 0,4);
}

$this->title = 'Estudiantes por paralelo: '.$per;#. ' '. 'período :'. $idper;
$this->params['breadcrumbs'][] = ['label' => 'Reporte Matrícula', 'url' => ['index', 'MatriculaSearch[idperiodo]'=> $idper]];
$this->params['breadcrumbs'][] = $this->title;
$total = 0;
$totalsoltero = 0;
$totalcasado = 0;
$totaldivorciado = 0;
$totalviudo = 0;
$totalunido = 0;
$totalsindato = 0;
$fecha = 'fecha: '.date('Y-m-d');
/*
if (!empty($dataProvider->models)) {
        foreach($dataProvider->models as $item){
	        $total+=$item['total'];
		
	}
}    
*/

?>

<div class="row">
<div class="col-xs-6">

    <h3><?= Html::encode($this->title) ?></h3>
    
    <p>
        <?php echo $fecha;//= Html::a('Verificar notas Sic con Siad', ['#'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'showFooter' =>true,
        'columns' => [['class' => 'yii\grid\SerialColumn'],
		'idper',
		'cedula',
		'NombCarr',
		'nivelm',
		'paralelo',
		'total',
		//'ec_nombre',

		
		/*
		['attribute'=>'nombcarr',
			'label'=>'Carrera',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		//'HOMBRES',

		['attribute'=>'soltero',
			'label'=>'Solteros',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalsoltero,
		],

		['attribute'=>'casado',
			'label'=>'Casados',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalcasado,
		],

		['attribute'=>'divorciado',
			'label'=>'Divorciados',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totaldivorciado,
		],

		['attribute'=>'viudo',
			'label'=>'Viudos',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalviudo,
		],
		
		['attribute'=>'unido',
			'label'=>'Union libre',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalunido,
		],
		
		['attribute'=>'sindato',
			'label'=>'Sin dato',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalsindato,
		],
		*/
		/*
		['attribute'=>'total',
			'label'=>'Total',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $total,
		],
		*/
       ],
    
]); ?>

</div>

<div class="col-xs-6">
           
        </div>
</div>


