<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LibretaCalificacion */
$this->title = 'Crear componente';
$this->params['breadcrumbs'][] = ['label' => 'Lista Componentes', 'url' => ['libretacalificacion/docente', 
									'id'=> $modelDocente->dpa_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="libreta-calificacion-create">

    <h3><?= Html::encode($this->title) ?></h3>
	<address>
		C.I.: <?= $modelDocente->CIInfPer ?> <br>
		Docente: <?= $modelDocente->getNombreDocente() ?> <br>
		Carrera: <?= $modelDocente->carrera->NombCarr ?> <br>
		<b>Asignatura: <?= $modelDocente->asignatura->NombAsig ?> 
		<?php echo '-- '. $modelDocente->idSemestre. ' '. $modelDocente->idParalelo ?></b>
	</address>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
