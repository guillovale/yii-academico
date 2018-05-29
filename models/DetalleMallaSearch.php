<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetalleMalla;

/**
 * DetalleMallaSearch represents the model behind the search form about `app\models\DetalleMalla`.
 */
class DetalleMallaSearch extends DetalleMalla
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idmalla', 'nivel', 'credito', 'estado'], 'integer'],
            [['idasignatura', 'caracter'], 'safe'],
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
        $query = DetalleMalla::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
				'pageSize' => 100,
			],
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
            'nivel' => $this->nivel,
            'credito' => $this->credito,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'idasignatura', $this->idasignatura])
            ->andFilterWhere(['like', 'caracter', $this->caracter]);

        return $dataProvider;
    }
}
