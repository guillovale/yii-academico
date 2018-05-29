<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_malla".
 *
 * @property integer $id
 * @property integer $idmalla
 * @property string $idasignatura
 * @property integer $nivel
 * @property integer $credito
 * @property string $caracter
 * @property integer $estado
 */
class DetalleMalla extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	
    public static function tableName()
    {
        return 'detalle_malla';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmalla', 'idasignatura'], 'required'],
            [['idmalla', 'nivel', 'credito', 'estado'], 'integer'],
            [['idasignatura'], 'string', 'max' => 10],
            [['caracter'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idmalla' => 'Idmalla',
            'idasignatura' => 'Asignatura',
            'nivel' => 'Nivel',
            'credito' => 'Créditos',
            'caracter' => 'Tipo',
            'estado' => 'Estado',
        ];
    }

	public function getMalla()
    {
        return $this->hasOne(MallaCarrera::className(), ['id' => 'idmalla']);
    }

	public function getAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['IdAsig' => 'idasignatura']);
    }

}
