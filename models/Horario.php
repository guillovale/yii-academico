<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "horario".
 *
 * @property integer $id
 * @property integer $idper
 * @property string $idcarrera
 * @property integer $nivel
 * @property string $paralelo
 * @property string $hora_clase
 * @property string $hora_inicio
 * @property string $hora_fin
 */
class Horario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'horario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idper'], 'required'],
            [['idper', 'nivel'], 'integer'],
            [['hora_clase', 'hora_inicio', 'hora_fin'], 'safe'],
            [['idcarrera'], 'string', 'max' => 6],
            [['paralelo'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idper' => 'Idper',
            'idcarrera' => 'Idcarrera',
            'nivel' => 'Nivel',
            'paralelo' => 'Paralelo',
            'hora_clase' => 'Hora Clase',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
        ];
    }
}
