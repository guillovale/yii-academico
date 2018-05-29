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
		<div style = "font-size:10px" class="col-xs-5 sidebar">
			</br></br>

			<?php
				$alumno = $this->params['alumno'];
			?>
			
			<h4><b><p><?php if($alumno) echo $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' . $alumno->NombInfPer ?> </p></b></h4>
			</br>
	
			<table class="table table-condensed">
			<?php 
				use yii\grid\GridView;
				use yii\data\ActiveDataProvider;
				use yii\db\ActiveRecord;
				//use yii\db\QueryInterface;
				//echo var_dump($this->params['matricula']); exit;	

			if (isset($this->params['matricula'])){
				//echo var_dump($this->params['matricula']); exit;
				$query = $this->params['matricula'];
				$dataProvider = new ActiveDataProvider([
						'query' => $query,
						'pagination' => [
							'pageSize' => 50,
						],
						'sort' =>false,
					]);
				if ($dataProvider) {
				
				//echo var_dump($dataProvider); exit;
				echo GridView::widget([
						'dataProvider' => $dataProvider,
						'columns' => [
							//['class' => 'yii\grid\SerialColumn'],
							'idmatricula',
							'idfactura',
							//'nivel',
							'idasig',
							'asignatura',					
							//'paralelo',
							//'NombreCarrera',
							'matricula.idParalelo',
							//'matricula.statusMatricula',
							[
								'label' => 'Estado',
								'format' => 'raw',
								'value' => 'matricula.statusMatricula',
								'contentOptions'=> function($data){
									if (isset($data->matricula->statusMatricula)) {	
										if ($data->matricula->statusMatricula == 'PENDIENTE'){
											return ['style'=>'color: red;']; // <-- right here
										}
									}
									else
										return ['style'=>'color: black;'];
									
								},
							],
							//'cnt',
						],
					]);
				}	
			}	
			
			?>
			</table>
		</div>
	</div>
</div>

<?php $this->endContent(); ?>
