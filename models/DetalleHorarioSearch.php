<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetalleHorario;

/**
 * DetalleHorarioSearch represents the model behind the search form about `app\models\DetalleHorario`.
 */
class DetalleHorarioSearch extends DetalleHorario
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idhorario', 'idcurso'], 'integer'],
            [['dia', 'hora_inicio', 'hora_fin'], 'safe'],
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
        $query = DetalleHorario::find();

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
            'idhorario' => $this->idhorario,
            'idcurso' => $this->idcurso,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
        ]);

        $query->andFilterWhere(['like', 'dia', $this->dia]);

        return $dataProvider;
    }
}
