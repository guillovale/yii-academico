<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$cedula = $this->params['cedula'];
$alumno = $this->params['alumno'];
$idasig = $this->params['idasig'];
$asignatura = $this->params['asignatura'];
$idfactura = $this->params['idfactura'];
$idper = $this->params['idper'];
$this->title = 'Notas componentes';
$this->params['breadcrumbs'][] = ['label' => 'Detalle Matrícula', 'url' => [
									'detallematricula/index', 'idfactura'=> $idfactura, 'idper'=> $idper, 
									'cedula'=> $cedula, 'alumno'=> $alumno,
								]];
$this->params['breadcrumbs'][] = $this->title;
$total = 0;
$amount = 0;
    //if (!empty($dataProvider->getModels())) {
      //  foreach ($dataProvider->getModels() as $key => $val) {
        //    $amount += $val->nota;
        //}
    //}
$usuario = Yii::$app->user->identity;
$template = '';
if ($usuario) {
	if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') 
		$template = '{update} {delete}';
}
?>
<div class="notas-detalle-index">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I: <?= $cedula ?><br>
		Alumno: <?= $alumno ?>
	</address> 
	<p>
		<?php echo $idasig;?> 
		<?php echo $asignatura; ?>
	</p>
	
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'showFooter'=>TRUE,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'idnota',
            //'idlibreta',
            //'iddetallematricula',
			[
			'attribute'=>'iddetallematricula',
			'label'=>'no. Matrícula',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
			[
			'attribute'=>'idnota',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],

			[
			//'attribute'=>'idMatricula',
			'label'=>'Comp.',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->libreta->getParametrosigla();
	                }
	        ],
			[
			//'attribute'=>'idMatricula',
			'label'=>'Hemisemestre',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->libreta->hemisemestre;
	                }
	        ],
			[
			//'attribute'=>'idMatricula',
			'label'=>'Parámetro',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->libreta->getParametro();
	                }
	        ],

			[
			//'attribute'=>'idMatricula',
			'label'=>'Componente',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->libreta->getComponente();
	                }
	        ],
            //'nota',
			[
			 'attribute' => 'nota', 
				'label' => 'Nota',
			 'content'=>function($data){
				return round($data->nota);
	                },
	        
			 //'footer' => round($amount),
		   ],
			[
			 'attribute' => 'peso', 
				'label' => 'Peso nota',
			 'content'=>function($data){
				return $data->peso;
	                },
	        
			 //'footer' => round($amount),
		   ],
			
			//'usuario',
			//'fecha',
			
            ['class' => 'yii\grid\ActionColumn',
				'template' => $template,
				
				'urlCreator' => function($action, $model, $key, $index) use ($cedula, $alumno, $idasig, $asignatura, $idfactura) {
			        
					if ($action == 'update') {
			            return Url::toRoute(['update', 'id' => $key, 'idfactura'=>$idfactura,
							'idasig'=>$idasig, 'asignatura'=> $asignatura,
							'hemi'=> $model->libreta->hemisemestre,
							'comp'=> $model->libreta->getComponente(),
							'cedula' => $cedula,
							'alumno' => $alumno,								
						]);
			        }

					if ($action == 'delete') {
			            return Url::toRoute(['delete', 'id' => $key]);
			        }

				},
		
			],
        ],
    ]); ?>

</div>
