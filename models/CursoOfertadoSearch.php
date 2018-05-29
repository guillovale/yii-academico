<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CursoOfertado;
use app\models\Carrera;
use yii\helpers\ArrayHelper;
/**
 * CursoOfertadoSearch represents the model behind the search form about `app\models\CursoOfertado`.
 */
class CursoOfertadoSearch extends CursoOfertado
{
    /**
     * @inheritdoc
     */

	//public $detallemalla0;
	public $carrera;
	public $asignatura;
    public $docente;
	public $nivel;

    public function rules()
    {
        return [
            [['id', 'idper', 'iddetallemalla', 'cupo', 'idhorario', 'estado'], 'integer'],
            [['iddocente', 'paralelo', 'fecha_inicio', 'fecha_fin'], 'safe'],
			 [['docente', 'asignatura', 'carrera', 'nivel'], 'safe'],
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
		$carreras = [];
		#$periodo = Periodolectivo::find()->where(['StatusPerLec'=>1])->one();
		if (isset(Yii::$app->user->identity->idcarr)) {
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			
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

		#$idper = $periodo?$periodo->idper:0;
        $query = CursoOfertado::find();
		$query->joinWith(['detallemalla', 'detallemalla.asignatura', 'detallemalla.malla.carrera', 'docente']);
		#$query->where(['idper'=>$idper]);
		$query->where(['in','idcarrera', $carreras]);
		$query->orderBy(['curso_ofertado.idper' => SORT_DESC,'carrera.NombCarr' => SORT_ASC, 'detalle_malla.nivel' => SORT_ASC,
						'curso_ofertado.paralelo' => SORT_ASC, 'asignatura.NombAsig' => SORT_ASC,						
						]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => ['pageSize' => 200,],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		$idcarr = isset($params['CursoOfertadoSearch']["idcarr"])?$params['CursoOfertadoSearch']["idcarr"]:'';

        $query->andFilterWhere([
            'curso_ofertado.id' => $this->id,
            'idper' => $this->idper,
            'iddetallemalla' => $this->iddetallemalla,
            'cupo' => $this->cupo,
            'idhorario' => $this->idhorario,
            'estado' => $this->estado,
			'carrera.idCarr' => $idcarr,
        ]);

        $query->andFilterWhere(['like', 'iddocente', $this->iddocente])
            ->andFilterWhere(['like', 'paralelo', $this->paralelo])
			->andFilterWhere(['like', 'asignatura.NombAsig', $this->asignatura])
			->andFilterWhere(['like', 'carrera.NombCarr', $this->carrera])
			->andFilterWhere(['like', 'detalle_malla.nivel', $this->nivel])
			->andFilterWhere(['like', 'informacionpersonal_d.ApellInfPer', $this->docente])
			->andFilterWhere(['>=', 'fecha_inicio', $this->fecha_inicio])
			->andFilterWhere(['>=', 'fecha_ifin', $this->fecha_fin]);

        return $dataProvider;
    }
}
