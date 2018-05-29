<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notas_detalle".
 *
 * @property integer $idnota
 * @property integer $idlibreta
 * @property integer $iddetallematricula
 * @property string $nota
 */
class NotasDetalle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	//public $amount;
    public static function tableName()
    {
        return 'notas_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idlibreta', 'nota', 'iddetallematricula'], 'required'],
            [['idlibreta', 'iddetallematricula'], 'integer'],
            [['nota'], 'number'],
			['nota', 'integer', 'integerOnly' => true, 'min' => 0, 'max' => 10],
			//[['nota'], 'default', 'value'=> 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idnota' => 'Idnota',
            'idlibreta' => 'Idlibreta',
            'iddetallematricula' => 'Alumno',
            'nota' => 'Nota',
			'usuario' => 'Usu. modifica',
			'fecha' => 'Fecha mod.',
        ];
    }

	public function getLibreta()
    {
        return $this->hasOne(LibretaCalificacion::className(), ['id' => 'idlibreta']);
    }

	public function getLibretasigla()
    {
		$model=$this->libreta;
		//echo var_dump($model); exit;
		return $model?$model->getParametrosigla():'';
    }
	
	public function getDetallematricula()
    {
        return $this->hasOne(DetalleMatricula::className(), ['id' => 'iddetallematricula']);
    }

}
