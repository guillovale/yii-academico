<?php
#use kartik\builder\TabularForm;
#use kartik\form\ActiveForm;
#use kartik\grid\GridView;
use yii\helpers\Html;
use yii\grid\GridView;

$periodo =  $this->params['periodo'];
$carrera =  $this->params['carrera'];
$asignatura =  $this->params['asignatura'];
$paralelo =  $this->params['paralelo'];
$nivel =  $this->params['nivel'];
$idper = $this->params['idper'];
$idcarr = $this->params['idcarr'];
$idasig = $this->params['idasig'];
$idcurso = $this->params['idcurso'];

$this->title = 'Consolidado';
$this->params['breadcrumbs'][] = ['label' => 'Libreta calificaciones', 'url' => ['index', 'idcurso'=> $idcurso]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div style = "font-size:12px" class="row">
	<div class="row">
	<h3><?= Html::encode($this->title) ?></h3>
	<address>
		Período: <?= $this->params['periodo'] ?> <br>
		Carrera: <?= $this->params['carrera'] ?> <br>
		<b>Asignatura: <?= $this->params['asignatura'] ?> </b><br>
		Nivel: <?= $this->params['nivel'] ?> 
		Paralelo: <?= $this->params['paralelo'] ?> </b>
	</address>
	
<?php
/*
$createUrl = "imprimir";
$deleteUrl = "delete";
$form = ActiveForm::begin();
echo TabularForm::widget([
    'form' => $form,
    'dataProvider' => $dataProvider,
	'actionColumn' => false,
    'attributes' => [
        'cedula' => ['type' => TabularForm::INPUT_STATIC],
		'nombre' => ['type' => TabularForm::INPUT_STATIC],
        #'idcurso' => [
         #   'type' => TabularForm::INPUT_WIDGET, 
         #   'widgetClass' => \kartik\widgets\ColorInput::classname()
        #],
        #'iddocente' => [
        #    'type' => TabularForm::INPUT_DROPDOWN_LIST, 
            #'items'=>ArrayHelper::map(Author::find()->orderBy('name')->asArray()->all(), 'id', 'name')
        #],
        'NGA1' => [
            'type' => TabularForm::INPUT_STATIC, 
			'label' => 'Ga1',
            #'options'=>['class'=>'form-control text-right'], 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
        'NGA2' => [
            'type' => TabularForm::INPUT_STATIC, 
			'label' => 'Ga2',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'NPA' => [
            'type' => TabularForm::INPUT_STATIC, 
			'label' => 'Pa',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'NX1' => [
            'type' => TabularForm::INPUT_STATIC, 
			'label' => 'Ex1',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'NX2' => [
            'type' => TabularForm::INPUT_STATIC,
			'label' => 'Ex2', 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'NM' => [
            'type' => TabularForm::INPUT_STATIC,
			'label' => 'Mej.',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'NAT' => [
            'type' => TabularForm::INPUT_STATIC, 
			'label' => 'Ast.',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'notafinal' => [
            'type' => TabularForm::INPUT_STATIC, 
			#'label' => 'Final',
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
		'Estado' => [
            'type' => TabularForm::INPUT_STATIC, 
            'columnOptions'=>['hAlign'=>GridView::ALIGN_RIGHT]
        ],
    ],
    'gridSettings' => [
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Consolidado</h3>',
            'type' => GridView::TYPE_PRIMARY,
			
            'after'=> 
                Html::a(
                    '<i class="glyphicon glyphicon-print"></i> Imprimir', 
                    ['imprimir', 'idcurso'=>$idcurso], 
                    ['class'=>'btn btn-success']
                )# . '&nbsp;' . 
                #Html::a(
                #    '<i class="glyphicon glyphicon-remove"></i> Delete', 
                #    $deleteUrl, 
                #    ['class'=>'btn btn-danger']
                #) . '&nbsp;' .
                #Html::submitButton(
                #    '<i class="glyphicon glyphicon-floppy-disk"></i> Save', 
                #    ['class'=>'btn btn-primary']
                #)
        ]
    ]     
]); 
ActiveForm::end(); 
*/
echo Html::a(
                    '<i class="glyphicon glyphicon-save"></i> Publicar notas', 
                    ['imprimir', 'idcurso'=>$idcurso], 
                    ['class'=>'btn btn-success']
                )
?>
<br><br>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        #'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute'=>'iddetallematricula',
				'label'=>'Id Matrícul',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'cedula',
				'label'=>'Cédula',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
            //'idper',
            //'iddocenteperasig',
            //'iddocente',
            //'fecha',
			[
				'attribute'=>'nombre',
				'label'=>'Nombre',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NGA1',
				'label'=>'NGA1',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NGA2',
				'label'=>'NGA2',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NPA',
				'label'=>'NPA',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NX1',
				'label'=>'NX1',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NX2',
				'label'=>'NX2',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'suma',
				'label'=>'SUMA',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NM',
				'label'=>'NM',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'notafinal',
				'label'=>'Final',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'NAT',
				'label'=>'Asist.',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'Estado',
				'label'=>'Estado',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
            //'hemisemestre',
			
            //'idparametro',
            // 'idcomponente',
            // 'tema',

            ['class' => 'yii\grid\ActionColumn', 'template' => '',

				'buttons' => [
				    'ver' => function ($url, $model) {
				        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
				                    'title' => Yii::t('app', 'ver notas'),
				        ]);
				    },
					'actualizar' => function ($url, $model) {
				        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
				                    'title' => Yii::t('app', 'actualizar'),
				        ]);
				    },
				],

				'urlCreator' => function($action, $model, $key, $index) {
			        
					if ($action == 'ver') {
			            return Url::toRoute(['view', 'id' => $key]);
			        }
					if ($action == 'actualizar') {
			            return Url::toRoute(['update', 'id' => $key]);
			        }
				},
			],
				
        ],
    ]); ?>
</div>
