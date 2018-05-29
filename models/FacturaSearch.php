<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Factura;

/**
 * FacturaSearch represents the model behind the search form about `app\models\Factura`.
 */
class FacturaSearch extends Factura
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idper'], 'integer'],
            [['cedula', 'fecha', 'documento'], 'safe'],
            [['total', 'pago'], 'number'],
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
		//$request = Yii::$app->request;
		//$get = $request->get();
		$documento = (isset($_GET['FacturaSearch']['id'])?$_GET['FacturaSearch']['id']:'');
		$cedula = (isset($_GET['FacturaSearch']['cedula'])?$_GET['FacturaSearch']['cedula']:'');
		//echo var_dump($documento); exit;
		if ( (isset($documento) &&  $documento != '') || (isset($cedula) &&  $cedula != '') ) {
		    $query = Factura::find()->orderBy(['idper' => SORT_DESC]);

		    $dataProvider = new ActiveDataProvider([
		        'query' => $query,
		    ]);

		    $this->load($params);

			//$query->andFilterWhere(['documento' => -1]);
	

		    if (!$this->validate()) {
		        // uncomment the following line if you do not want to return any records when validation fails
		        // $query->where('0=1');
		        return $dataProvider;
		    }
	
		    $query->andFilterWhere([
		        'id' => $this->id,
		        'idper' => $this->idper,
		        'fecha' => $this->fecha,
		        'total' => $this->total,
		        'pago' => $this->pago,
		
		    ]);
	
		    $query->andFilterWhere(['like','cedula', $this->cedula])
		        ->andFilterWhere(['like','documento', $this->documento]);

			//$query->andWhere(['NOT',['cedula' => null]])
			//	->andWhere(['NOT', ['documento' => null]]);

			if (Yii::$app->user->identity->idperfil == 'fin'){
				$query->andFilterWhere(['>', 'total', 0.00]);
			}

		    return $dataProvider;
		}
		else {
			$query = Factura::find()->where(['documento' => -1]);
			$dataProvider = new ActiveDataProvider([
		        'query' => $query,
		    	]);
			return $dataProvider;
		}
	}
}
