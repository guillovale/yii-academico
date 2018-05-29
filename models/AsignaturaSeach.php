<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Asignatura;

/**
 * AsignaturaSeach represents the model behind the search form about `app\models\Asignatura`.
 */
class AsignaturaSeach extends Asignatura
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdAsig', 'NombAsig', 'ColorAsig'], 'safe'],
            [['StatusAsig'], 'integer'],
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
        $query = Asignatura::find();

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
            'StatusAsig' => $this->StatusAsig,
        ]);

        $query->andFilterWhere(['like', 'IdAsig', $this->IdAsig])
            ->andFilterWhere(['like', 'NombAsig', $this->NombAsig])
            ->andFilterWhere(['like', 'ColorAsig', $this->ColorAsig]);

        return $dataProvider;
    }
}
