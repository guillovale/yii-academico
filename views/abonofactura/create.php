<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AbonoFactura */
#echo var_dump(['FacturaSearch']); exit;
$this->title = 'Pago documento No. '. $this->params['factura'];
$id = array('id'=> $this->params['factura']);
$this->params['breadcrumbs'][] = ['label' => 'Pago documento', 'url' => ['abonofactura/abonar', 'FacturaSearch'=>$id]];
$this->params['breadcrumbs'][] = $this->title;
$suma = (float) $this->params['suma'];
$total = (float) $this->params['total'];
$saldo = $total - $suma;
?>
<div class="row">
<div class="col-xs-4">

    <h3><?= Html::encode($this->title) ?></h3>

	<address>
		Alumno : <?= $this->params['alumno'] ?><br>
		CI. : <?= $this->params['cedula'] ?><br>
		Total pagos $: <?= $suma ?> <br>
		Total documento $: <?= $total ?> <br>
		<b>
		Saldo $: <?= $saldo ?>
		</b>
	</address>

</div>

<div class="col-xs-8">
	
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
