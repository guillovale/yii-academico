<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetalleMatricula;

/**
 * DetalleMatriculaSearch represents the model behind the search form about `app\models\DetalleMatricula`.
 */
class DetalleMatriculaSearch extends DetalleMatricula
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfactura', 'idnota', 'credito', 'vrepite'], 'integer'],
            [['idmatricula', 'idasig', 'fecha'], 'safe'],
            [['costo'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
	//echo var_dump($params["DetalleMatriculaSearch"]['periodo']);exit;
		$periodo = isset($params["DetalleMatriculaSearch"]['periodo'])?$params["DetalleMatriculaSearch"]['periodo']:'';
		$carrera = isset($params["DetalleMatriculaSearch"]['carrera'])?$params["DetalleMatriculaSearch"]['carrera']:'';
		$nivel = isset($params["DetalleMatriculaSearch"]['nivel'])?$params["DetalleMatriculaSearch"]['nivel']:'';
		$asignatura = isset($params["DetalleMatriculaSearch"]['idasig'])?$params["DetalleMatriculaSearch"]['idasig']:'';
		$paralelo = isset($params["DetalleMatriculaSearch"]['paralelo'])?$params["DetalleMatriculaSearch"]['paralelo']:'';	 
		$query = DetalleMatricula::find()
						->joinWith('factura')
						->joinWith(['factura.cedula0'])
						//->joinWith('informacionpersonal')
						//->joinWith(['matricula.cedula'])
						//->joinWith('matricula.cedula')
						->select(['detalle_matricula.id', 'idfactura', 'idmatricula', 'factura.idper', 'idcarr', 'nivel', 'paralelo', 
							'estado', 'idasig', 'idcurso', 'COUNT(*) AS cnt'])
						->where(['factura.idper'=>$periodo])
						->andWhere(['idcarr'=>$carrera])
						->andWhere(['nivel'=> $nivel])
						->andWhere(['idasig'=> $asignatura])
						//->andWhere(['detalle_matricula.estado'=> 1])
						->andFilterWhere(['in', 'paralelo', $paralelo])
						->groupBy(['idcarr', 'idasig','paralelo', 'factura.cedula'])
						->orderBy(['nivel'=>SORT_ASC, 'paralelo'=>SORT_ASC, 
								'informacionpersonal.ApellinfPer'=>SORT_ASC]);
	
	//					->andWhere(['!=','status',2]);
	//        $query = DetalleMatricula::find();
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 80,],
		]);

			$this->load($params);

			if (!$this->validate()) {
				// uncomment the following line if you do not want to return any records when validation fails
				// $query->where('0=1');
				return $dataProvider;
			}

			$query->andFilterWhere([
				'id' => $this->id,
				'periodo' => $this->periodo,
				'idnota' => $this->idnota,
				'credito' => $this->credito,
				'vrepite' => $this->vrepite,
				'costo' => $this->costo,
				'fecha' => $this->fecha,
			]);

		 //       $query->andFilterWhere(['like', 'matricula.idper', '107'])
		 //           ->andFilterWhere(['like', 'idasig', $this->idasig]);

			return $dataProvider;
		
	}

	public function searchaprobados($params) {
	
		$periodo = isset($params["DetalleMatriculaSearch"]['periodo'])?$params["DetalleMatriculaSearch"]['periodo']:0;
		$carrera = isset($params["DetalleMatriculaSearch"]['carrera'])?$params["DetalleMatriculaSearch"]['carrera']:'';
		$nivel = isset($params["DetalleMatriculaSearch"]['nivel'])?$params["DetalleMatriculaSearch"]['nivel']:0;
		$asignatura = isset($params["DetalleMatriculaSearch"]['idasig'])?$params["DetalleMatriculaSearch"]['idasig']:'';
		$paralelo = isset($params["DetalleMatriculaSearch"]['paralelo'])?$params["DetalleMatriculaSearch"]['paralelo']:'';
		$sql = 'SELECT m.id, f.idper, 
				m.idcarr, m.nivel, m.paralelo,
				m.idasig,
				sum(if(n.aprobada = 1, 1, 0)) as aprobada ,
				sum(if(n.aprobada = 0, 1, 0)) as reprobada
				FROM detalle_matricula m, notasalumnoasignatura n, factura f
				WHERE m.estado = 1 
				and f.tipo_documento = "MATRICULA"
				and f.id = m.idfactura
				and n.iddetalle = m.id
				and f.idper = :periodo
				and m.idcarr = :carrera
				GROUP by f.idper, m.idcarr, m.idasig, 
					m.nivel, m.paralelo
				ORDER by m.idcarr, m.nivel, 
					m.paralelo, m.idasig';
		$query1 = DetalleMatricula::findBySql($sql, [':periodo' => $periodo, ':carrera' => $carrera]);
		$query = DetalleMatricula::find()
						->select(['detalle_matricula.id', 'detalle_matricula.idfactura', 
								'factura.idper', 'detalle_matricula.idcarr', 'detalle_matricula.nivel', 
								'detalle_matricula.paralelo', 'detalle_matricula.idasig', 'curso_ofertado.iddocente',
								'detalle_matricula.idcurso',
								'sum(if(notasalumnoasignatura.aprobada = 1, 1, 0)) as aprobada', 
								'sum(if(notasalumnoasignatura.aprobada = 0, 1, 0)) as reprobada'
							])
						->joinWith(['factura'])
						#->joinWith(['factura.cedula0'])
						#->joinWith('informacionpersonal')
						->joinWith(['factura'])
						->joinWith(['curso'])
						#->with( 'notasalumno')
						#->leftJoin('notasalumnoasignatura', 'notasalumnoasignatura.iddetalle = detalle_matricula.id')
						->joinWith(['notasalumno'])
						#->leftJoin('notasalumnoasignatura', 'notasalumnoasignatura.iddetalle=detalle_matricula.id')
						//->joinWith('matricula.cedula')
						
						->where(['detalle_matricula.estado'=>1, 'factura.tipo_documento'=>'MATRICULA'])
						
						->groupBy(['factura.idper', 'detalle_matricula.idcarr',
								'detalle_matricula.idasig','detalle_matricula.paralelo'])
						->orderBy(['factura.idper'=>SORT_ASC, 'detalle_matricula.idcarr'=>SORT_ASC,
								'detalle_matricula.nivel'=>SORT_ASC, 'detalle_matricula.paralelo'=>SORT_ASC, 
								'detalle_matricula.idasig'=>SORT_ASC]);
	
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 80,],
		]);

			$this->load($params);

			if (!$this->validate()) {
				// uncomment the following line if you do not want to return any records when validation fails
				// $query->where('0=1');
				return $dataProvider;
			}
			#echo var_dump($asignatura); exit;
			$query->andFilterWhere([
				#'id' => $this->id,
				'factura.idper' => $periodo,
				'detalle_matricula.idcarr' => $carrera,
				'detalle_matricula.nivel' => $nivel,
				'detalle_matricula.idasig' => $asignatura,
				'detalle_matricula.paralelo' => $paralelo,
				
			]);

		        #$query->andFilterWhere(['like', 'detalle_matricula.idasig', $asignatura]);
		 //           ->andFilterWhere(['like', 'idasig', $this->idasig]);
			#echo var_dump($dataProvider->getModels());exit;
			return $dataProvider;
		
	}
}
