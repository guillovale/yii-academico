<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Matricula;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasSicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$nombreperiodo = $this->params['nombreperiodo'];
if ( substr($nombreperiodo, 0,4) == substr($nombreperiodo, 4,4) ){
	$per = '1S-'.substr($nombreperiodo, 0,4);
}
else {
	$per = '2S-'.substr($nombreperiodo, 0,4);
}
//$idper = $model->idper;
//var_dump($model); exit;
$this->title = 'Reporte matrícula'. ' '. 'período: '.$per;
$this->params['breadcrumbs'][] = $this->title;

$total = 0;
$total1 = 0;
$fecha = 'fecha: '.date('Y-m-d');
if (!empty($dataProvider->models)) {
        foreach($dataProvider->models as $item){
	        $total+=$item['sumtotal'];
	}
}    

if (!empty($dataProvider1->models)) {
        foreach($dataProvider1->models as $item){
	        $total1+=$item['sumtotal'];
	}
}    


?>

<div class="row">
<div class="col-xs-6">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

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
		
		#'nivel0',
		'nivel1',
		'nivel2',
		'nivel3',
		'nivel4',
		'nivel5',
		'nivel6',
		'nivel7',
		'nivel8',
		'nivel9',
		'nivel10',
		
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
		['attribute'=>'sumtotal',
			'label'=>'Total',
			'format'=>'raw',//raw, html
			'options' => ['style' => 'font-color:#FF0000'],
			'footer' => $total,
		],
		
		[
			'attribute'=>'nivel0',
			'label'=>'SNNA',
			'format'=>'text',//raw, html
			#'content'=>function($data){
			#	return $data["idcarr"]->getNombreCarrera();
	         #       }
	    ],

		['class' => 'yii\grid\ActionColumn', 'template'=> '{view}',

			'urlCreator' => function($action, $model, $key, $index) {
				#echo var_dump($action); exit;
	           				
				if ($action == 'view') {
		                return Url::toRoute(['reporte_porparalelo', 'idper' => $model['idper'], 'idcarr' => $model['idcarr']
							]);
		        }
		    },

		]
		
       ],
    
]); ?>

	<?= GridView::widget([
        'dataProvider' => $dataProvider1,
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
		'nivel1',
		'nivel2',
		'nivel3',
		'nivel4',
		'nivel5',
		'nivel6',
		'nivel7',
		'nivel8',
		'nivel9',
		'nivel10',
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
		['attribute'=>'sumtotal',
			'label'=>'Total',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $total1,
		],

		['class' => 'yii\grid\ActionColumn', 'template'=> '{view}',

			'urlCreator' => function($action, $model, $key, $index) {
				#echo var_dump($action); exit;
	           // if ($action == 'delete' && $this->params['eliminar']) {
	           //     return Url::toRoute(['detallematricula/delete', 'id' => $key]);
	           // }
				if ($action == 'update' && $model->estado == 1 && ( in_array($model->idcarr, explode("'", $usuario->idcarr)) || 
					in_array('%', explode("'", $usuario->idcarr)) )
									
				) {
	                return Url::toRoute(['detallematricula/update', 'id' => $key,							
					]);
	            }
				if ($action == 'view') {
		                return Url::toRoute(['reporte_porparalelo', 'idper' => $model['idper'], 'idcarr' => $model['idcarr']
							]);
		        }
		    },

		]
		
       ],
    
	]); ?>

</div>

 <div class="col-xs-6">
            <?php
            use scotthuangzl\googlechart\GoogleChart;

		
		$graph_data = [];
		
				
		$graph_data[] = array('Carrera', 'Total'); 
		$modEms = $dataProvider->getModels();

		//for($i = 0; $i < sizeof($modEm); $i++){
		foreach ($modEms as $modEm) {
		//$modEm     = get_object_vars($modEm);
		
		 $arr['NombCarr'] = $modEm['nombcarr'];
		 $arr['total'] = intval($modEm['sumtotal']);
		 $graph_data[] = array($arr['NombCarr'],$arr['total']); 
		
			//add the values you require as set in the order of Year, Sales , Expenses
		} //loop ends here
		//echo GoogleChart::widget(array('visualization' => 'LineChart',
             //'data' => $graph_data,
		//var_dump($graph_data);
		//exit;
	

            echo GoogleChart::widget(array('visualization' => 'PieChart',
                'data' => $graph_data,
                'options' => array('title' => 'Matrícula')));
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

