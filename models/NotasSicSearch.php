<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NotasSic;

/**
 * NotasSicSearch represents the model behind the search form about `app\models\NotasSic`.
 */
class NotasSicSearch extends NotasSic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['idcarrera', 'codigo', 'calificacion', 'nivel'], 'integer'],
            ['cedula', 'safe'],
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
	$cedula = '0000001';       
	if(isset($params['NotasSicSearch']['cedula'])) $cedula = $params['NotasSicSearch']['cedula'];
	//$query = Notasalumnoasignatura::find();
	//$carrera = (isset($params['NotasalumnoasignaturaSearch']['carrera']))?($params['NotasalumnoasignaturaSearch']['carrera']):'';
        $query = NotasSic::find()
			->where(['notas_sic.cedula' => $cedula])
			->orderby(['carrera' => SORT_ASC, 'nivel' => SORT_ASC, 'asignatura' => SORT_ASC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
		'sort' =>false,
		'pagination' => [
				'pageSize' => 100,
			    ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idcarrera' => $this->idcarrera,
            'codigo' => $this->codigo,
            'calificacion' => $this->calificacion,
            'fecha' => $this->fecha,
            'nivel' => $this->nivel,
        ]);

        $query->andFilterWhere(['like', 'carrera', $this->carrera])
            ->andFilterWhere(['like', 'cedula', $this->cedula])
            ->andFilterWhere(['like', 'asignatura', $this->asignatura])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
