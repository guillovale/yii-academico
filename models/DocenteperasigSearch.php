<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Docenteperasig;

/**
 * DocenteperasigSearch represents the model behind the search form about `app\models\Docenteperasig`.
 */
class DocenteperasigSearch extends Docenteperasig
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dpa_id', 'idPer', 'idAnio', 'idSemestre', 'status', 'id_actdist', 'transf_asistencia', 'transf_frecuente', 'transf_parcial', 'transf_final', 'arrastre', 'publicar'], 'integer'],
            [['CIInfPer', 'idAsig', 'idCarr', 'idParalelo', 'tipo_orgmalla'], 'safe'],
            [['idMc', 'id_contdoc'], 'number'],
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
		$cedula = (isset($_GET['DocenteperasigSearch']['CIInfPer'])?$_GET['DocenteperasigSearch']['CIInfPer']:'');
		$periodo = (isset($_GET['DocenteperasigSearch']['idPer'])?$_GET['DocenteperasigSearch']['idPer']:'');
		$query = Docenteperasig::find()
				->orderBy(['idPer'=>SORT_DESC]);

	    $dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    $this->load($params);

	    if (!$this->validate()) {
	        // uncomment the following line if you do not want to return any records when validation fails
	        // $query->where('0=1');
	        return $dataProvider;
	    }

	    $query->andFilterWhere([
	        //'dpa_id' => $this->dpa_id,
	        'idPer' => $periodo,
			'CIInfPer' => $this->CIInfPer,
		        //'idAnio' => $this->idAnio,
	        'idSemestre' => $this->idSemestre,
	        #'status' => $this->status,
		        //'idMc' => $this->idMc,
		        //'id_actdist' => $this->id_actdist,
		        //'id_contdoc' => $this->id_contdoc,
		        //'transf_asistencia' => $this->transf_asistencia,
		        //'transf_frecuente' => $this->transf_frecuente,
		        //'transf_parcial' => $this->transf_parcial,
		        //'transf_final' => $this->transf_final,
		        //'arrastre' => $this->arrastre,
		        //'extra' => $this->extra,
		]);

		    //$query->andFilterWhere(['like', 'CIInfPer', $this->CIInfPer])
		 $query ->andFilterWhere(['like', 'idAsig', $this->idAsig])
		 	    ->andFilterWhere(['like', 'idCarr', $this->idCarr])
		 		->andFilterWhere(['like', 'idParalelo', $this->idParalelo]);
		        //->andFilterWhere(['like', 'tipo_orgmalla', $this->tipo_orgmalla]);

		 return $dataProvider;
		#}
		#else {
		#	$query = Docenteperasig::find()->where(['dpa_id' => -1]);
		#	$dataProvider = new ActiveDataProvider([
		#        'query' => $query,
		#    	]);
		#	return $dataProvider;
		#}
    }
}
