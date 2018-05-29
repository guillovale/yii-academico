<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DetalleMatriculaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle de Matrícula';
$this->params['breadcrumbs'][] = ['label' => 'Documento Matrícula', 'url' => ['factura/index', 'FacturaSearch[cedula]'=> $this->params['cedula']]];
$this->params['breadcrumbs'][] = $this->title;
$hoy = date("Y-m-d");
$cedula = $this->params['cedula'];
$eliminar = $this->params['eliminar'];
//echo var_dump($this->params['eliminar']); exit;
$usuario = Yii::$app->user->identity;
$template = '{ver}';
if ($usuario) {
	if (($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') && $eliminar == 1 ) 
		$template = '{ver} {update} {delete}';
	elseif ($usuario->idperfil == 'centros' && $eliminar == 1) 
		$template = '{ver} {update}';
}

?>
<div class="detalle-matricula-index">

    <h3><?= Html::encode($this->title) ?></h3>
	 <address>
		C.I: <?= $this->params['cedula'] ?><br>
		Alumno: <?= $this->params['alumno'] ?>
	</address> 
	<?php  #echo Html::a('Ver consolidado', ['detallematricula/publicar', 'idfactura'=> $this->params['idfactura']],
			#					 ['class'=>'btn btn-primary btn-ms']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php  #echo Html::a('Imprimir C.P.', ['detallematricula/imprimir_cp', 'idfactura'=> $this->params['idfactura']],
			#			['class'=>'btn btn-primary']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'idfactura',
            //'idmatricula',
            //'idasig',
		[
			'attribute'=>'id',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],
			
		[
			'label'=>'Carrera',
			#'attribute'=>'curso.detallemalla.malla.carrera.NombCarr',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				$row = '';
				if ($data->idcurso > 0)
					$row = $data->curso->detallemalla->malla->carrera->NombCarr;
				else
					$row = $data->idCarr0->NombCarr;
				return $row;
	        }

        ],
		
		[
			'attribute'=>'idcurso',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	    ],
		

		[
			'attribute'=>'fecha',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			
	        ],

		[
			'label'=>'idAsig.',
			#'attribute'=>'curso.detallemalla.idasignatura',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				$row = '';
				if ($data->idcurso > 0)
					$row = $data->curso->detallemalla->idasignatura;
				else
					$row = $data->idasig;
				return $row;
	        }
			
	    ],
		[
			'label'=>'Asignatura',
			#'attribute'=>'curso.detallemalla.asignatura.NombAsig',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				$row = '';
				if ($data->idcurso > 0)
					$row = $data->curso->detallemalla->asignatura->NombAsig;
				else
					$row = $data->idAsig->NombAsig;
				return $row;
	        }
			
	     ],
	    //'asignatura',
		
		[
			'label'=>'Nivel',
			#'attribute'=>'curso.detallemalla.nivel',
			//'label'=>'Nivel',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				$row = '';
				if ($data->idcurso > 0)
					$row = $data->curso->detallemalla->nivel;
				else
					$row = $data->nivel;
				return $row;
	        }

	        ],

		[
			#'attribute'=>'paralelo',
			'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
			'content'=>function($data){
				$row = '';
				if ($data->idcurso > 0)
					$row = $data->curso->paralelo;
				else
					$row = $data->paralelo;
				return $row;
	        }
	        ],
		/*
		[
			'attribute'=>'estado',
			//'label'=>'Paralelo',
			'format'=>'text',//raw, html
			'filter'=>false,
			'enableSorting' => false,
	        ],
		*/
		[
			//'attribute'=>'idMatricula',
			'label'=>'Estado',
			'format'=>'text',//raw, html
			'content'=>function($data){
				$estado = '';
				if ($data->estado == 1)
					$estado = 'APROBADA';
				if ($data->estado == 0)
					$estado = 'ANULADA';
				return $estado;
	                },
			'contentOptions'=> function($data){	
				if ($data->estado == 1){
					return ['style'=>'color: black;']; // <-- right here
				}
				else
					return ['style'=>'color: red;'];
				},
	        ],
            //'idnota',
            'credito',
            // 'vrepite',
            'costo',
            // 'horario',
            // 'fecha',

            ['class' => 'yii\grid\ActionColumn',
			'template' => $template,

			'buttons' => [
		        'ver' => function ($url, $model) {
					if ($model->estado == 1) {
				        return Html::a('<span class="glyphicon glyphicon-eye-open">notas</span>', $url, [
				                    'title' => Yii::t('app', 'ver notas'),
				        ]);
					}
		        },

		        'update' => function ($url, $model) {
					if ($model->estado == 1 ) {
				        return Html::a('<span class="glyphicon glyphicon-pencil">matrícula</span>', $url, [
				                    'title' => Yii::t('app', 'actualizar matrícula'),
				        ]);
					}
		        },
		        'delete' => function($url, $model){
					if ($model->estado == 1 ) {
		        		return 
							Html::a('<span class="glyphicon glyphicon-trash">anular</span>', ['delete', 'id' => $model->id], [
									'class' => '',
									'data' => [
										'confirm' => 'Está seguro de anular el registro ?',
										'method' => 'post',
									],
				   		 	]);
					}
       			 }

          ],

			'urlCreator' => function($action, $model, $key, $index) use ($cedula, $usuario) {
	           // if ($action == 'delete' && $this->params['eliminar']) {
	           //     return Url::toRoute(['detallematricula/delete', 'id' => $key]);
	           // }
				if ($action == 'update' && $model->estado == 1 && ( in_array($model->idcarr, explode("'", $usuario->idcarr)) || 
					in_array('%', explode("'", $usuario->idcarr)) )
									
				) {
	                return Url::toRoute(['detallematricula/update', 'id' => $key,							
					]);
	            }
				if ($action == 'ver') {
		                return Url::toRoute(['notasdetalle/index', 'id' => $key,
							]);
		        }
		    },
		
		], 


        ],
    ]); ?>

</div>
