<?php $this->beginContent('@app/views/layouts/main.php'); ?>
        
<div class="row">

<div class="col-md-6">
	<div style="font-size:14px;">
		<?= $content; ?>
	</div>
</div>


<div class="col-md-6">
	<div style="font-size:11px;">
		<h4 style="color:blue;">Matriculados</h4>
		</br></br>
			
		<?php
			use yii\grid\GridView;
			use yii\data\ActiveDataProvider;
			//use yii\db\ActiveRecord;

			if (isset($this->params['detalleasig'])){
				$query = $this->params['detalleasig'];
									
				$dataProvider = new ActiveDataProvider([
					'query' => $query,
					'pagination' => [
						'pageSize' => 50,
					],
					'sort' =>false,
				]);				

				echo GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						//['class' => 'yii\grid\SerialColumn'],
						'idcurso',
						#'carrera',
						'nivel',
						//'idasig',
						'asignatura',					
						'paralelo',
						'curso.cupo',
						//'NombreCarrera',
						//'matricula->idParalelo',
						'cnt',
						'curso.fecha_fin',
					],
				]);		
			}
		
		?>


	</div>
   
  </div>
  
</div>

	
<?php $this->endContent();
