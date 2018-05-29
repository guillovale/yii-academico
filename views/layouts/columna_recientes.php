<?php $this->beginContent('@app/views/layouts/main.php'); ?>
        
<div class="row">

<div class="col-md-8">
	<div style="font-size:14px;">
		<?= $content; ?>
	</div>
</div>


<div class="col-md-4">
	<div style="font-size:11px;">
			

	   		<h2 style="color:blue;">Recientes</h2>
			</br></br>
			
			<?php
			use yii\grid\GridView;
			use app\models\Notasalumnoasignatura;
			use yii\helpers\Html;
						
			//use app\models\Matricula;
			//use app\models\Asignatura;
			use yii\data\ActiveDataProvider;
			


			$cedula = (isset($_GET['cedula']) ? $_GET['cedula'] : '');
			
			$dataProvider = new ActiveDataProvider([
			    'query' => (Notasalumnoasignatura::find()
					->joinWith('matricula0')
					->joinWith('idAsig0')
					->joinWith('matricula0.idCarr0')
					->where(['notasalumnoasignatura.CIInfPer' => $cedula])
					->andwhere(['!=', 'notasalumnoasignatura.observacion_efa', ''])
					->orderBy('carrera.NombCarr ASC, matricula.idSemestre ASC, asignatura.NombAsig ASC')
					),
					
			    'pagination' => [
				'pageSize' => 50,
			    ],
			
				'sort' =>false,
			]);		
		
			echo GridView::widget([
				'dataProvider' => $dataProvider,


				'columns' => [
					//['class' => 'yii\grid\SerialColumn'],
					//'matricula.idCarr',
					'NombreCarrera',
					'Nivel',
					'idAsig',
					'Asignatura',
					'CalifFinal',
					'observacion',
					
					/*
					[
						//'attribute'=>'nombreCarrera',
						'label'=>'Estado',
						'format'=>'text',//raw, html
						'content'=>function($data){
								//return ($data->aprobada) == 1?"APROBADA":"REPROBADA";
								//return $data->aprobada;
								if ($data->aprobada == 1)
									return Html::a('<span class="glyphicon glyphicon-ok"></span>');
								else
									return Html::a('<span class="glyphicon glyphicon-remove"></span>');

							}
					],*/

					//'ultima_modificacion'					
					    // you may configure additional properties here
					
				],
			    
			]);		

		
	?>


	</div>
   
  </div>
  
</div>

	
<?php $this->endContent();
