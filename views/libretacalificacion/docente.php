<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LibretaCalificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lista Componentes';
$this->params['breadcrumbs'][] = ['label' => 'Docente por asignatura', 
							'url' => ['docenteperasig/index', 'DocenteperasigSearch[CIInfPer]'=> $modelDocente->CIInfPer]];
$this->params['breadcrumbs'][] = $this->title;
$template = '{ver}';
$usuario = Yii::$app->user->identity;
if ($usuario) {
	if (($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') ) 
		$template = '{ver} {actualizar}';
}
?>
<div class="form-group">
        <?= Html::a('Crear componente', ['create', 'iddocenteperasig'=> $modelDocente->dpa_id], ['class' => 'btn btn-success']) ?>
</div>

<div class="libreta-calificacion-index">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I.: <?= $modelDocente->CIInfPer ?> <br>
		Docente: <?= $modelDocente->getNombreDocente() ?> <br>
		Carrera: <?= $modelDocente->carrera->NombCarr ?> <br>
		<b>Asignatura: <?= $modelDocente->asignatura->NombAsig ?> 
		<?php echo '-- '. $modelDocente->idSemestre. ' '. $modelDocente->idParalelo ?></b>
		
	</address>
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
			            return Url::toRoute(['ver', 'id' => $key]);
			        }
					if ($action == 'actualizar') {
			            return Url::toRoute(['update', 'id' => $key]);
			        }
				},
			],
				
        ],
    ]); ?>

</div>
