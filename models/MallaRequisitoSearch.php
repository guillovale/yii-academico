<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MallaRequisito;

/**
 * MallaRequisitoSearch represents the model behind the search form about `app\models\MallaRequisito`.
 */
class MallaRequisitoSearch extends MallaRequisito
{
	public $carrera;
    public $asignatura;
	public $detalle;
	public $nivel;
	public $idasig;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idmalla', 'idmallarequisito'], 'integer'],
            [['tipo'], 'safe'],
			[['carrera', 'asignatura', 'detalle', 'nivel', 'idasig'], 'safe'],
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
        $query = MallaRequisito::find()
				->joinWith('mallarequisito')
				->joinWith('mallarequisito.malla')
				->joinWith('mallarequisito.asignatura')
				->joinWith('mallarequisito.malla.carrera')
				#->joinWith('mallarequisito')
				->orderBy(['malla_carrera.idcarrera'=>SORT_ASC, 'malla_carrera.detalle'=>SORT_DESC, 
							'detalle_malla.nivel'=>SORT_ASC, 'asignatura.NombAsig'=>SORT_ASC]);

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
            'id' => $this->id,
            'idmalla' => $this->idmalla,
            'idmallarequisito' => $this->idmallarequisito,
        ]);

        $query->andFilterWhere(['like', 'tipo', $this->tipo])
			->andFilterWhere(['like', 'carrera.NombCarr', $this->carrera])
    		->andFilterWhere(['like', 'asignatura.NombAsig', $this->asignatura])
			->andFilterWhere(['like', 'malla_carrera.detalle', $this->detalle])
			->andFilterWhere(['like', 'detalle_malla.nivel', $this->nivel])
			->andFilterWhere(['like', 'detalle_malla.idasignatura', $this->idasig]);

        return $dataProvider;
    }
}
