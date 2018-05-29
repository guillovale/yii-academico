<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExtensionDocente;

/**
 * ExtensionDocenteSearch represents the model behind the search form about `app\models\ExtensionDocente`.
 */
class ExtensionDocenteSearch extends ExtensionDocente
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idcurso'], 'integer'],
            [['fecha_inicio', 'fecha_fin', 'memo'], 'safe'],
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
        $query = ExtensionDocente::find();

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
            'idcurso' => $this->idcurso,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
        ]);

        $query->andFilterWhere(['like', 'memo', $this->memo]);

        return $dataProvider;
    }
}
