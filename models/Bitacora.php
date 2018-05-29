<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bitacora".
 *
 * @property integer $bt_id
 * @property string $bt_usuario
 * @property string $bt_fechahora
 * @property string $bt_accion
 * @property string $bt_ippc
 * @property string $bt_observacion
 */
class Bitacora extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bitacora';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bt_fechahora'], 'safe'],
            [['bt_usuario'], 'string', 'max' => 12],
            [['bt_accion'], 'string', 'max' => 100],
            [['bt_ippc'], 'string', 'max' => 30],
            [['bt_observacion'], 'string', 'max' => 600]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bt_id' => 'Bt ID',
            'bt_usuario' => 'Bt Usuario',
            'bt_fechahora' => 'Bt Fechahora',
            'bt_accion' => 'Bt Accion',
            'bt_ippc' => 'Bt Ippc',
            'bt_observacion' => 'Bt Observacion',
        ];
    }
}
