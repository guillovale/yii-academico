<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Matricula;
use app\models\Periodolectivo;
use yii\db\Query;

/**
 * EquivalenciaSearch represents the model behind the search form about `app\models\Equivalencia`.
 */
class MatriculaSearch extends Matricula
{
    /**
     * @inheritdoc
     */

	//public $idper;

    public function rules()
    {
        return [
            // [['idequivalencia'], 'integer'],
            [['idMatricula'], 'safe'],
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
    public function search($params)
    {
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$this->idPer;	
		//echo var_dump($params['MatriculaSearch']['idperiodo']); exit;
		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

	
		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'MAX(m.nivel) as nivel'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('carrera c', 'm.idcarr = c.idcarr')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		//->andwhere('idcarr not in("056", "197", "206", "601", "602","603")')
		->andwhere("c.optativa = 0 or c.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC, 'nivel' => SORT_ASC]);
		//->all();
		#echo var_dump($subQuery); exit;
	


	$query->select(['u.idper','u.cedula', 'u.idcarr', 'carrera.nombcarr', 'u.nivel', 'count(u.nivel) as total1', 
					'if(u.nivel > 0, count(u.nivel), 0) as total',
					'if(u.nivel = 0, count(u.nivel), 0) as nivel0'])
	->from(['u' => $subQuery])
	->leftJoin('carrera', 'u.idcarr = carrera.idcarr')
	->groupBy(['u.nivel', 'u.idcarr'])
	->orderBy(['u.idcarr' => SORT_ASC, 'u.nivel' => SORT_ASC]);


	$querytotal->select(['idper','idcarr', 'nombcarr' , 'nivel0'
				
				,'sum(IF(nivel = 1,total,0)) as nivel1', 'sum(IF(nivel = 2,total,0)) as nivel2'
				, 'sum(IF(nivel = 3,total,0)) as nivel3', 'sum(IF(nivel = 4,total,0)) as nivel4'
				, 'sum(IF(nivel = 5,total,0)) as nivel5', 'sum(IF(nivel = 6,total,0)) as nivel6'
				, 'sum(IF(nivel = 7,total,0)) as nivel7', 'sum(IF(nivel = 8,total,0)) as nivel8'
				, 'sum(IF(nivel = 9,total,0)) as nivel9', 'sum(IF(nivel = 10,total,0)) as nivel10'
				, 'sum(total) as sumtotal'

		])
	->from([$query])
	->groupBy(['idcarr']);



        $dataProvider = new ActiveDataProvider([
            'query' => $querytotal,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 200,
			    ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }


	public function reporte_promedio($params)
    {
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$this->idPer;	
		
		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'MAX(m.nivel) as nivel'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('carrera c', 'm.idcarr = c.idcarr')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		//->andwhere('idcarr not in("056", "197", "206", "601", "602","603")')
		->andwhere("c.optativa = 0 or c.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC, 'nivel' => SORT_ASC]);

		$query->select(['u.idper','u.cedula', 'u.idcarr', 'carrera.nombcarr', 
				'informacionpersonal.GeneroPer', 'u.nivel',
				'count(informacionpersonal.GeneroPer) as total',
				'count(IF(informacionpersonal.GeneroPer not in("M", "H"),1,0)) as totalnulos',			
				'if(u.nivel = 0,count(informacionpersonal.GeneroPer),0) as totalsnna',
		])
		->from(['u' => $subQuery])
		->leftJoin('carrera', 'u.idcarr = carrera.idcarr')
		->leftJoin('informacionpersonal', 'u.cedula = informacionpersonal.ciinfper')
		->groupBy(['informacionpersonal.GeneroPer', 'u.idcarr', 'u.nivel'])
		->orderBy(['u.nivel' => SORT_DESC, 'u.idcarr' => SORT_ASC, 'informacionpersonal.GeneroPer' => SORT_ASC]);


		$querytotal->select(['idper','idcarr', 'nombcarr', 
					'sum(IF(GeneroPer = "H" and nivel > 0 ,total,0)) as HOMBRES', 
					'sum(IF(GeneroPer = "M" and nivel > 0,total,0)) as MUJERES', 
					'sum(IF(nivel = 0,total,0)) as SNNA',
					'sum(IF(nivel > 0,total,0)) as sumacarrera', 
					'sum(IF((GeneroPer is null or GeneroPer =""),totalnulos,0)) as sindato',
					'sum(total) as sumtotal'

			])
		->from([$query])
		->groupBy(['idcarr']);



        $dataProvider = new ActiveDataProvider([
            'query' => $querytotal,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 200,
			    ],
        ]);

		//var_dump($params);
		//exit;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        

        return $dataProvider;
    }


	public function reporte_promedio_optativa($params)
    {
	
		$idperiodo = 0;
		$periodo = Periodolectivo::find()
				//->where(['idPer' => $idper])
				->orderBy(['idper' => SORT_DESC])
				->one();
			
		if (!empty($periodo)){
			$idperiodo = $periodo->idper;
			$nombper = $periodo->DescPerLec;
		}
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$idperiodo;	
		//var_dump($idper);
		//exit;
		//if (!empty($periodo)) $idper = $periodo->idper;
	

		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'MAX(m.nivel) as nivel'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('curso_ofertado co', 'm.idcurso = co.id')
		->leftJoin('carrera c', 'm.idcarr = c.idCarr')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		//->andwhere('idcarr not in("056", "197", "206", "601", "602","603")')
		->andwhere("c.optativa = 1")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula', 'c.idCarr'])
		//->groupBy(['f.cedula', 'm.idcarr'])
		->orderBy(['m.idcarr' => SORT_ASC, 'nivel' => SORT_ASC]);
		//->all();
		#echo var_dump($subQuery); exit;

		$query->select(['u.idper','u.cedula', 'u.idcarr', 'carrera.nombcarr', 'u.nivel', 'count(u.nivel) as total'])
		->from(['u' => $subQuery])
		->leftJoin('carrera', 'u.idcarr = carrera.idCarr')
		->groupBy(['u.nivel', 'u.idCarr'])
		->orderBy(['u.idCarr' => SORT_ASC, 'u.nivel' => SORT_ASC]);

		$querytotal->select(['idper','idcarr', 'nombcarr' 
					,'sum(IF(nivel = 0,total,0)) as nivel0', 'sum(IF(nivel = 1,total,0)) as nivel1' 
					,'sum(IF(nivel = 2,total,0)) as nivel2'
					, 'sum(IF(nivel = 3,total,0)) as nivel3', 'sum(IF(nivel = 4,total,0)) as nivel4'
					, 'sum(IF(nivel = 5,total,0)) as nivel5', 'sum(IF(nivel = 6,total,0)) as nivel6'
					, 'sum(IF(nivel = 7,total,0)) as nivel7', 'sum(IF(nivel = 8,total,0)) as nivel8'
					, 'sum(IF(nivel = 9,total,0)) as nivel9', 'sum(IF(nivel = 10,total,0)) as nivel10', 'sum(total) as sumtotal'

			])
		->from([$query])
		->groupBy(['idcarr']);



        $dataProvider = new ActiveDataProvider([
            'query' => $querytotal,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 200,
			    ],
        ]);

		//var_dump($params);
		//exit;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        // $querytotal->andFilterWhere(['like', 'nombcarr', 'ing']);
	

        /*$query->andFilterWhere(['like', 'asignatura', $this->asignatura])
            ->andFilterWhere(['like', 'equivalencia', $this->equivalencia])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);*/

        return $dataProvider;
    }



	public function reporte_etnia($params)
    {
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$this->idPer;

		$subQuery = new Query;
		$query = new Query;
		//$querytotal = new Query;
		
		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'carrera.nombcarr', 
					'informacionpersonal.EtniaPer','tipo_etnia.tet_id','tipo_etnia.tet_nombre'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('curso_ofertado co', 'm.idcurso = co.id')
		->leftJoin('carrera', 'm.idcarr = carrera.idcarr')
		->leftJoin('informacionpersonal', 'f.cedula = informacionpersonal.ciinfper')
		->leftJoin('tipo_etnia', 'tipo_etnia.tet_id = informacionpersonal.EtniaPer')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		->andwhere("carrera.optativa = 0 or carrera.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC])
		->all();

		$query->select(['u.idper','u.cedula', 
				'u.tet_nombre', 'u.EtniaPer',
				'count(u.cedula) as total'
		])
		->from(['u' => $subQuery])
		->groupBy(['u.tet_id'])
		->orderBy(['total' => SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
		    'query' => $query,
			'sort' =>false,
			'pagination' => [
					'pageSize' => 200,
					],
		    ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        

        return $dataProvider;
    }


	public function reporte_estado($params)
    {
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$this->idPer;
		
		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'carrera.nombcarr', 
					'informacionpersonal.EstadoCivilPer','estado_civil.ec_nombre'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('curso_ofertado co', 'm.idcurso = co.id')
		->leftJoin('carrera', 'm.idcarr = carrera.idcarr')
		->leftJoin('informacionpersonal', 'f.cedula = informacionpersonal.ciinfper')
		->leftJoin('estado_civil', 'estado_civil.ec_id = informacionpersonal.EstadoCivilPer')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		->andwhere("carrera.optativa = 0 or carrera.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC])
		->all();

		$query->select(['u.idper','u.cedula', 
				'u.ec_nombre', 'u.EstadoCivilPer',
				'count(u.cedula) as total'
		])
		->from(['u' => $subQuery])
		->groupBy(['u.EstadoCivilPer'])
		->orderBy(['total' => SORT_DESC]);
	
	
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 200,
			    ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

	public function reporte_discapacidad($params)
    {
	
		$idper = (isset($params['MatriculaSearch']['idperiodo']))?($params['MatriculaSearch']['idperiodo']):$this->idPer;
		
		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

		$subQuery->select(['f.idper','f.cedula', 'm.idcarr', 'carrera.nombcarr', 'nivel', 
					'informacionpersonal.tipo_discapacidad','discapacidad.dsp_nombre'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('curso_ofertado co', 'm.idcurso = co.id')
		->leftJoin('carrera', 'm.idcarr = carrera.idcarr')
		->leftJoin('informacionpersonal', 'f.cedula = informacionpersonal.ciinfper')
		->leftJoin('discapacidad', 'discapacidad.dsp_id = informacionpersonal.tipo_discapacidad')
		->where(['f.idper'=> $idper, 'm.estado'=>1])
		->andwhere("carrera.optativa = 0 or carrera.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		->andwhere(['>','nivel',0])
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC])
		->all();

		$query->select(['u.idper','u.cedula', 
				'u.tipo_discapacidad', 'u.dsp_nombre',
				'if(u.nivel > 0, count(u.cedula),0) as total',
				'if(u.nivel = 0,count(u.cedula), 0) as total1'
		])
		->from(['u' => $subQuery])
		->groupBy(['u.tipo_discapacidad'])
		->orderBy(['total' => SORT_DESC]);
	
	
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 200,
			    ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            //$query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }


}
