<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\models\Notasalumnoasignatura;
use yii\db\Query;
/**
 * NotasalumnoasignaturaSearch represents the model behind the search form about `app\models\Notasalumnoasignatura`.
 */
class NotasalumnoasignaturaSearch extends Notasalumnoasignatura
{
    /**
     * @inheritdoc
     */

	public $nombreCarrera;
	public $semestre;
	public $asignatura;
	
	#public $factura;
	//public $paralelo;
	//public $nivel;
	

    public function rules()
    {
        return [
            [['idnaa', 'idPer', 'asistencia', 'StatusCalif', 'VRepite', 'op1', 'op2', 'op3', 'pierde_x_asistencia', 'repite', 'retirado', 'excluidaxrepitencia', 'excluidaxreingreso', 'excluidaxresolucion', 'convalidacion', 'aprobada', 'anulada', 'arrastre', 'exam_final_atrasado', 'exam_supl_atrasado', 'dpa_id'], 'integer'],
            [['CIInfPer', 'idAsig', 'idMatricula', 'observacion', 'registro_asistencia', 'usu_registro_asistencia', 'registro', 'ultima_modificacion', 'usu_pregistro', 'usu_umodif_registro', 'archivo', 'institucion_proviene', 'porcentaje_convalidacion', 'observacion_efa', 'observacion_espa', 'usu_habilita_efa', 'usu_habilita_espa'], 'safe'],
            [['CAC1', 'CAC2', 'CAC3', 'TCAC', 'CEF', 'CSP', 'CCR', 'CSP2', 'CalifFinal', 'idMc'], 'number'],
		[['nombreCarrera'], 'safe']
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
		#echo var_dump($this->CIInfPer);
		#exit; 
		$cedula = '000001';       
		if(isset($params['NotasalumnoasignaturaSearch']['CIInfPer'])) $cedula = $params['NotasalumnoasignaturaSearch']['CIInfPer'];
		//$query = Notasalumnoasignatura::find();
		$carrera = (isset($params['NotasalumnoasignaturaSearch']['carrera']))?($params['NotasalumnoasignaturaSearch']['carrera']):'';
		$query = Notasalumnoasignatura::find()->where(['notasalumnoasignatura.CIInfPer' => $cedula]);
		#$query = Notasalumnoasignatura::find();

        $dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => false,
			'pagination' => [
				'pageSize' => 100,
			]
        ]);

		/*
		$dataProvider->setSort([
		    'attributes' => [
			'nombreCarrera' => [
			'asc' => ['matricula.idCarr' => SORT_ASC],
			'desc' => ['matricula.idCarr' => SORT_DESC],
			'label' => 'Nombre carrera'
        	    ]
        	,
		'semestre' => [
			'asc' => ['matricula.idsemestre' => SORT_ASC],
			'desc' => ['matricula.idsemestre' => SORT_DESC],
			'label' => 'Nombre carrera'
        	    ]
        	]
    	]);

		*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
		        // $query->where('0=1');
			//$query->joinWith(['nombreCarrera']);
			$query->joinWith(['matricula0']);
        	return $dataProvider;
        }

		
        $query->andFilterWhere([
			 'aprobada' => $this->aprobada,
			 /*
			'notasalumnoasignatura.CIInfPer' => $this->CIInfPer,
           'idnaa' => $this->idnaa,
            'idPer' => $this->idPer,
            'CAC1' => $this->CAC1,
            'CAC2' => $this->CAC2,
            'CAC3' => $this->CAC3,
            'TCAC' => $this->TCAC,
            'CEF' => $this->CEF,
            'CSP' => $this->CSP,
            'CCR' => $this->CCR,
            'CSP2' => $this->CSP2,
            'CalifFinal' => $this->CalifFinal,
            'asistencia' => $this->asistencia,
            'StatusCalif' => $this->StatusCalif,
            'VRepite' => $this->VRepite,
            'op1' => $this->op1,
            'op2' => $this->op2,
            'op3' => $this->op3,
            'pierde_x_asistencia' => $this->pierde_x_asistencia,
            'repite' => $this->repite,
            'retirado' => $this->retirado,
            'excluidaxrepitencia' => $this->excluidaxrepitencia,
            'excluidaxreingreso' => $this->excluidaxreingreso,
            'excluidaxresolucion' => $this->excluidaxresolucion,
            'convalidacion' => $this->convalidacion,
           
            'anulada' => $this->anulada,
            'arrastre' => $this->arrastre,
            'registro_asistencia' => $this->registro_asistencia,
            'registro' => $this->registro,
            'ultima_modificacion' => $this->ultima_modificacion,
            'idMc' => $this->idMc,
            'exam_final_atrasado' => $this->exam_final_atrasado,
            'exam_supl_atrasado' => $this->exam_supl_atrasado,
            'dpa_id' => $this->dpa_id,
			*/
        ]);
		
	
        //$query->andFilterWhere(['like', 'notasalumnoasignatura.CIInfPer', $this->CIInfPer]);
		$query->andFilterWhere([
			'or',
			['like', 'matricula.idcarr', $carrera],
			['like', 'detalle_matricula.idcarr', $carrera],
		]);
		//$query->andFilterWhere(['like', 'matricula.idcarr', $carrera]);
		//$query->andFilterWhere(['detalle_matricula.idcarr'=> $carrera]);
	
		/*
            ->andFilterWhere(['like', 'idAsig', $this->idAsig])
            ->andFilterWhere(['like', 'idMatricula', $this->idMatricula])
            ->andFilterWhere(['like', 'observacion', $this->observacion])
            ->andFilterWhere(['like', 'usu_registro_asistencia', $this->usu_registro_asistencia])
            ->andFilterWhere(['like', 'usu_pregistro', $this->usu_pregistro])
            ->andFilterWhere(['like', 'usu_umodif_registro', $this->usu_umodif_registro])
            ->andFilterWhere(['like', 'archivo', $this->archivo])
            ->andFilterWhere(['like', 'institucion_proviene', $this->institucion_proviene])
            ->andFilterWhere(['like', 'porcentaje_convalidacion', $this->porcentaje_convalidacion])
            ->andFilterWhere(['like', 'observacion_efa', $this->observacion_efa])
            ->andFilterWhere(['like', 'observacion_espa', $this->observacion_espa])
            ->andFilterWhere(['like', 'usu_habilita_efa', $this->usu_habilita_efa])
    	       ->andFilterWhere(['like', 'usu_habilita_espa', $this->usu_habilita_espa]);
		*/
	
	
		$query->joinWith(['matricula0']);
		$query->joinWith(['detallematricula']);
		$query->orderBy(['matricula.idcarr'=>SORT_ASC, 'matricula.idsemestre'=>SORT_ASC, 'idAsig'=>SORT_ASC, 'idPer'=>SORT_ASC]);

        return $dataProvider;
    }

	public function searchaprobados($params) {
	
		$periodo = isset($params["NotasalumnoasignaturaSearch"]['periodo'])?$params["NotasalumnoasignaturaSearch"]['periodo']:0;
		$carrera = isset($params["NotasalumnoasignaturaSearch"]['carrera'])?$params["NotasalumnoasignaturaSearch"]['carrera']:'';
		$nivel = isset($params["NotasalumnoasignaturaSearch"]['nivel'])?$params["NotasalumnoasignaturaSearch"]['nivel']:0;
		$asignatura = isset($params["NotasalumnoasignaturaSearch"]['idAsig'])?$params["NotasalumnoasignaturaSearch"]['idAsig']:'';
		$paralelo = isset($params["NotasalumnoasignaturaSearch"]['idMatricula'])?$params["NotasalumnoasignaturaSearch"]['idMatricula']:'';
		
		$query = Notasalumnoasignatura::find()
						->select(['detalle_matricula.id', 'detalle_matricula.idfactura', 'NombCarr as carrera',
								'factura.idper', 'detalle_matricula.idcarr', 'periodolectivo.DescPerLec as periodo',
								'detalle_matricula.nivel as nivel', 'NombAsig as asignatura', 'notasalumnoasignatura.CIInfPer',
								'concat(informacionpersonal.ApellInfPer, " ",informacionpersonal.ApellMatInfPer, " "
								,informacionpersonal.NombInfPer) as nombre',
								'detalle_matricula.paralelo as paralelo', 'detalle_matricula.idasig',
								'if(notasalumnoasignatura.aprobada = 1, "aprobada", "reprobada") as aprobadas'
								#'(if(notasalumnoasignatura.aprobada = 0, 1, 0)) as reprobadas)'
								#'sum(if(notasalumnoasignatura.aprobada = 1, 1, 0)) as aprobadas', 
								#'sum(if(notasalumnoasignatura.aprobada = 0, 1, 0)) as reprobadas'
							])
						#->joinWith(['factura'])
						#->joinWith(['factura.cedula0'])
						#->joinWith('CIInfPer')
						->joinWith(['detallematricula'])
						->joinWith(['detallematricula.factura'])
						->joinWith(['detallematricula.factura.periodo'])
						->joinWith(['detallematricula.idCarr0'])
						->joinWith(['detallematricula.idAsig'])
						#->joinWith(['curso'])
						#->with( 'notasalumno')
						->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = notasalumnoasignatura.CIInfPer')
						#->joinWith(['notasalumno'])
						#->leftJoin('notasalumnoasignatura', 'notasalumnoasignatura.iddetalle=detalle_matricula.id')
						//->joinWith('matricula.cedula')
						
						->where(['detalle_matricula.estado'=>1, 'factura.tipo_documento'=>'MATRICULA'])
						
						#->groupBy(['factura.idper', 'detalle_matricula.idcarr',
						#		'detalle_matricula.idasig','detalle_matricula.paralelo'])
						->orderBy(['notasalumnoasignatura.idper'=>SORT_ASC, 'detalle_matricula.idcarr'=>SORT_ASC,
								'detalle_matricula.nivel'=>SORT_ASC, 'detalle_matricula.paralelo'=>SORT_ASC, 
								'detalle_matricula.idasig'=>SORT_ASC, 'notasalumnoasignatura.aprobada'=>SORT_DESC,
								'informacionpersonal.ApellInfPer'=>SORT_ASC, 'informacionpersonal.ApellMatInfPer'=>SORT_ASC]);
	
		#echo var_dump($asignatura ,' ', $periodo, ' ', $carrera);exit;
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
				'factura.idPer' => $periodo,
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

	public function searchmejores($params) {
	
		$periodo = isset($params["NotasalumnoasignaturaSearch"]['periodo'])?$params["NotasalumnoasignaturaSearch"]['periodo']:0;
		$carrera = isset($params["NotasalumnoasignaturaSearch"]['carrera'])?$params["NotasalumnoasignaturaSearch"]['carrera']:'';
		$nivel = isset($params["NotasalumnoasignaturaSearch"]['nivel'])?$params["NotasalumnoasignaturaSearch"]['nivel']:0;
		
		$subQuery = new Query;
		$query = new Query;
		$querytotal = new Query;

		$subQuery->select(['f.cedula', 'c.idcarr', 'c.NombCarr as carrera', 'm.nivel'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('carrera c', 'm.idcarr = c.idcarr')
		->where(['f.idper'=> $periodo, 'm.estado'=>1])
		#->andwhere(['m.idcarr'=> $carrera])
		#->andwhere(['nivel'=> $nivel])
		->andwhere("c.optativa = 0 or c.optativa is null")
		->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula'])
		->orderBy(['m.idcarr' => SORT_ASC, 'nivel' => SORT_DESC]);
		#echo var_dump(count($subQuery->all()));exit;
		/*
		$query->select(['n.CIInfPer', 
							'round(sum(n.CalifFinal)/count(n.CalifFinal), 2) as promedio',
							'count(n.CalifFinal) as contador', 
							'if(dm.nivel >= 0, dm.nivel, m.idSemestre) as nivel',
							'if(n.iddetalle > 0, dm.idcarr, m.idcarr) as carrera',
							'concat(informacionpersonal.ApellInfPer, " ", informacionpersonal.ApellMatInfPer, 
							" ", informacionpersonal.NombInfPer) as nombre',

			])*/
		$query->select(['n.CIInfPer', 
						'concat(informacionpersonal.ApellInfPer, " ", informacionpersonal.ApellMatInfPer, 
							" ", informacionpersonal.NombInfPer) as nombre',
						'round(sum(n.CalifFinal)/count(n.CalifFinal), 2) as promedio',
						'count(n.CalifFinal) as contador', 
						'u.idcarr', 'u.carrera', 'u.nivel'])
			->from(['notasalumnoasignatura n'])
			->rightJoin(['u' => $subQuery], 'u.cedula = n.CIInfPer')
		#->leftJoin(['u' => $subQuery], 'CIInfPer = n.CIInfPer')
		#->leftJoin('notasalumnoasignatura n', 'm.id = n.iddetalle')
		#->leftJoin('detalle_matricula dm', 'dm.id = n.iddetalle')
			->leftJoin('informacionpersonal', 'n.CIInfPer = informacionpersonal.CIInfPer')
			->leftJoin('matricula m', 'm.idmatricula = n.idmatricula')
			->leftJoin('detalle_matricula dm', 'dm.id = n.iddetalle')
		#->leftJoin(['detallematricula'])
		#->where(['n.iddetalle' => 'm.'])
		#->andWhere(['=','n.CIInfPer', 'm.cedula'])
		->groupBy(['n.CIInfPer'])
		->orderBy(['u.nivel'=> SORT_DESC, 'promedio' => SORT_DESC]);

		#echo var_dump(count($query->all()), '-', count($subQuery->all()));exit;
		/*
		$querya->select(['u.idper','u.cedula', 'u.idcarr', 'carrera.nombcarr', 
				'informacionpersonal.GeneroPer', 'count(informacionpersonal.GeneroPer) as total',
				'count(IF(informacionpersonal.GeneroPer not in("M", "H"),1,0)) as totalnulos'			
		])
		->from(['u' => $subQuery])
		->leftJoin('carrera', 'u.idcarr = carrera.idcarr')
		->leftJoin('informacionpersonal', 'u.cedula = informacionpersonal.ciinfper')
		->groupBy(['informacionpersonal.GeneroPer', 'u.idcarr'])
		->orderBy(['u.idcarr' => SORT_ASC, 'informacionpersonal.GeneroPer' => SORT_ASC]);


		$querytotal->select(['idper','idcarr', 'nombcarr', 'sum(IF(GeneroPer = "H",total,0)) as HOMBRES', 
					'sum(IF(GeneroPer = "M",total,0)) as MUJERES', 
					'sum(IF((GeneroPer is null or GeneroPer =""),totalnulos,0)) as sindato','sum(totalnulos) as sumtotal'

			])
		->from([$query])
		->groupBy(['idcarr']);		
		
		*/

//**********************************************************************************************************************++

		
		$queryn = Notasalumnoasignatura::find()
						->select(['NombCarr as carrera', 'notasalumnoasignatura.CIInfPer',
								'periodolectivo.DescPerLec as periodo', 'detalle_matricula.nivel',
								'concat(informacionpersonal.ApellInfPer, " ", informacionpersonal.ApellMatInfPer) as nombre',
								'round(sum(notasalumnoasignatura.CalifFinal)/count(notasalumnoasignatura.CalifFinal), 2) as promedio', 
							])
						#->joinWith(['factura'])
						#->joinWith(['factura.cedula0'])
						->joinWith('cedula0')
						->joinWith(['matricula0'])
						->joinWith(['detallematricula'])
						->joinWith(['detallematricula.factura'])
						->joinWith(['detallematricula.factura.periodo'])
						->joinWith(['detallematricula.idCarr0'])
						#->joinWith(['detallematricula.idAsig'])
						#->joinWith(['curso'])
						#->with( 'notasalumno')
						#->leftJoin('notasalumnoasignatura', 'notasalumnoasignatura.iddetalle = detalle_matricula.id')
						#->joinWith(['notasalumno'])
						#->leftJoin('notasalumnoasignatura', 'notasalumnoasignatura.iddetalle=detalle_matricula.id')
						//->joinWith('matricula.cedula')
						
						#->where(['detalle_matricula.estado'=>1, 'factura.tipo_documento'=>'MATRICULA'])
						->andWhere(['or',
						   ['notasalumnoasignatura.aprobada'=>1],
						   ['notasalumnoasignatura.aprobada'=>0]
					   ])
						
						
						->groupBy(['notasalumnoasignatura.CIInfPer'])
						->orderBy(['detalle_matricula.nivel'=>SORT_DESC, 'promedio'=>SORT_DESC]);
	
		#echo var_dump($asignatura ,' ', $periodo, ' ', $carrera);exit;
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 100,],
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
				#'notasalumnoasignatura.idPer' => $periodo,
				#'detalle_matricula.idcarr' => $carrera,
				#'idcarr' => $carrera,
				'u.nivel' => $nivel,				
			]);
			
			$query->andFilterWhere([
				'or',
				['like', 'dm.idcarr', $carrera],
				['like', 'm.idcarr', $carrera],
			]);
			/*
			$query->andFilterWhere([
				'or',
				['<=', 'm.idsemestre', $nivel],
				['<=', 'dm.nivel', $nivel],
			]);
			*/
		    #$query->andFilterWhere(['<=', 'detalle_matricula.nivel', $nivel]);
			#$query->andFilterWhere(['<=', 'matricula.idsemestre', $nivel]);
		 	#$query->andFilterWhere(['like', 'idasig', $this->idasig]);
			#echo var_dump($dataProvider->getModels());exit;
			return $dataProvider;
		
	}

	public function searchsnna($params) {
		
		$periodo = isset($params["NotasalumnoasignaturaSearch"]['periodo'])?
					$params["NotasalumnoasignaturaSearch"]['periodo']:$this->idPer;
		$carrera = isset($params["NotasalumnoasignaturaSearch"]['carrera'])?$params["NotasalumnoasignaturaSearch"]['carrera']:'';
		$query = new Query;	
		
		$sql = "SELECT periodolectivo.DescPerLec as período, notasalumnoasignatura.idPer, malla_carrera.idcarrera, 
				carrera.NombCarr as carrera, notasalumnoasignatura.CIInfPer as cédula,
				concat(informacionpersonal.ApellInfPer, ' ', informacionpersonal.ApellMatInfPer, ' ', 
				informacionpersonal.NombInfPer) as nombre,
				notasalumnoasignatura.idAsig, notasalumnoasignatura.CalifFinal, notasalumnoasignatura.asistencia,
				detalle_malla.peso, round(sum(notasalumnoasignatura.CalifFinal*detalle_malla.peso/100), 2) as sumaNota, 
				round(sum(notasalumnoasignatura.asistencia*detalle_malla.peso/100), 0) as sumaAsistencia,
				if( (round(sum(notasalumnoasignatura.CalifFinal*detalle_malla.peso/100), 2) >= 8 
				 and round(sum(notasalumnoasignatura.asistencia*detalle_malla.peso/100), 0) >= 70 ), 
				'APROBADO', 'REPROBADO' ) AS Estado, count(notasalumnoasignatura.idAsig) as contador
				FROM notasalumnoasignatura
                LEFT JOIN periodolectivo on periodolectivo.idper = notasalumnoasignatura.idPer
                LEFT JOIN informacionpersonal on informacionpersonal.CIInfPer = notasalumnoasignatura.CIInfPer
				LEFT JOIN detalle_matricula on detalle_matricula.id = notasalumnoasignatura.iddetalle
				LEFT JOIN curso_ofertado on curso_ofertado.id = detalle_matricula.idcurso
				LEFT JOIN detalle_malla on detalle_malla.id = curso_ofertado.iddetallemalla
				LEFT JOIN malla_carrera on malla_carrera.id = detalle_malla.idmalla
                LEFT JOIN carrera on carrera.idCarr = malla_carrera.idcarrera
				where malla_carrera.detalle like :snna and  malla_carrera.idcarrera like :carrera
				and notasalumnoasignatura.idPer = :periodo
				GROUP BY malla_carrera.idcarrera, nombre";
		$sql1 = ' select count(*) from (
				SELECT notasalumnoasignatura.CIInfPer
				FROM notasalumnoasignatura
                LEFT JOIN periodolectivo on periodolectivo.idper = notasalumnoasignatura.idPer
                LEFT JOIN detalle_matricula on detalle_matricula.id = notasalumnoasignatura.iddetalle
				LEFT JOIN curso_ofertado on curso_ofertado.id = detalle_matricula.idcurso
				LEFT JOIN detalle_malla on detalle_malla.id = curso_ofertado.iddetallemalla
				LEFT JOIN malla_carrera on malla_carrera.id = detalle_malla.idmalla
				where malla_carrera.detalle like :snna and
				malla_carrera.idcarrera like :carrera and notasalumnoasignatura.idPer = :periodo  
				GROUP BY malla_carrera.idcarrera, notasalumnoasignatura.CIInfPer) c
				';
		
		$totalCount = Yii::$app->db->
				createCommand($sql1, ['snna'=>'%SNNA%', 'carrera'=>$carrera, 'periodo'=> $periodo])->queryScalar();	
		#echo var_dump($totalCount, $periodo, $carrera);exit;
		$dataProvider = new SqlDataProvider([
			'sql' => $sql,
			'params' => ['snna'=>'%SNNA%', 'carrera'=>$carrera, 'periodo'=> $periodo],
			'totalCount' => $totalCount,
			'pagination' => ['pageSize' => 500,],
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
				'notasalumnoasignatura.idPer' => $periodo,
				'malla_carrera.idcarr' => $carrera,
				
			]);

		        #$query->andFilterWhere(['like', 'detalle_matricula.idasig', $asignatura]);
		 //           ->andFilterWhere(['like', 'idasig', $this->idasig]);
			#echo var_dump($dataProvider->getModels());exit;
			return $dataProvider;
		
	}


}
