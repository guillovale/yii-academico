<?php $this->beginContent('@app/views/layouts/main.php'); ?>
        <div class="container">
	<aside>
   		<h2>Malla Curricular</h2>
		

		<?php
		use yii\widgets\ActiveForm;
		use zii\widgets;
		/*
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operaciones',
		));
		
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));

		$this->endWidget();
		*/

		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Recientes',
			));
			
		
		// if (Yii::app()->controller->action->id == "admin" and Yii::app()->controller->id == "matricula")
		//{ 
			
			$usuario = Yii::app()->user->Id;
			//$usuario1 = concat(',$usuario1,');
			$select = "select * from notasalumnoasignatura where (observacion = 'HOMOLOGADA' 
					OR observacion = 'CONVALIDADA') and usu_pregistro = ";
			$select = $select . "'" . $usuario . "'" . " order by registro DESC limit 10";
			
			if ($recientes = Notasalumnoasignatura::model()->findAllBySql($select))
			{
			 	$tempCed = '';
				$tempcarr = '';
				foreach($recientes as $reciente)
				{
					$alumno = Informacionpersonal::model()->findByAttributes(array(
							'CIInfPer'=>$reciente->CIInfPer));
					if($alumno && ($alumno->CIInfPer != $tempCed))
					{
						echo "<br>";
						echo $alumno->ApellInfPer.' '.$alumno->NombInfPer;
						$tempCed = $alumno->CIInfPer;
						echo "<br>";
					}
					
					$matricula = Matricula::model()->findByAttributes(array(
							'idMatricula'=>$reciente->idMatricula));
					if($matricula && ($matricula->idCarr!=$tempcarr))
					{
						$carrera = Carrera::model()->findByAttributes(array(
							'idCarr'=>$matricula->idCarr));
						echo "<br>{$carrera->NombCarr}";
						$tempcarr = $matricula->idCarr;
					}
					
					$asignatura = Asignatura::model()->findByAttributes(array(
							'IdAsig'=>$reciente->idAsig));
					
					echo "<li>{$matricula->idsemestre} / {$asignatura->NombAsig}</li>";
				}
			} 
			
			//$this->endWidget();
		//}
		
		$this->endWidget();
	?>



   
	</aside>


	
	<div id="content" class="col-sm-4">
                    <?php echo $content; ?>
            </div><!-- content -->

        </div>            

	
<?php $this->endContent();
