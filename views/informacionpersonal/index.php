<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InformacionpersonalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Alumno';
$this->params['breadcrumbs'][] = $this->title;
$usuario = Yii::$app->user->identity;
$template = '';
?>
<div class="informacionpersonal-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<p>
	<?php if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' ) {
		    	echo Html::a('Crear alumno', ['create'], ['class' => 'btn btn-success']);
				$template = '{update} {resetear}';
		}
	?>
	</p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'CIInfPer',
            //'cedula_pasaporte',
            //'TipoDocInfPer',
            'ApellInfPer',
            'ApellMatInfPer',
            'NombInfPer',
            // 'NacionalidadPer',
            // 'EtniaPer',
            // 'FechNacimPer',
            // 'LugarNacimientoPer',
            // 'GeneroPer',
            // 'EstadoCivilPer',
            // 'CiudadPer',
            // 'DirecDomicilioPer',
            // 'Telf1InfPer',
            // 'CelularInfPer',
            // 'TipoInfPer',
            // 'statusper',
            //'mailPer',
            //'mailInst',
            // 'GrupoSanguineo',
            // 'tipo_discapacidad',
            // 'carnet_conadis',
            // 'num_carnet_conadis',
            // 'porcentaje_discapacidad',
            // 'fotografia',
            // 'codigo_dactilar',
            // 'hd_posicion',
            // 'huella_dactilar',
            // 'ultima_actualizacion',
            // 'codigo_verificacion',

            ['class' => 'yii\grid\ActionColumn', 'template' => $template,

				'buttons' => [
		        
		        'update' => function ($url, $model) {
		            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
		                        'title' => Yii::t('app', 'actualizar curso'),
		            ]);
		        },
		        'resetear' => function($url, $model){
            		return Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['resetearclave', 'id' => $model->CIInfPer], [
						    'class' => '',
						    'data' => [
						        'confirm' => 'Está seguro de resetear la clave ?',
						        'method' => 'post',
						    ],
							'title' => Yii::t('app', 'resetear clave'),
           		 		]);
       				}

         	 	],

				'urlCreator' => function($action, $model, $key, $index) {
			        //if ($action == 'delete') {
			          //  return Url::toRoute(['cursoofertado/delete', 'id' => $key]);
			        //}
					if ($action == 'update') {
			            return Url::toRoute(['informacionpersonal/update', 'id' => $key]);
			        }
					if ($action == 'resetear') {
				            return Url::toRoute(['informacionpersonal/resetearclave', 
									'id' => $key]);
				    }
				},

			],
        ],
    ]); ?>
</div>
