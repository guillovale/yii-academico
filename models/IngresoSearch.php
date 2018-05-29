<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ingreso;

/**
 * IngresoSearch represents the model behind the search form about `app\models\Ingreso`.
 */
class IngresoSearch extends Ingreso
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['id', 'idper'], 'integer'],
            [['idcarr', 'malla', 'CIInfPer', 'fecha', 'tipo_ingreso', 'observacion', 
				'usuario', 'nombrecarrera', 'nombrealumno'], 'safe'],
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
	public function search($params)	{

		#if ($this->load($params)) {
			$query = Ingreso::find();
			$query->joinWith(['carrera', 'cedula0']);
			$usuario = Yii::$app->user->identity;
			if ($usuario->idperfil == 'snna'){

				$query->where(['tipo_ingreso'=>'SNNA']);

			}
			
			
			$query->orderBy(['idper'=>SORT_DESC, 'idcarr'=>SORT_ASC,'informacionpersonal.ApellInfPer'=>SORT_ASC, 
							'informacionpersonal.ApellMatInfPer'=>SORT_ASC, 'informacionpersonal.NombInfPer'=>SORT_ASC]);
			$dataProvider = new ActiveDataProvider([
			    'query' => $query,
				'pagination' => ['pageSize' => 100,],
			]);

			$this->load($params);

			if (!$this->validate()) {
			    // uncomment the following line if you do not want to return any records when validation fails
			    // $query->where('0=1');
			    return $dataProvider;
			}

			$query->andFilterWhere([
			    'id' => $this->id,
			    'idper' => $this->idper,
			    #'fecha' => $this->fecha,
				'ingreso.CIInfPer' => $this->CIInfPer,
			]);

			$query->andFilterWhere(['like', 'tipo_ingreso', $this->tipo_ingreso])
			    ->andFilterWhere(['like', 'carrera.NombCarr', $this->nombrecarrera])
			    #->andFilterWhere(['like', 'CIInfPer', $this->CIInfPer])
			    #->andFilterWhere(['like', 'tipo_ingreso', $this->tipo_ingreso])
			    #->andFilterWhere(['like', 'observacion', $this->observacion])
			    ->andFilterWhere(['like', 'informacionpersonal.ApellInfPer', $this->nombrealumno]);

			return $dataProvider;
		    
		#}
		#else {
		#	$query = Ingreso::find()
		#		->where(['CIInfPer' => -1]);
		#	$dataProvider = new ActiveDataProvider([
         #   			'query' => $query,
        #		]);
		#	return $dataProvider;
		#}
	}
}
