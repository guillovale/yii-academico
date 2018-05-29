<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LibretaCalificacion;

/**
 * LibretaCalificacionSearch represents the model behind the search form about `app\models\LibretaCalificacion`.
 */
class LibretaCalificacionSearch extends LibretaCalificacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idcurso','idper', 'iddocenteperasig', 'hemisemestre', 'idparametro', 'idcomponente'], 'integer'],
            [['iddocente', 'fecha', 'tema'], 'safe'],
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
        $query = LibretaCalificacion::find()
		->orderBy(['hemisemestre'=>SORT_ASC, 'idcomponente'=>SORT_ASC]);

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
            'idper' => $this->idper,
            'iddocenteperasig' => $this->iddocenteperasig,
            'fecha' => $this->fecha,
            'hemisemestre' => $this->hemisemestre,
            'idparametro' => $this->idparametro,
            'idcomponente' => $this->idcomponente,
        ]);

        $query->andFilterWhere(['like', 'iddocente', $this->iddocente])
            ->andFilterWhere(['like', 'tema', $this->tema]);

        return $dataProvider;
    }
}
