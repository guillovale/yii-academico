<?php
//use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use app\widgets\Item;
use app\models\Carrera;
use app\models\Informacionpersonal;
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
<div class="container">
	<div class="row">
		<div class="col-xs-7">
			<?= $content; ?>
		</div>
		<div class="col-xs-5 sidebar">
			</br></br>

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

			<?php $carrera = (isset($_GET['NotasalumnoasignaturaSearch']['carrera']) ? 
								$_GET['NotasalumnoasignaturaSearch']['carrera'] : '');
				$cedula = (isset($_GET['NotasalumnoasignaturaSearch']['CIInfPer']) ? 
								$_GET['NotasalumnoasignaturaSearch']['CIInfPer'] : '');
				//echo var_dump($carrera); exit;
				if($carrera == '') $carrera = '000';

				if($cedula == '') $cedula = '000';
				$nombrecarrera = Carrera::find()
				->where(['idCarr' => $carrera])
				->one();
				$nobrecarrara = '';
				if (isset($nombrecarrera->NombCarr)) $nobrecarrara = $nombrecarrera->NombCarr;
				echo Item::widget([
				'options' => ['class' => 'malla'],
				'header' => '<b>' . $nobrecarrara . '</b>' . '</br>',
				'body' => (app\widgets\Mallas::widget(['carrera' => $carrera,'cedula' => $cedula]))
			]); ?>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>
