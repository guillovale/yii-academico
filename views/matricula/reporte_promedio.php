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
if ( substr($nombreperiodo, 0,4) == substr($nombreperiodo, 4,4) ){
	$per = '1S-'.substr($nombreperiodo, 0,4);
}
else {
	$per = '2S-'.substr($nombreperiodo, 0,4);
}

$this->title = 'Estudiantes por género: '.$per;#. ' '. 'período :'. $idper;
$this->params['breadcrumbs'][] = $this->title;
$total = 0;
$totalhombres = 0;
$totalmujeres = 0;
$totalsindato = 0;
$totalsnna = 0;
$fecha = 'fecha: '.date('Y-m-d');
if (!empty($dataProvider->models)) {
        foreach($dataProvider->models as $item){
	        #$total+=$item['sumtotal'];
		$totalhombres+=$item['HOMBRES'];
		$totalmujeres+=$item['MUJERES'];
		$totalsindato+=$item['sindato'];
		$totalsnna+=$item['SNNA'];
		
	}
	$total+=$totalhombres + $totalmujeres;
}    


?>

<div class="row">
<div class="col-xs-6">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_searchgenero', ['model' => $searchModel]); ?>

    <p>
        <?php echo $fecha;//= Html::a('Verificar notas Sic con Siad', ['#'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
	'showFooter' =>true,
        'columns' => [
		//'ciinfper',
		//'idcarr',
		//'nombcarr',
		['attribute'=>'nombcarr',
			'label'=>'Carrera',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],
		//'HOMBRES',

		['attribute'=>'HOMBRES',
			'label'=>'Hombres',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalhombres,
		],

		['attribute'=>'MUJERES',
			'label'=>'Mujeres',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalmujeres,
		],


		['attribute'=>'sindato',
			'label'=>'Sin dato',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalsindato,
		],
		

		//'MUJERES',
		
		//'sumtotal',
		/*
		[
			//'attribute'=>'idMatricula',
			'label'=>'Carrera',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data["idcarr"]->getNombreCarrera();
	                }
	        ],*/

		
		//'nivel',
		//'total',
		['attribute'=>'sumacarrera',
			'label'=>'Total',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $total,
		],
		['attribute'=>'SNNA',
			'label'=>'SNNA',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $totalsnna,
		],
		
       ],
    
]); ?>

</div>

<div class="col-xs-6">
            <?php
            use scotthuangzl\googlechart\GoogleChart;

		
		$graph_data = [];
		
				
		$graph_data[] = array('Carrera', 'Hombres', 'Mujeres'); 
		$modEms = $dataProvider->getModels();

		//var_dump($modEms);
		//exit;

		//for($i = 0; $i < sizeof($modEm); $i++){
		foreach ($modEms as $modEm) {
		//$modEm     = get_object_vars($modEm);
		
		 $arr['nombcarr'] = $modEm['nombcarr'];
		 $arr['HOMBRES'] = intval($modEm['HOMBRES']);
			$arr['MUJERES'] = intval($modEm['MUJERES']);
		 $graph_data[] = array($arr['nombcarr'],$arr['HOMBRES'], $arr['MUJERES']); 
		
			//add the values you require as set in the order of Year, Sales , Expenses
		} //loop ends here
		//echo GoogleChart::widget(array('visualization' => 'LineChart',
             //'data' => $graph_data,
		//var_dump($graph_data);
		//exit;
	

            echo GoogleChart::widget(array('visualization' => 'ColumnChart',
                'data' => $graph_data,
                'options' => array('title' => 'Matrículados')));
		//var_dump($modEm);
		//exit;

	/*
            echo GoogleChart::widget(array('visualization' => 'LineChart',
                'data' => array(
                    array('Task', 'Hours per Day'),
                    array('Work', 11),
                    array('Eat', 2),
                    array('Commute', 2),
                    array('Watch TV', 2),
                    array('Sleep', 7)
                ),
                'options' => array('title' => 'My Daily Activity')));

            echo GoogleChart::widget(array('visualization' => 'LineChart',
                'data' => array(
                    array('Year', 'Sales', 'Expenses'),
                    array('2004', 1000, 400),
                    array('2005', 1170, 460),
                    array('2006', 660, 1120),
                    array('2007', 1030, 540),
                ),
                'options' => array(
                    'title' => 'My Company Performance2',
                    'titleTextStyle' => array('color' => '#FF0000'),
                    'vAxis' => array(
                        'title' => 'Scott vAxis',
                        'gridlines' => array(
                            'color' => 'transparent'  //set grid line transparent
                        )),
                    'hAxis' => array('title' => 'Scott hAixs'),
                    'curveType' => 'function', //smooth curve or not
                    'legend' => array('position' => 'bottom'),
                )));

            echo GoogleChart::widget(array('visualization' => 'ScatterChart',
                'data' => array(
                    array('Sales', 'Expenses', 'Quarter'),
                    array(1000, 400, '2015 Q1'),
                    array(1170, 460, '2015 Q2'),
                    array(660, 1120, '2015 Q3'),
                    array(1030, 540, '2015 Q4'),
                ),
                'scriptAfterArrayToDataTable' => "data.setColumnProperty(2, 'role', 'tooltip');",
                'options' => array(
                    'title' => 'Expenses vs Sales',
                )));

            echo GoogleChart::widget( array('visualization' => 'Gauge', 'packages' => 'gauge',
                'data' => array(
                    array('Label', 'Value'),
                    array('Memory', 80),
                    array('CPU', 55),
                    array('Network', 68),
                ),
                'options' => array(
                    'width' => 400,
                    'height' => 120,
                    'redFrom' => 90,
                    'redTo' => 100,
                    'yellowFrom' => 75,
                    'yellowTo' => 90,
                    'minorTicks' => 5
                )
            ));
            echo GoogleChart::widget( array('visualization' => 'Map',
                'packages'=>'map',//default is corechart
                'loadVersion'=>1,//default is 1.  As for Calendar, you need change to 1.1
                'data' => array(
                    ['Country', 'Population'],
                    ['China', 'China: 1,363,800,000'],
                    ['India', 'India: 1,242,620,000'],
                    ['US', 'US: 317,842,000'],
                    ['Indonesia', 'Indonesia: 247,424,598'],
                    ['Brazil', 'Brazil: 201,032,714'],
                    ['Pakistan', 'Pakistan: 186,134,000'],
                    ['Nigeria', 'Nigeria: 173,615,000'],
                    ['Bangladesh', 'Bangladesh: 152,518,015'],
                    ['Russia', 'Russia: 146,019,512'],
                    ['Japan', 'Japan: 127,120,000']
                ),
                'options' => array('title' => 'My Daily Activity',
                    'showTip'=>true,
                )));
		*/

            ?>
        </div>
</div>
