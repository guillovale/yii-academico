<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Equivalencia;

/**
 * EquivalenciaSearch represents the model behind the search form about `app\models\Equivalencia`.
 */
class EquivalenciaSearch extends Equivalencia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idequivalencia'], 'integer'],
            [['asignatura', 'equivalencia', 'fecha', 'usuario'], 'safe'],
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
        $query = Equivalencia::find();

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
            'idequivalencia' => $this->idequivalencia,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'asignatura', $this->asignatura])
            ->andFilterWhere(['like', 'equivalencia', $this->equivalencia])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
