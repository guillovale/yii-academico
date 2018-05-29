<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Notasalumnoasignatura */

$this->title = $model->idAsig;
$this->params['breadcrumbs'][] = ['label' => 'Notasalumnoasignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$url = Yii::$app->request->url;//yii::$app->session->get('url');

// <?= Html::a('Cancelar', [$url], ['class' => 'btn btn-warning'])

?>
<div class="notasalumnoasignatura-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Actualizar', ['update', 'id' => $model->idnaa], ['class' => 'btn btn-primary']) ?>
        
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'CIInfPer',
            'idAsig',
            'idPer',
            'CalifFinal',
            'asistencia',
            'StatusCalif',
            'idMatricula',
            'VRepite',
            'observacion',
            'excluidaxrepitencia',
            'excluidaxreingreso',
            'excluidaxresolucion',
            'aprobada',
            
        ],
    ]) ?>

</div>
