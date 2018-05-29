<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HabilitarDocente;

/**
 * HabilitarDocenteSearch represents the model behind the search form about `app\models\HabilitarDocente`.
 */
class HabilitarDocenteSearch extends HabilitarDocente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'iddocenteperasig', 'hemisemestre'], 'integer'],
            [['componente', 'fechaini', 'fechafin'], 'safe'],
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
        $query = HabilitarDocente::find();

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
            'iddocenteperasig' => $this->iddocenteperasig,
            'hemisemestre' => $this->hemisemestre,
            'fechaini' => $this->fechaini,
            'fechafin' => $this->fechafin,
        ]);

        $query->andFilterWhere(['like', 'componente', $this->componente]);

        return $dataProvider;
    }
}
