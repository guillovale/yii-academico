<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "malla_carrera".
 *
 * @property integer $id
 * @property string $idcarrera
 * @property string $detalle
 * @property string $fecha
 * @property string $anio
 * @property integer $estado
 */
class MallaCarrera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'malla_carrera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcarrera', 'detalle', 'fecha', 'anio'], 'required'],
            [['fecha'], 'safe'],
            [['estado'], 'integer'],
            [['idcarrera'], 'string', 'max' => 6],
            [['detalle'], 'string', 'max' => 100],
            [['anio'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idcarrera' => 'Id carrera',
            'detalle' => 'Detalle',
            'fecha' => 'Fecha',
            'anio' => 'Año',
			'carrera.NombCarr' => 'Carrera',
            'estado' => 'Estado',
        ];
    }

	public function getCarrera()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'idcarrera']);
    }

}
