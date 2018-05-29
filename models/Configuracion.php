<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "configuracion".
 *
 * @property integer $id
 * @property string $detalle
 * @property double $valor
 * @property string $dato
 */
class Configuracion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'configuracion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detalle'], 'required'],
            [['valor'], 'number'],
            [['detalle'], 'string', 'max' => 50],
            [['dato'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detalle' => 'Detalle',
            'valor' => 'Valor',
            'dato' => 'Dato',
        ];
    }
}
