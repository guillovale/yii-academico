<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "paralelo".
 *
 * @property integer $idparalelo
 * @property string $paralelo
 */
class Paralelo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'paralelo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['paralelo'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idparalelo' => 'Idparalelo',
            'paralelo' => 'Paralelo',
        ];
    }
}
