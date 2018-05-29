<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_horario".
 *
 * @property integer $id
 * @property integer $idhorario
 * @property integer $idcurso
 * @property string $dia
 * @property string $hora_inicio
 * @property string $hora_fin
 */
class DetalleHorario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'detalle_horario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idhorario', 'idcurso'], 'required'],
            [['idhorario', 'idcurso'], 'integer'],
            [['hora_inicio', 'hora_fin'], 'safe'],
            [['dia'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idhorario' => 'Idhorario',
            'idcurso' => 'Idcurso',
            'dia' => 'Dia',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
        ];
    }
	
	public function getcurso()
    {
        return $this->hasOne(CursoOfertado::className(), ['id' => 'idcurso']);
    }

}
