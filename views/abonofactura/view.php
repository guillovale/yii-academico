<style>
@media print {
   #printPageButton {
    display: none;
  }
  a[href] {
    display: none;
  }

}
</style>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Informacionpersonal;
/* @var $this yii\web\View */
/* @var $model app\models\AbonoFactura */
$this->title = 'COMPROBANTE DE INGRESO';

//$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Abono Facturas', 'url' => ['factura/index']];
//$this->params['breadcrumbs'][] = $this->title;
$id = array('id'=> $this->params['factura']);
?>
<div class="jumbotron">
        
	<?= Html::img('@web/uploads/encabezado.png', ['alt'=>'some', 'class'=>'thing']);?>
        
        
</div>
<div class="abono-factura-view">

	<h3><?= Html::encode($this->title) ?></h3>

    <p>
	<button id="printPageButton", onclick="window.print()">Imprimir</button>
        
        <?php print \yii\helpers\Html::a( 'Regresar', ['abonofactura/abonar', 'FacturaSearch'=>$id], ['class' =>'btn btn-warning']) ?>
    </p>
</br>

   <?php

$hoy = date("j/ n/ Y"); 
print 'Fecha: '.$hoy;                      // 10, 3, 2001

?>
</br>

<address>
		Documento: <?= $model->idfactura ?> <br>
		Alumno: <?= $model->factura->nombreAlumno ?> <br>
		C.I.: <?= $model->factura->cedula ?> <br>
</address>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idfactura',
            'fecha',
            'documento',
            'valor',
            //'usuario',
        ],
    ]) ?>

</div>

</br></br></br></br>
<footer>
 
  <p>---------------------------</p>
  <p>		Firma</p>

</footer>
