<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DocenteperasigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Docente por asignatura';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="docenteperasig-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'options' => ['style' => 'font-size:12px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'dpa_id',
			//'idPer',
			//'idCarr',
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
				//'attribute'=>'docente',
				'label'=>'Carrera',
				'format'=>'text',//raw, html
				'filter'=>false,
				'content'=>function($data){
					return $data->carrera->NombCarr;
	             }
				//'enableSorting' => false,
			
			],
            'CIInfPer',
			[
				//'attribute'=>'docente',
				'label'=>'Docente',
				'format'=>'text',//raw, html
				'filter'=>false,
				'content'=>function($data){
					return $data->getNombreDocente();
	             }
				//'enableSorting' => false,
			
			],
            //'idAsig',
			[
				'attribute'=>'idAsig',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				//'attribute'=>'docente',
				'label'=>'Asignatura',
				'format'=>'text',//raw, html
				'filter'=>false,
				'content'=>function($data){
					return $data->asignatura->NombAsig;
	             }
				//'enableSorting' => false,
			
			],
            
            // 'idAnio',
			[
				'attribute'=>'idSemestre',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
			[
				'attribute'=>'idParalelo',
				//'label'=>'Período',
				'format'=>'text',//raw, html
				'filter'=>false,
				'enableSorting' => false,
			
			],
            // 'idSemestre',
             //'idParalelo',
             //'status',
            // 'idMc',
            // 'tipo_orgmalla',
            // 'id_actdist',
            // 'id_contdoc',
            // 'transf_asistencia',
            // 'transf_frecuente',
            // 'transf_parcial',
            // 'transf_final',
            // 'arrastre',
            // 'extra',

            ['class' => 'yii\grid\ActionColumn',
				'template' => '{ver} {habilitar}',
				'buttons' => [
				    'ver' => function ($url, $model) {
				        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
				                    'title' => Yii::t('app', 'ver componentes'),
				        ]);
				    },
					'habilitar' => function ($url, $model) {
				       // return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', $url, [
				         //           'title' => Yii::t('app', 'habilitar docente'),
				       // ]);
				    },
				],

				'urlCreator' => function($action, $model, $key, $index) {
			        
					if ($action == 'ver') {
			            return Url::toRoute(['libretacalificacion/docente', 'id' => $key]);
			        }
					if ($action == 'habilitar') {
			            //return Url::toRoute(['habilitardocente/index', 'id' => $key, 					
					//		]);
			        }
				},				
			],
        ],
    ]); ?>

</div>
