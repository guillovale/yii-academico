<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
$this->title = 'UTELVT';
?>
<div class="site-index">

    <div class="jumbotron">
        
	<?= Html::img('@web/uploads/banner_utelvt.jpg', ['width'=>'1200','height'=>'200','alt'=>'some', 'class'=>'thing']);?>
        
        
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Documentación</h2>

                <p>Bienvenidos
			La Universidad Técnica Luis Vargas Torres, 
			cuyas siglas son UTE – LVT, es un Centro de Educación Superior Estatal
			creado mediante Ley No. 70-16 del 4 de Mayo de 1970, 
			y promulgada en el Registro Oficial No. 436 de fecha 21 del mismo mes y año.</p>

                <p><a class="btn btn-default" href="#">Documentación &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Foros</h2>

                <p>UTELVT</p>

                <p><a class="btn btn-default" href="#">Foros &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Extensiones</h2>

                <p>UTELVT</p>

                <p><a class="btn btn-default" href="#">Extensiones &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
