<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LibretaCalificacion */
$this->title = 'Crear componente';
$this->params['breadcrumbs'][] = ['label' => 'Lista Componentes', 'url' => ['libretacalificacion/index', 
									'idcurso'=> $modelcurso->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libreta-calificacion-create">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I.: <?= $modelcurso->iddocente ?> <br>
		Docente: <?= $modelcurso->getNombreDocente() ?> <br>
		Carrera: <?= $modelcurso->detallemalla->malla->carrera->NombCarr ?> <br>
		<b>Asignatura: <?= $modelcurso->detallemalla->asignatura->NombAsig ?> 
		<?php echo '-- '. $modelcurso->nivel. ' '. $modelcurso->paralelo ?></b>
	</address>
    <?= $this->render('_formcrear', [
        'model' => $model,
    ]) ?>

</div>
