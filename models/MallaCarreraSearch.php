<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MallaCarrera;
use yii\helpers\ArrayHelper;

/**
 * MallaCarreraSearch represents the model behind the search form about `app\models\MallaCarrera`.
 */
class MallaCarreraSearch extends MallaCarrera
{
    /**
     * @inheritdoc
     */
	public $nombrecarr;

    public function rules()
    {
        return [
            [['id', 'estado'], 'integer'],
            [['idcarrera', 'detalle', 'fecha', 'anio', 'nombrecarr'], 'safe'],
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
		if (isset(Yii::$app->user->identity->idcarr)) {
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			//$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
			if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::getColumn(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr');
			}
		
			else {
				$carreras = ArrayHelper::getColumn(Carrera::find()->where(['in', 'idcarr', $carreras_user])
														->orderBy(['nombcarr'=>SORT_DESC])
														->all(), 'idCarr');
			}
		}
		
		$query = MallaCarrera::find();
		$query->joinWith(['carrera']);
		//$query->where(['idper'=>$idper]);
		$query->where(['in','idcarrera', $carreras]);
		$query->orderBy(['carrera.NombCarr' => SORT_ASC						
						]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



       // $query = MallaCarrera::find();

        //$dataProvider = new ActiveDataProvider([
         //   'query' => $query,
        //]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fecha' => $this->fecha,
            'estado' => $this->estado,
        ]);

        $query->andFilterWhere(['like', 'idcarrera', $this->idcarrera])
            ->andFilterWhere(['like', 'detalle', $this->detalle])
			->andFilterWhere(['like', 'carrera.NombCarr', $this->nombrecarr])
            ->andFilterWhere(['like', 'anio', $this->anio]);

        return $dataProvider;
    }
}
