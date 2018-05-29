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
/* @var $model app\models\Factura */

// echo Html::img('@web/banner_utelvt.jpg');


$this->title = 'COMPROBANTE DE INGRESO';
//$this->params['breadcrumbs'][] = ['label' => 'Documentos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
// Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])
?>
<div class="jumbotron">
        
	<?= Html::img('@web/uploads/encabezado.png', ['alt'=>'some', 'class'=>'thing']);?>
        
        
</div>
<div class="factura-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
	<button id="printPageButton", onclick="window.print()">Imprimir</button>
        
        <?php print \yii\helpers\Html::a( 'Regresar', Yii::$app->request->referrer, ['class' =>'btn btn-warning']) ?>
    </p>
</br>

<?php

$hoy = date("j/ n/ Y"); 
print 'Fecha: '.$hoy;                      // 10, 3, 2001

?>
</br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
	//	'fecha',            
		'id',
            'cedula',
            'idper',
            //'iva',
            //'descuento',
            //'total',
            'documento',
            'pago',
        ],
    ]) ?>

</div>
</br></br></br></br>
<footer>
 
  <p>---------------------------</p>
  <p>		Firma</p>

</footer>
