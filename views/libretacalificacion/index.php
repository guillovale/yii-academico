<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LibretaCalificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista Componentes';
$this->params['breadcrumbs'][] = ['label' => 'Docente por asignatura', 
							'url' => ['cursoofertado/docente', 'CursoOfertadoSearch[iddocente]'=> $modelCurso->iddocente]];
$this->params['breadcrumbs'][] = $this->title;
$template = '{ver}';
$usuario = Yii::$app->user->identity;
if ($usuario) {
	if (($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') ) 
		$template = '{ver} {actualizar}';
}
?>
<div style = "font-size:11px" class="row">
	<div class="col-xs-8">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I.: <?= $modelCurso->iddocente ?> <br>
		<b>Docente: <?= $modelCurso->docente->nombre ?> </b><br>
		<?= $modelCurso->detallemalla->malla->carrera->NombCarr ?><br>
		<?= $modelCurso->detallemalla->idasignatura ?>
		<?= $modelCurso->detallemalla->asignatura->NombAsig ?>
	</address>
	<?php if ( stristr($modelCurso->detallemalla->malla->detalle, 'SNNA') === FALSE )
		echo Html::a('Ver consolidado', ['notasdetalle/publicar', 'idcurso'=>$modelCurso->id],
								 ['class'=>'btn btn-primary btn-ms']); 
			else 
				echo Html::a('Ver consolidado', ['consolidado', 'idcurso'=>$modelCurso->id],
								 ['class'=>'btn btn-primary btn-ms']);	?>
	
	<?php  echo Html::a('Agregar Alumnos a componentes', ['agregar', 'idcurso'=>$modelCurso->id],
								 ['class'=>'btn btn-warning btn-ms']); ?>
	<?php  echo Html::a('Crear componente', ['crearcomponente', 'idcurso'=>$modelCurso->id],
								 ['class'=>'btn btn-default btn-ms']); ?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
			[
				'attribute'=>'id',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
            //'idper',
            //'iddocenteperasig',
            //'iddocente',
            //'fecha',
			[
				//'attribute'=>'docente',
				'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'content'=>function($data){
					return $data->periodo->DescPerLec;
	             }
				//'enableSorting' => false,
			
			],
			[
				'attribute'=>'fecha',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
            //'hemisemestre',
			[
				'attribute'=>'hemisemestre',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			'parametrosigla',
			'componente',
            //'idparametro',
            // 'idcomponente',
            // 'tema',

            ['class' => 'yii\grid\ActionColumn', 'template' => $template,

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

	<div class="col-xs-4">
	
   	<p> <b>Lista Alumnos: </b><br> </p> 
		<?= GridView::widget([
		    'dataProvider' => $datamatriculados,
		    //'filterModel' => $searchModel,
		    'columns' => [
		        ['class' => 'yii\grid\SerialColumn'],

		        'factura.cedula',
				[
				//'attribute'=>'docente',
				'label'=>'Alumno',
				'format'=>'text',//raw, html
				'filter'=>false,
				'content'=>function($data){
					return $data->factura->getNombreAlumno();
	             }
				//'enableSorting' => false,
			
			],
		    ['class' => 'yii\grid\ActionColumn', 'template' => ''],
		    ],
		]); ?>


	</div>

</div>
