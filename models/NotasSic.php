<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notas_sic".
 *
 * @property integer $idcarrera
 * @property string $carrera
 * @property string $cedula
 * @property integer $codigo
 * @property string $asignatura
 * @property integer $calificacion
 * @property string $estado
 * @property string $fecha
 * @property string $nivel
 */
class NotasSic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notas_sic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcarrera', 'codigo', 'calificacion', 'nivel'], 'integer'],
            [['carrera', 'cedula', 'asignatura', 'estado', 'fecha'], 'required'],
            [['fecha'], 'safe'],
            [['carrera', 'asignatura', 'estado'], 'string', 'max' => 80],
            [['cedula'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idcarrera' => 'Idcarrera',
            'carrera' => 'Carrera',
            'cedula' => 'Cédula',
            'codigo' => 'Código',
            'asignatura' => 'Asignatura',
            'calificacion' => 'Nota',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
            'nivel' => 'Nivel',
        ];
    }

	public static function primaryKey()
		{
        		return ['codigo','cedula'];
    		}

}
