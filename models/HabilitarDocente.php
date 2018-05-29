<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habilitar_docente".
 *
 * @property integer $id
 * @property integer $iddocenteperasig
 * @property integer $hemisemestre
 * @property string $componente
 * @property string $fechaini
 * @property string $fechafin
 */
class HabilitarDocente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'habilitar_docente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iddocenteperasig', 'hemisemestre', 'componente', 'fechaini', 'fechafin'], 'required'],
            [['iddocenteperasig', 'hemisemestre'], 'integer'],
            [['fechaini', 'fechafin'], 'safe'],
            [['componente'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'iddocenteperasig' => 'Iddocenteperasig',
            'hemisemestre' => 'Hemisemestre',
            'componente' => 'Componente',
            'fechaini' => 'Fechaini',
            'fechafin' => 'Fechafin',
        ];
    }
}
