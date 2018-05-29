<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Informacionpersonal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\NotasalumnoasignaturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$nombre = '';
/*
if (isset($searchModel->CIInfPer) && (!empty($searchModel->CIInfPer)) ) {
	$estudiante = Informacionpersonal::find()
	->where("CIInfPer = $searchModel->CIInfPer")
	->one();
	if (!empty($estudiante)) {
		$nombre = $estudiante->ApellInfPer . ' ' . $estudiante->ApellMatInfPer . ' ' .  $estudiante->NombInfPer;
	}
}
$this->title = 'Alumno: ' . $nombre;
*/
$this->params['breadcrumbs'][] = 'Culminación';

//Yii::$app->getSession()->setFlash('url', $_SERVER['REQUEST_URI']);
Yii::$app->session->set('url', $_SERVER['REQUEST_URI']);
Url::remember();
//$url = Url::to(Url::current());
//var_dump($url);

?>
<div class="notasalumnoasignatura-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo var_dump(Yii::$app->user->identity->LoginUsu); exit;//= Html::a('Create Notasalumnoasignatura', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
		

            //'idnaa',
            //'CIInfPer',
		//'nombreCarrera',
		[
			'attribute'=>'nombreCarrera',
			'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],

		[
			//'attribute'=>'idMatricula',
			'label'=>'Nivel',
			'format'=>'text',//raw, html
			'content'=>function($data){
				if ($data->getSemestre() > 0){
					return $data->getSemestre();
				}
				else
					return $data->getNiveldetalle();
	                }
	        ],

	    //'idAsig',
		[
			'attribute'=>'idAsig',
			'label'=>'Código',
			'format'=>'text',//raw, html
			'filter'=>false,
	        ],

		[
			//'attribute'=>'idMatricula',
			'label'=>'Asignatura',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getAsignatura();
	                }
	        ],

		[
			//'attribute'=>'idMatricula',
			'label'=>'Período',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getnombrePeriodo();
	                }
	        ],

            array(  'attribute'=>'CalifFinal',
                        'label'=>'Nota',
                        'format'=>'raw',
			'filter'=>false,
		),
            //'CalifFinal',
		array(  'attribute'=>'asistencia',
                        'label'=>'Asist.',
                        'format'=>'raw',
			'filter'=>false,
		),

            [
		
		'class' => 'yii\grid\ActionColumn',
		'contentOptions' => ['style' => 'width:260px;'],
		'header'=>'',
		'template' => '{view}{delete}',
		'buttons' => [

			'view' => function ($url, $model,$key) {
				$identity = Yii::$app->user->identity;
				if (isset($identity) && $identity->crearnota == 1 && !empty($model->observacion_efa) 
					&& $model->observacion != 'APROBADA' && $model->observacion != 'REPROBADA' 
					&& $model->usu_pregistro == $identity->LoginUsu) {
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, 
						['title' => Yii::t('app', 'Ver'),]);
				}
			},

			//delete button
			'delete' => function ($url, $model) {
				$identity = Yii::$app->user->identity;
				if (isset($identity) && $identity->crearnota == 1 && !empty($model->observacion_efa) 
					&& $model->observacion != 'APROBADA' && $model->observacion != 'REPROBADA' 
					&& $model->usu_pregistro == $identity->LoginUsu) {
		        	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [ 
						'title' => Yii::t('app', 'Eliminar'),
						//'class' =>'btn btn-primary btn-xs',
						'data-method'=>'post', 
					      ]);
				}
		    },

		],
		
		'urlCreator' => function ($action, $model, $key, $index) {
        		if ($action === 'delete') {
        		        $url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['/notasalumnoasignatura/delete?id='.$model->idnaa], true);
        		        return $url;
        		}

			if ($action === 'view') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['/notasalumnoasignatura/view?id='.$model->idnaa], true);
        		        return $url;
        		}

		}
		
		],
	],
    ]); ?>

</div>
