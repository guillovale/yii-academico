<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Detalleparalelo;

/**
 * DetalleparaleloSearch represents the model behind the search form about `app\models\Detalleparalelo`.
 */
class DetalleparaleloSearch extends Detalleparalelo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddetalleparalelo', 'idparalelo', 'nivel', 'idper', 'cupo', 'habilitado'], 'integer'],
            [['idcarr', 'idasig'], 'safe'],
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
        $query = Detalleparalelo::find();

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
            'iddetalleparalelo' => $this->iddetalleparalelo,
            'idparalelo' => $this->idparalelo,
            'nivel' => $this->nivel,
            'idper' => $this->idper,
            'cupo' => $this->cupo,
            'habilitado' => $this->habilitado,
        ]);

        $query->andFilterWhere(['like', 'idcarr', $this->idcarr])
            ->andFilterWhere(['like', 'idasig', $this->idasig]);

        return $dataProvider;
    }
}
