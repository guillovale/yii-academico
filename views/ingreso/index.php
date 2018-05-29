<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $searchModel app\models\IngresoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ingreso';
$this->params['breadcrumbs'][] = $this->title;

$usuario = Yii::$app->user->identity;
$template = '';
if ($usuario) {
	if (($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') ) 
		$template = '{homologar} {update} {delete}';
	elseif ($usuario->idperfil == 'centros') 
		$template = '{update} {delete}';
	elseif ($usuario->idperfil == 'snna') 
		$template = '{homologar}';
}

?>
<div style = "font-size:11px" class="ingreso-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
	<?php if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
			echo Html::a('Crear Ingreso', ['create'], ['class' => 'btn btn-success']);
			echo ' ';
			echo Html::a('Carga masiva', ['upload'], ['class' => 'btn btn-warning']);
		}
	?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'idper',
		'periodo.DescPerLec',
        #'nombrecarrera',
		[
				'attribute' => 'nombrecarrera',
				'value' => 'carrera.NombCarr'
			],
		
		'malla0.detalle',
		[
			'attribute'=>'CIInfPer',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			#'filter'=>false,
			'enableSorting' => false,
			
		],
        
		[
			'attribute'=>'nombrealumno',
			'format'=>'text',//raw, html
			'content'=>function($data){
				return $data->getNombreAlumno();
	                }
	        ],

		/*[
			'attribute'=>'malla',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],*/

		[
			'attribute'=>'fecha',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],

		[
			'attribute'=>'tipo_ingreso',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			//'filter'=>false,
			//'enableSorting' => false,
			
		],
		[
			'attribute'=>'observacion',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],
		/*[
			'attribute'=>'usuario',
			//'label'=>'Nombre Carrera',
			'format'=>'text',//raw, html
			'filter'=>false,
			//'enableSorting' => false,
			
		],*/
            //'fecha',
            //'tipo_ingreso',
            //'observacion',
             //'usuario',

            ['class' => 'yii\grid\ActionColumn', 'template' => $template,

		//******************************************
		'buttons' => [
			'update' => function ($url, $model,$key) {
				$identity = Yii::$app->user->identity;
				$carreras_user = explode("'", $identity->idcarr);
				if (in_array($model->idcarr, $carreras_user)) {
					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, 
						['title' => Yii::t('app', 'Ver'),]);
				}
				if (in_array('%', $carreras_user)) {
		        	return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, 
						['title' => Yii::t('app', 'Ver'),]);
				}
			},

			//delete button
			'delete' => function ($url, $model) {
				$identity = Yii::$app->user->identity;
				$carreras_user = explode("'", $identity->idcarr);

				if ( in_array($model->idcarr, $carreras_user) ) {
		        	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [ 
						'title' => Yii::t('app', 'Eliminar'),
						//'class' =>'btn btn-primary btn-xs',
						'data-method'=>'post', 
					      ]);
				}
//				echo var_dump($identity->idcarr); exit;
				if ( in_array('%', $carreras_user)) {
		        	return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [ 
						'title' => Yii::t('app', 'Eliminar'),
						//'class' =>'btn btn-primary btn-xs',
						'data-method'=>'post', 
					      ]);
				}
		   	 },

			'homologar' => function ($url, $model,$key) {
				$identity = Yii::$app->user->identity;
				$carreras_user = explode("'", $identity->idcarr);
				if ( in_array($model->idcarr, $carreras_user) ) {
					return Html::a('<span >Homologar</span>', $url, 
							['title' => Yii::t('app', 'homologar'),]);
				}
				if ( in_array('%', $carreras_user) ) {
					return Html::a('<span >Homologar</span>', $url, 
					['title' => Yii::t('app', 'homologar'),]);
				}
			},

		],
		
		'urlCreator' => function ($action, $model, $key, $index) {
        		if ($action === 'delete') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['/ingreso/delete?id='.$model->id], true);
        		        return $url;
        		}

			if ($action === 'update') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['/ingreso/update?id='.$model->id], true);
        		        return $url;
        		}
			if ($action === 'homologar') {
        		        //$url ='/notasalumnoasignatura/delete?id='.$model->idnaa;
				$url = Url::to(['/homologar/homologar?id='.$model->id], true);
        		        return $url;
        		}

		}
		
		],

		//****************************************
        ],
    ]); ?>

</div>
