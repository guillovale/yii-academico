<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "malla_estudiante".
 *
 * @property integer $id_malla
 * @property string $cedula
 * @property string $carrera
 * @property string $anio_habilitacion
 * @property string $fecha
 */
class MallaEstudiante extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'malla_estudiante';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cedula', 'carrera', 'anio_habilitacion'], 'required'],
            [['fecha'], 'safe'],
            [['cedula'], 'string', 'max' => 20],
            [['carrera'], 'string', 'max' => 10],
            [['anio_habilitacion'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_malla' => 'Id Malla',
            'cedula' => 'Cédula',
            'carrera' => 'Carrera',
            'anio_habilitacion' => 'Año Habilitación',
            'fecha' => 'Fecha',
        ];
    }


	/**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCarr0()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'carrera']);
    }

	public function getNombreCarrera()
	    {
		$model=$this->idCarr0;
		return $model?$model->NombCarr:'';
	    }


	public function getIdEstudiante()
	    {
		return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'cedula']);
	    }

	public function getNombreEstudianate()
	    {
		$model=$this->idEstudiante;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
	    }



}
