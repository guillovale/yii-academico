<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExtensionMatricula;

/**
 * ExtensionMatriculasearch represents the model behind the search form about `app\models\ExtensionMatricula`.
 */
class ExtensionMatriculasearch extends ExtensionMatricula
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'idper'], 'integer'],
            [['cedula', 'fechain', 'fechafin', 'idcarr'], 'safe'],
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

		$query = ExtensionMatricula::find();

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
		    'cedula' => $this->cedula,
		    'idper' => $this->idper,
		    'fechain' => $this->fechain,
		    'fechafin' => $this->fechafin,
			'idcarr' => $this->idcarr,
		]);

		//$query->andFilterWhere(['like', 'cedula', $this->cedula])
		 //   ->andFilterWhere(['like', 'idcarr', $this->idcarr]);

		return $dataProvider;
	}
	else {
		$query = ExtensionMatricula::find()
					->where(['cedula' => -1]);
		$dataProvider = new ActiveDataProvider([
            			'query' => $query,
        	]);
		return $dataProvider;
	}

    }
}
