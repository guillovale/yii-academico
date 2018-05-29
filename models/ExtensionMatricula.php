<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extension_matricula".
 *
 * @property integer $id
 * @property integer $idper
 * @property string $cedula
 * @property string $fechain
 * @property string $fechafin
 * @property string $idcarr
 */
class ExtensionMatricula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extension_matricula';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idper', 'cedula', 'fechain', 'fechafin', 'idcarr', 'memorandum'], 'required'],
            [['idper'], 'integer'],
            [['fechain', 'fechafin'], 'safe'],
            [['cedula'], 'string', 'max' => 20],
		[['usuario'], 'string', 'max' => 20],
            [['idcarr'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idper' => 'Período',
            'cedula' => 'Cédula',
            'fechain' => 'Fecha inicio',
            'fechafin' => 'Fecha fin',
            'idcarr' => 'Id Carrera',
		'usuario' => 'Usuario',
		'memorandum' => 'Memorandum'
        ];
    }

	public function getIdCarrera() {
   	     return $this->hasOne(Carrera::className(), ['idCarr' => 'idcarr']);
	}

	public function getNombreCarrera() {
		$model=$this->idCarrera;
		return $model?$model->NombCarr:'';
	}

}
