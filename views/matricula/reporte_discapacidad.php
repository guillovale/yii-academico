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

$this->title = 'Estudiantes por discapacidad: '.$per;#. ' '. 'período :'. $idper;
$this->params['breadcrumbs'][] = $this->title;
$total = 0;
$totalsoltero = 0;
$totalcasado = 0;
$totaldivorciado = 0;
$totalviudo = 0;
$totalunido = 0;
$totalsindato = 0;
$fecha = 'fecha: '.date('Y-m-d');
if (!empty($dataProvider->models)) {
        foreach($dataProvider->models as $item){
	        $total+=$item['total'];
	}
}    

?>

<div class="row">
<div class="col-xs-6">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_searchdiscapacidad', ['model' => $searchModel]); ?>

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
		//'ec_nombre',

		['attribute'=>'dsp_nombre',
			'label'=>'Discapacidad',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'color:#0000FF'],
			//'footer' => $total,
		],

			
		['attribute'=>'total',
			'label'=>'Total',
			'format'=>'raw',//raw, html
			//'options' => ['style' => 'font color:red'],
			'footer' => $total,
		],
		
       ],
    
]); ?>

</div>

<div class="col-xs-6">
            <?php
            use scotthuangzl\googlechart\GoogleChart;

		
		$graph_data = [];
		
				
		$graph_data[] = array('nombre', 'TOTAL'); 
		$modEms = $dataProvider->getModels();

		foreach ($modEms as $modEm) {
		
		$arr['dsp_nombre'] = $modEm['dsp_nombre'];
		
		$arr['total'] = intval($modEm['total']);
		$graph_data[] = array($arr['dsp_nombre'], $arr['total']); 
		
			//add the values you require as set in the order of Year, Sales , Expenses
		} //loop ends here
		
            echo GoogleChart::widget(array('visualization' => 'PieChart',
                'data' => $graph_data,
                'options' => array('title' => 'Matriculados')));
		
            ?>
        </div>
</div>


