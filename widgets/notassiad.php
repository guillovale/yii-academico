<?php
namespace app\widgets;
use app\models\MallaCurricular;
use app\models\MallaEstudiante;
use app\models\Asignatura;
use app\models\Notasalumnoasignatura;
use app\models\Equivalencia;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii;

class Mallas extends \yii\base\Widget
{

	public $carrera;
	public $cedula='';
	public $anio_malla = 2014;
	public $notas;

	public function run()
	{
		/*
		if(isset(\Yii::$app->params['mallas']) && is_int(\Yii::$app->params['mallas'])) {
			$limit = \Yii::$app->params['mallas'];
		} else {
			$limit = 100;
		}
		*/
		
		$anio_estudiante = Mallaestudiante::find()
				->where("cedula = $this->cedula and carrera = $this->carrera")
				->one();
		
		if (!empty($anio_estudiante)) $this->anio_malla = $anio_estudiante->anio_habilitacion;

		$mallas = MallaCurricular::find()
				->where("anio_habilitacion = $this->anio_malla and idCarr = $this->carrera")
				->orderBy(['idSemestre'=>SORT_ASC])
				->all();
		if (empty($mallas)) {
			echo '<p>No hay mallas para mostrar.</p>';
		} else {
			
			echo '<ul class="list-unstyled">' . $this->renderMallas($mallas) . '</ul>';
		}
	}
	public function renderMallas($mallas)
	{
		// the current user identity. Null if the user is not authenticated.
		$identity = Yii::$app->user->identity;		
		//$identity = true;
		$notas = $this->getNotas($this->cedula);
		$id_notas = ArrayHelper::getColumn($notas, 'idAsig');
		//$count = count($aprobadas);
		//echo var_dump($ids);
		//exit;
		$estaenmalla = 0;
		$mallacompleta = 1;
		$items = [];

		$items[] = '<b>' . '<p>' . $this->anio_malla . '</p>'. '</b>';		
		
		foreach ($mallas as $malla) {
			//$estaenmalla = 0;
			//$clave = array_search('$malla->idAsig', $ids);
			//echo var_dump($clave);
			//exit;

			if (in_array($malla->idAsig, $id_notas)) $estaenmalla = 1;
			else {



				$equivalencias = Equivalencia::find()
					->where(['asignatura' => $malla->idAsig])
					->andWhere(['equivalencia' => $id_notas])
					->all();


				if (!empty($equivalencias)) $estaenmalla = 1;
				/*

				$equivalencias = $this->buscarEquivalencias($malla);
				$idsasig = ArrayHelper::getColumn($equivalencias, 'asignatura');
				$idsequi = ArrayHelper::getColumn($equivalencias, 'equivalencia');
				if (in_array($malla->idAsig, $idsasig) or in_array($malla->idAsig, $idsequi)) $estaenmalla = 1;

				*/

				else	{
					$estaenmalla = 0; 
					$mallacompleta = 0;
				}
			}

			$items[] = $this->renderMalla($malla, $estaenmalla);
		}		
		
		if ($identity && ($mallacompleta == 1 or $this->carrera == '197' or $this->carrera == '602')) {
			$items[] = '</br>';
			$items[] = '<li>' . Html::a(Html::encode("Imprimir Certificado"), ['creapdf', 'cedula' => $this->cedula, 'idCarr' => $this->carrera],array('target'=>'_blank')) . '</li>';
		}
		//echo var_dump($estaenmalla);

		return implode("\n", $items);
	}

	public function renderMalla($malla, $estaenmalla)
	{
		$asignatura = Asignatura::find()
				->where(['IdAsig' => $malla->idAsig])
				->one();
		

		$nombre = ((!empty($asignatura))?$asignatura['NombAsig']:'');

		$dato = $malla->idSemestre . ' ' .$nombre;

		if ($estaenmalla == 0) {
			return '<li style="color:red;">' . $dato . ' (' . Html::a(Html::encode($malla->idAsig), 
				['equivalencia/create', 'idAsig' => $malla->idAsig]) . ')' . '</li>';
		} else {
			return '<li style="color:blue;">' . $dato . ' (' . $malla->idAsig . ')'.'</li>';
		}
		
		

		//return '<li style="color:blue;">' . $dato . ' (' . $idasignatura . ')'. '</li>';
	}

	
	public function getNotas($cedula)
	{
				
		$notas = Notasalumnoasignatura::find()
			->where(['CIInfPer' => $this->cedula])
			->all();

		return $notas;
	}


}
