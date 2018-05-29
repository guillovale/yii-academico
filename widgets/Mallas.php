<?php
namespace app\widgets;
use app\models\MallaCurricular;
use app\models\MallaEstudiante;
use app\models\DetalleMalla;
use app\models\Ingreso;
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
	public $anio_malla = 0;
	public $notas;

	public function run()
	{
		
		$mallas = null;
		#$anio_estudiante = Mallaestudiante::find()
		#		->where(['cedula' => $this->cedula, 'carrera' => $this->carrera])
		#		->one();
		$idmalla = Ingreso::find()
				->where(['CIInfPer' => $this->cedula, 'idcarr' => $this->carrera])
				->orderBy(['fecha'=>SORT_DESC])
				->one();
		#echo var_dump($idmalla); exit;
		if ($idmalla) {
			$this->anio_malla = $idmalla->malla0->detalle;
			#$mallas = MallaCurricular::find()
			#	->leftJoin('asignatura')
			#	->where("anio_habilitacion = $anio_estudiante->anio_habilitacion and idCarr = $this->carrera and imp = 1")
			#	->orderBy(['idSemestre'=>SORT_ASC, 'NombAsig'=>SORT_ASC])
			#	->all();
			$mallas = DetalleMalla::find()
				->joinWith('asignatura')
				->where(['idmalla'=>$idmalla->idmalla, 'estado'=> 1])
				->orderBy(['nivel'=>SORT_ASC, 'NombAsig'=>SORT_ASC])
				->all();

			#echo var_dump(count($mallas)); exit;
			if (empty($mallas))	echo '<p>No hay mallas para mostrar.</p>';
			else	echo '<ul class="list-unstyled">' . $this->renderMallas($mallas) . '</ul>';
		}
		else
			echo '<p>No hay ingreso creado.</p>';
	}

	public function renderMallas($mallas)
	{
		// the current user identity. Null if the user is not authenticated.
		$identity = Yii::$app->user->identity;		
		$notas = $this->getNotas($this->cedula);
		$id_notas = ArrayHelper::getColumn($notas, 'idAsig');
		//echo var_dump($id_notas); exit;
		$equiparada = '';
		$estaenmalla = 0;
		$mallacompleta = 1;
		$items = [];
		$items[] = '<b>' . '<p>' . $this->anio_malla . '</p>'. '</b>';	

				
		foreach ($mallas as $malla) {
			$estaenmalla = 0;
			$equiparada = '';
			$clave = '';
			$clave = array_search($malla->idasignatura, $id_notas);	
	
			if (is_numeric($clave)) {
				$estaenmalla = 1;
				unset($id_notas[$clave]);		
				
			}
			
			else {	
				//if ($malla->idAsig = 'CNA02'){

				//}
				
				$equivalencia = Equivalencia::find()
					->where(['asignatura' => $malla->idasignatura])
					->andWhere(['equivalencia' => $id_notas])
					->one();

				if (!empty($equivalencia)) {
					$estaenmalla = 1;
					$equiparada = $equivalencia->equivalencia;
					
					$key = array_search($equiparada, $id_notas);
					if (is_numeric($key)) {
						unset($id_notas[$key]);
						//ArrayHelper::remove($id_notas, $equivalencia->equivalencia);	
					}

					
				}
			
				else	{
					$estaenmalla = 0;
					if  ($malla->caracter != 'OPCIONAL')
					$mallacompleta = 0;
				}	
			}

			$items[] = $this->renderMalla($malla, $estaenmalla, $equiparada);
		}		
		
		if ( ($identity->idperfil == 'diracad' || $identity->idperfil == 'secacad' || $identity->idperfil == 'centros'
			|| $identity->idperfil == 'sa') 
			&& $mallacompleta == 1 ) {
			$items[] = '</br>';
			$items[] = '<li>' . Html::a(Html::encode("Imprimir Certificado"), 
				['creapdf', 'cedula' => $this->cedula, 'idCarr' => $this->carrera],array('target'=>'_blank')) . '</li>';
		}
		return implode("\n", $items);
	}


	public function renderMalla($malla, $estaenmalla, $equiparada)
	{
		$nombre = ((!empty($malla->asignatura->NombAsig))?$malla->asignatura->NombAsig:'');

		$dato = $malla->nivel . ' ' .$nombre;
		$equivalencia = ($equiparada)?' => '.$equiparada:'';

		if ($estaenmalla == 0) {
			return '<li style="color:red;">' . $dato . ' (' . Html::a(Html::encode($malla->idasignatura), 
				['equivalencia/create', 'idAsig' => $malla->idasignatura]) . ')' . '</li>';
		} else {
			return '<li style="color:blue;">' . $dato . ' (' . $malla->idasignatura . ')'. ' ' . '<b>' . $equivalencia . '</b>'.'</li>';
		}
		
	}

	
	public function getNotas($cedula)
	{
				
		$notas = Notasalumnoasignatura::find()
			->where(['CIInfPer' => $this->cedula, 'aprobada' => 1])
			->all();

		return $notas;
	}


}
