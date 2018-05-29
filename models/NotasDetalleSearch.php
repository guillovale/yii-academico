<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NotasDetalle;

/**
 * NotasDetalleSearch represents the model behind the search form about `app\models\NotasDetalle`.
 */
class NotasDetalleSearch extends NotasDetalle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idnota', 'idlibreta', 'iddetallematricula'], 'integer'],
            [['nota'], 'number'],
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
	//$this->load($params);
        $query = NotasDetalle::find()
		->leftJoin('libreta_calificacion', 'libreta_calificacion.id = notas_detalle.idlibreta')
		->orderBy(['hemisemestre'=>SORT_ASC, 'idcomponente'=>SORT_ASC]);
		//->where(['iddetallematricula' => $this->iddetallematricula]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
	//echo var_dump($dataProvider->getModels()); exit;
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
	
        $query->andFilterWhere([
            'idnota' => $this->idnota,
            'idlibreta' => $this->idlibreta,
            'iddetallematricula' => $this->iddetallematricula,
            'nota' => $this->nota,
        ]);
	
        return $dataProvider;
    }
}
