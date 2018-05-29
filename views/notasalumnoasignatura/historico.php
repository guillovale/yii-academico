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
$this->params['breadcrumbs'][] = 'Histórico';

//Yii::$app->getSession()->setFlash('url', $_SERVER['REQUEST_URI']);
Yii::$app->session->set('url', $_SERVER['REQUEST_URI']);
Url::remember();
//$url = Url::to(Url::current());
//var_dump($url);

?>
<div style = "font-size:10px" class="col-xs-12 sidebar">
<div class="notasalumnoasignatura-index">

    <h3><?= Html::encode($this->title) ?></h3>
    <?php echo $this->render('_searchhistorico', ['model' => $searchModel]); ?>
	<?php
				$cedula = (isset($_GET['NotasalumnoasignaturaSearch']['CIInfPer']) ? 
								$_GET['NotasalumnoasignaturaSearch']['CIInfPer'] : '');
				$alumno = Informacionpersonal::find()
					->where(['CIInfPer' => $cedula])
					->one();
	?>
			
	<h4><b><p>Alumno: <?php if($alumno) echo $alumno->ApellInfPer . ' ' . 
					$alumno->ApellMatInfPer . ' ' . $alumno->NombInfPer ?> </p></b></h4>
	</br>
	
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

	    'idAsig0.IdAsig',
		'idAsig0.NombAsig',
		'periodo0.DescPerLec',

		

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
		array(  'attribute'=>'aprobada',
                        'label'=>'Estado',
                        'format'=>'raw',
			'filter'=>false,
		),
		array(  'attribute'=>'observacion',
                        'label'=>'Observación',
                        'format'=>'raw',
			'filter'=>false,
		),
		#'aprobada',
		#'observacion',

            [
		
		'class' => 'yii\grid\ActionColumn',
		'contentOptions' => ['style' => 'width:260px;'],
		'header'=>'',
		'template' => '',
		
		
		],
	],
    ]); ?>

</div>

</div>
