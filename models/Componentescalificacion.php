<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "componentescalificacion".
 *
 * @property integer $idcomponente
 * @property integer $idparametro
 * @property string $componente
 * @property string $tipo
 */
class Componentescalificacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'componentescalificacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idparametro'], 'integer'],
            [['componente'], 'string', 'max' => 200],
            [['tipo'], 'string', 'max' => 40]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idcomponente' => 'Idcomponente',
            'idparametro' => 'Idparametro',
            'componente' => 'Componente',
            'tipo' => 'Tipo',
        ];
    }
	
	public function getParametro()
    {
        return $this->hasOne(Parametroscalificacion::className(), ['idparametro' => 'idparametro']);
    }

}
