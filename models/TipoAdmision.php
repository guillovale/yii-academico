<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipo_admision".
 *
 * @property string $tad_id
 * @property string $tad_nombre
 * @property integer $estado
 */
class TipoAdmision extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_admision';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tad_id'], 'required'],
            [['estado'], 'integer'],
            [['tad_id'], 'string', 'max' => 4],
            [['tad_nombre'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tad_id' => 'Tad ID',
            'tad_nombre' => 'Tad Nombre',
            'estado' => 'Estado',
        ];
    }
}
