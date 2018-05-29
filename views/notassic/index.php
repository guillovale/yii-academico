<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Notasalumnoasignatura;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasSicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notas Sic';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notas-sic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //= Html::a('Verificar notas Sic con Siad', ['#'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'idcarrera',
            //'carrera',
		
		[
			'attribute'=>'carrera',
			'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			//'filter'=>false,
	        ],		

	    'nivel',
            //'cedula',
            'codigo',
            'asignatura',
            'calificacion',
            'estado',
            //'fecha',
		/*
		[
						//'attribute'=>'nombreCarrera',
						'label'=>'Estado',
						'format'=>'text',//raw, html
						'content'=>function($data){
								//return ($data->aprobada) == 1?"APROBADA":"REPROBADA";
								//return $data->aprobada;
								if ($data->estado == 'APROBADA' || $data->estado == 'HOMOLOGADA')
									return Html::a('<span class="glyphicon glyphicon-ok"></span>');
								else
									return Html::a('<span class="glyphicon glyphicon-remove"></span>');

							}
					],
		*/


            ['class' => 'yii\grid\ActionColumn', 'template' => '{enviar}', 
		'buttons'  => [
	        	'enviar' => function($url, $model) {
				//echo var_dump($model['nivel']); exit;
				if (!empty($model->cedula) && ($model->estado != 'REPROBADA'))	{

					$nota = 0.0;
					(($model->estado == 'CONVALIDADA' || $model->estado == 'HOMOLOGADA') && 
						($model->calificacion === NULL))? $nota = 8.0:$nota = $model['calificacion'];

					if ($model->estado == 'APROBADA' && $model['calificacion'] > 13 && 
						$model['calificacion'] <= 20) $nota = ($model['calificacion']/2);

					if ($model->estado == 'APROBADA' && $model['calificacion'] > 25 && 
						$model['calificacion'] <= 40) $nota = (round($model['calificacion']/4, 1));					

					$notas = Notasalumnoasignatura::find()
						->joinWith('matricula0')
						->joinWith('idAsig0')
						->joinWith('matricula0.idCarr0')
						->where(['notasalumnoasignatura.CIInfPer' => $model['cedula'], 
							'matricula.idCarr' => (intval($model['idcarrera'])),
							'matricula.idSemestre' =>  $model['nivel'],
							'notasalumnoasignatura.idAsig' => $model['codigo'],
							'notasalumnoasignatura.CalifFinal' => ($nota)
						])
						->orderBy('carrera.NombCarr ASC, matricula.idSemestre ASC, asignatura.NombAsig ASC')
						->one();				
				
				
					if (empty($notas))
			        	return Html::a('<span class="glyphicon glyphicon-arrow-right"></span>', $url, [
		                	'title' => Yii::t('app', 'enviar'),]);
				}
				return;
			}
		],

		'urlCreator' => function ($action, $model, $key, $index) {
			if ($action === 'enviar') {
				//$url = 'view&carrera='.$model['idcarrera'].'&codigo='.$model['cedula'];
				//$nota = $model['calificacion']?$model['calificacion']:0.0;
				//if (($model['calificacion'] === NULL ) && 
				//	($model['estado'] == 'HOMOLOGADA' || $model['estado'] == 'CONVALIDADA' )) $nota = 8.00;

				$nota = 0.0;
				(($model->estado == 'CONVALIDADA' || $model->estado == 'HOMOLOGADA') && 
					($model->calificacion === NULL))? $nota = 8.0:$nota = $model['calificacion'];

				if ($model->estado == 'APROBADA' && $model['calificacion'] > 13 && 
					$model['calificacion'] <= 20) $nota = ($model['calificacion']/2);

				if ($model->estado == 'APROBADA' && $model['calificacion'] > 25 && 
					$model['calificacion'] <= 40) $nota = (round($model['calificacion']/4,1));			


				$url = Url::to(['notassic/subirnota', 'cedula' => $model['cedula'], 'idcarrera' => $model['idcarrera'], 
						'nivel' => $model['nivel'], 'codigo' => $model['codigo'],
						'calificacion' => $nota, 'estado' => $model['estado'],
						'fecha' => $model['fecha']
				]);
				return $url;
			}
		}
		

	], 
       ],
    ]); ?>

</div>
