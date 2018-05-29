<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asignatura".
 *
 * @property string $IdAsig
 * @property string $NombAsig
 * @property string $ColorAsig
 * @property integer $StatusAsig
 *
 * @property Notasalumnoasignatura[] $notasalumnoasignaturas
 */
class Asignatura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $nombre;

    public static function tableName()
    {
        return 'asignatura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdAsig'], 'required'],
            [['StatusAsig'], 'integer'],
            [['IdAsig', 'ColorAsig'], 'string', 'max' => 10],
            [['NombAsig', 'nombre'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdAsig' => 'Código',
            'NombAsig' => 'Asignatura',
            'ColorAsig' => 'Color',
            'StatusAsig' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotasalumnoasignaturas()
    {
        return $this->hasMany(Notasalumnoasignatura::className(), ['idAsig' => 'IdAsig']);
    }
}
