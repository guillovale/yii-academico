<?php $this->beginContent('@app/views/layouts/main.php'); ?>
        
<div class="row">

<div class="col-md-6">
	<div style="font-size:11px;">
		<?= $content; ?>
	</div>
</div>


<div class="col-md-6">
	<div style="font-size:11px;">
			

	   		<h2>Notas siad</h2>
			</br></br>
			<?php
				use app\models\Informacionpersonal;
				$cedula = (isset($_GET['NotasSicSearch']['cedula']) ? $_GET['NotasSicSearch']['cedula'] : '');
				$alumno = Informacionpersonal::find()
					->where(['CIInfPer' => $cedula])
					->one();
			?>
			
			<h4 style="color:blue;"><p>Alumno: <?php if($alumno) echo $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' . $alumno->NombInfPer ?> </p></h4>
			</br></br></br>

			<?php
			use yii\grid\GridView;
			use app\models\Notasalumnoasignatura;
			use yii\helpers\Html;
			
			//use app\models\Matricula;
			//use app\models\Asignatura;
			use yii\data\ActiveDataProvider;
			
			
			$dataProvider = new ActiveDataProvider([
			    'query' => Notasalumnoasignatura::find()
					->joinWith('matricula0')
					->joinWith('idAsig0')
					->joinWith('matricula0.idCarr0')
					->where(['notasalumnoasignatura.CIInfPer' => $cedula])
					->orderBy('carrera.NombCarr ASC, matricula.idSemestre ASC, asignatura.NombAsig ASC'),
			    'pagination' => [
				'pageSize' => 100,
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
					//'aprobada',

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
					],

					//'ultima_modificacion'					
					    // you may configure additional properties here
					
				],
			    
			]);		

		
	?>


	</div>
   
  </div>
  
</div>
	         

	
<?php $this->endContent();
