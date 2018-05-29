<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use app\models\Informacionpersonal;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\MallaEstudiante */
//$this->title = 'Homologar nota estudiante:';

$this->params['breadcrumbs'][] = ['label' => 'ingreso', 'url' => ['ingreso/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->title = 'Homologación:';
$suma = $this->params['suma'];
?>

<div style = "font-size:11px" class="row">
<div class="col-xs-5">
	<h4><?= Html::encode($this->title) ?></h4>
	<address>
		Alumno: <a href="#"><?= $this->params['alumno']?></a>.<br>
		C.I: <?= $this->params['cedula']?>.<br>
		Carrera: <?= $this->params['carrera']?>.<br>
		<?= $this->params['malla']?>.<br>
		Período: <?= $this->params['periodo']?>.<br>
	</address>


    

    <?= $this->render('_formhomologar', [
        'modelnota' => $modelnota,
	'modeldetalle' => $modeldetalle,
    ]) ?>

</div>

<div class="col-xs-7">

	<?php $form = ActiveForm::begin([
        'action' => ['homologar'],
        'method' => 'get',
    ]); ?>

	<div class="form-group">
        <?php if(!empty($this->params['cedula'])) {
		echo Html::a('Imprimir', ['mallapdf', 'cedula' => $this->params['cedula'], 
			'idCarr' => $this->params['idcarr'], 'malla' => $this->params['malla']
			],['class' => 'btn btn-success', 'target'=>'_blank']); 
		}
	?>
	</div>

    <?php ActiveForm::end(); ?>	



   <?php if (isset($this->params['detallematricula'])) {
	
	echo GridView::widget([
        'dataProvider' => $this->params['detallematricula'],
	//	'showFooter'=>TRUE,
        //'filterModel' => $modeldetalle,
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idfactura',
            //'idmatricula',
            //'idasig',
		[
			'attribute'=>'idfactura',
			'label'=>'Doc.',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],

		[
			'attribute'=>'idcarr',
			'label'=>'Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		'nivel',
		[
			'attribute'=>'idasig',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		'NombAsig',
		[
			'attribute'=>'credito',
			'label'=>'Crédito',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
		//'credito',
		'costo',
		[
			 'attribute' => 'total',
			 'format' => 'raw',
			 'contentOptions'=>['style'=>'width: 10%;text-align:left'],
			 
			
			
		],
		

            ['class' => 'yii\grid\ActionColumn',
		'template' => '{delete}',

		'urlCreator' => function ($action, $data, $key, $index) {
        		if ($action === 'delete') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				//echo var_dump($data);exit;
				$url = Url::to(['/homologar/delete?id='.$data["id"].';'.$data["total"]], true);
        		        return $url;
        		}
		}
		
			//],
		
		], 


        ],
    ]); }?>
	
	<p> Total a cancelar: $ <?= $suma ?><br> </p> 
	
</div>
</div>
