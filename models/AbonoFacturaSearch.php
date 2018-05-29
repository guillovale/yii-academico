<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AbonoFactura;

/**
 * AbonoFacturaSearch represents the model behind the search form about `app\models\AbonoFactura`.
 */
class AbonoFacturaSearch extends AbonoFactura
{
	public $cedula;
	public $alumno;
	public $tipodocumento;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idfactura'], 'integer'],
            [['fecha', 'documento', 'usuario', 'cedula', 'alumno', 'tipodocumento'], 'safe'],
            [['valor'], 'number'],
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
        $query = AbonoFactura::find()
				->joinWith(['factura'])
				->joinWith(['factura.cedula0']);

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
            'idfactura' => $this->idfactura,
            //'fecha' => $this->fecha,
            'valor' => $this->valor,
        ]);

        $query->andFilterWhere(['like', 'documento', $this->documento])
			->andFilterWhere(['like', 'factura.cedula', $this->cedula])
			->andFilterWhere(['like', 'factura.tipo_documento', $this->tipodocumento])
			->andFilterWhere(['like', 'informacionpersonal.ApellInfPer', $this->alumno])
			->andFilterWhere(['like', 'abono_factura.fecha', $this->fecha])
            ->andFilterWhere(['like', 'abono_factura.usuario', $this->usuario]);

        return $dataProvider;
    }
}
