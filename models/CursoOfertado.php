<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "curso_ofertado".
 *
 * @property integer $id
 * @property integer $idper
 * @property integer $iddetallemalla
 * @property string $iddocente
 * @property string $paralelo
 * @property integer $cupo
 * @property integer $idhorario
 * @property integer $estado
 */
class CursoOfertado extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $idcarr;
	public $idmalla;
	public $nivel;
	public $asignaturamalla;

    public static function tableName()
    {
        return 'curso_ofertado';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idper', 'iddetallemalla', 'cupo', 'paralelo'], 'required'],
            [['idper', 'iddetallemalla', 'cupo', 'idhorario', 'estado', 'restringido'], 'integer'],
            [['iddocente'], 'string', 'max' => 20],
            [['paralelo'], 'string', 'max' => 2],
			[['idcarr', 'idmalla'], 'safe'],
			[['fecha_inicio', 'fecha_fin'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'idcurso',
            'idper' => 'Idper',
            'iddetallemalla' => 'Iddetallemalla',
            'iddocente' => 'Id docente',
            'paralelo' => 'Paralelo',
            'cupo' => 'Cupo',
            'idhorario' => 'Horario',
            'estado' => 'Estado',
			'idcarr' => 'Carrera',
			'idmalla' => 'Malla',
        ];
    }

	/**
     * @return \yii\db\ActiveQuery
     */
	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idPer' => 'idper']);
    }
    public function getDetallemalla()
    {
        return $this->hasOne(DetalleMalla::className(), ['id' => 'iddetallemalla']);
    }
	public function getDocente()
    {
        return $this->hasOne(InformacionpersonalD::className(), ['CIInfPer' => 'iddocente']);
    }
	public function getNombreDocente()
    {
		$model=$this->docente;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }
	public function getCupos()
    {
		#echo var_dump(count($this->getDetallematricula()) ); exit;
		$matriculados = DetalleMatricula::find()
							->where(['idcurso' => $this->id, 'estado'=> 1])->all();

		$total_matriculados = $matriculados?count($matriculados):0;
		return $this->cupo - $total_matriculados;
    }

}
