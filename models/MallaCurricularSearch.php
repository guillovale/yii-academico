<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MallaCurricular;

/**
 * InformacionpersonalSearch represents the model behind the search form about `app\models\Informacionpersonal`.
 */
class MallaCurricularSearch extends MallaCurricular
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           [['idCarr', 'idAsig', 'idef', 'num_creditos', 'horas_semanales', 'caracter', 'org_mallacurr', 'anio_habilitacion', 'codigo', 		'fecha_registro', 'usu_registra', 'usu_modif', 'imp'], 'required'],
            [['idAnio', 'idSemestre', 'num_creditos', 'horas_semanales', 'status', 'imp'], 'integer'],
            [['fecha_registro', 'fecha_modif'], 'safe'],
            [['idCarr'], 'string', 'max' => 6],
            [['idAsig', 'usu_registra', 'usu_modif'], 'string', 'max' => 10],
            [['idef'], 'string', 'max' => 3],
            [['caracter'], 'string', 'max' => 20],
            [['org_mallacurr'], 'string', 'max' => 2],
            [['anio_habilitacion'], 'string', 'max' => 4],
            [['codigo'], 'string', 'max' => 30]
            
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
		if ($this->load($params)) {
			$query = MallaCurricular::find();

			$dataProvider = new ActiveDataProvider([
			    'query' => $query,
			]);

			//$this->load($params);

			if (!$this->validate()) {
			    // uncomment the following line if you do not want to return any records when validation fails
			    // $query->where('0=1');
			    //return $dataProvider;
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

			//$query->andFilterWhere(['CIInfPer' => $this->CIInfPer])
			    //->andFilterWhere(['like', 'cedula_pasaporte', $this->cedula_pasaporte])
			    //->andFilterWhere(['like', 'TipoDocInfPer', $this->TipoDocInfPer])
			  //  ->andFilterWhere(['like', 'ApellInfPer', $this->ApellInfPer])
			  //  ->andFilterWhere(['like', 'ApellMatInfPer', $this->ApellMatInfPer])
			  //  ->andFilterWhere(['like', 'NombInfPer', $this->NombInfPer])
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
				;

			return $dataProvider;
		}
		else {
			$query = MallaCurricular::find()
				->where(['idcarr' => -1]);
			$dataProvider = new ActiveDataProvider([
            			'query' => $query,
        		]);
			return $dataProvider;
		}
	}
}
