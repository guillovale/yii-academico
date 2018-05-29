<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Informacionpersonal;

/**
 * InformacionpersonalSearch represents the model behind the search form about `app\models\Informacionpersonal`.
 */
class InformacionpersonalSearch extends Informacionpersonal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CIInfPer', 'cedula_pasaporte', 'TipoDocInfPer', 'ApellInfPer', 
			'ApellMatInfPer', 'NombInfPer', 'NacionalidadPer', 'FechNacimPer', 	
			'LugarNacimientoPer', 'mailPer'], 'safe'],
            
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
		//if ($this->load($params)) {
			$query = Informacionpersonal::find()
				->select(['CIInfPer', 'ApellInfPer', 'ApellMatInfPer', 'NombInfPer', 
					'codigo_dactilar', 'cedula_pasaporte', 'TipoInfPer', 'statusper', 'mailPer']);

			$dataProvider = new ActiveDataProvider([
			    'query' => $query,
			]);

			$this->load($params);

			if (!$this->validate()) {
			    // uncomment the following line if you do not want to return any records when validation fails
			    // $query->where('0=1');
			    return $dataProvider;
			}

			/*$query->andFilterWhere([
			    'EtniaPer' => $this->EtniaPer,
			    'FechNacimPer' => $this->FechNacimPer,
			    'statusper' => $this->statusper,
			    'GrupoSanguineo' => $this->GrupoSanguineo,
			    'porcentaje_discapacidad' => $this->porcentaje_discapacidad,
			    'hd_posicion' => $this->hd_posicion,
			    'ultima_actualizacion' => $this->ultima_actualizacion,
			]);*/

			$query->andFilterWhere(['CIInfPer' => $this->CIInfPer])
			    //->andFilterWhere(['like', 'cedula_pasaporte', $this->cedula_pasaporte])
			    //->andFilterWhere(['like', 'TipoDocInfPer', $this->TipoDocInfPer])
			    ->andFilterWhere(['like', 'ApellInfPer', $this->ApellInfPer])
			    ->andFilterWhere(['like', 'ApellMatInfPer', $this->ApellMatInfPer])
			    ->andFilterWhere(['like', 'NombInfPer', $this->NombInfPer])
				->andFilterWhere(['like', 'mailPer', $this->mailPer]);
			    /*->andFilterWhere(['like', 'NacionalidadPer', $this->NacionalidadPer])
			    ->andFilterWhere(['like', 'LugarNacimientoPer', $this->LugarNacimientoPer])
			    ->andFilterWhere(['like', 'GeneroPer', $this->GeneroPer])
			    ->andFilterWhere(['like', 'EstadoCivilPer', $this->EstadoCivilPer])
			    ->andFilterWhere(['like', 'CiudadPer', $this->CiudadPer])
			    ->andFilterWhere(['like', 'DirecDomicilioPer', $this->DirecDomicilioPer])
			    ->andFilterWhere(['like', 'Telf1InfPer', $this->Telf1InfPer])
			    ->andFilterWhere(['like', 'CelularInfPer', $this->CelularInfPer])
			    ->andFilterWhere(['like', 'TipoInfPer', $this->TipoInfPer])
			    ->andFilterWhere(['like', 'mailPer', $this->mailPer])
			    ->andFilterWhere(['like', 'mailInst', $this->mailInst])
			    ->andFilterWhere(['like', 'tipo_discapacidad', $this->tipo_discapacidad])
			    ->andFilterWhere(['like', 'carnet_conadis', $this->carnet_conadis])
			    ->andFilterWhere(['like', 'num_carnet_conadis', $this->num_carnet_conadis])
			    ->andFilterWhere(['like', 'fotografia', $this->fotografia])
			    ->andFilterWhere(['like', 'codigo_dactilar', $this->codigo_dactilar])
			    ->andFilterWhere(['like', 'huella_dactilar', $this->huella_dactilar])
			    ->andFilterWhere(['like', 'codigo_verificacion', $this->codigo_verificacion])*/
				

			#return $dataProvider;
		//}
		#else {
		#	$query = Informacionpersonal::find()->select(['CIInfPer', 'ApellInfPer', 'ApellMatInfPer', 'NombInfPer', 
		#			'codigo_dactilar', 'cedula_pasaporte', 'TipoInfPer', 'statusper', 'mailPer'])
		#		->where(['statusper' => 1]);
		#$dataProvider = new ActiveDataProvider([
         #  			'query' => $query,
        #	]);
		return $dataProvider;
		#}
	}
}
