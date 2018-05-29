<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "extension_docente".
 *
 * @property integer $id
 * @property integer $idcurso
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $memo
 */
class ExtensionDocente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

	public $carrera;
	public $asignatura;
	public $nivel;
	public $paralelo;

    public static function tableName()
    {
        return 'extension_docente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idcurso', 'fecha_inicio', 'fecha_fin'], 'required'],
            [['idcurso'], 'integer'],
            [['fecha_inicio', 'fecha_fin'], 'safe'],
            [['memo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idcurso' => 'Idcurso',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'memo' => 'Memo',
			'curso.iddocente' => 'Cédula',
			'curso.nombreDocente' => 'Docente',
        ];
    }

	public function getCurso()
    {
        return $this->hasOne(CursoOfertado::className(), ['id' => 'idcurso']);
    }

}
