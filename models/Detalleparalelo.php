<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalleparalelo".
 *
 * @property integer $iddetalleparalelo
 * @property integer $idparalelo
 * @property integer $nivel
 * @property integer $idper
 * @property string $idcarr
 * @property integer $cupo
 * @property integer $habilitado
 * @property string $idasig
 */
class Detalleparalelo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalleparalelo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idparalelo', 'nivel', 'idper', 'cupo', 'habilitado'], 'integer'],
            [['idcarr'], 'string', 'max' => 6],
            [['idasig'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'iddetalleparalelo' => 'Iddetalleparalelo',
            'idparalelo' => 'Idparalelo',
            'nivel' => 'Nivel',
            'idper' => 'Idper',
            'idcarr' => 'Idcarr',
            'cupo' => 'Cupo',
            'habilitado' => 'Habilitado',
            'idasig' => 'Idasig',
        ];
    }
}
