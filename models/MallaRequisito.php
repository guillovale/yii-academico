<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "malla_requisito".
 *
 * @property integer $id
 * @property integer $idmalla
 * @property integer $idmallarequisito
 * @property string $tipo
 */
class MallaRequisito extends \yii\db\ActiveRecord
{
	#public $carrera;
	#public $malla;
	#public $nivel;
	#public $nivel_prerequisito;
	# public $idasig;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'malla_requisito';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idmalla', 'idmallarequisito'], 'required'],
            [['idmalla', 'idmallarequisito'], 'integer'],
            [['tipo'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idmalla' => 'Asignatura',
            'idmallarequisito' => 'Prerequisito',
            'tipo' => 'Tipo',
			'detalle' => 'Malla',
			'detallemalla.idasignatura' => 'Id',
        ];
    }

	public function getDetallemalla()
    {
        return $this->hasOne(DetalleMalla::className(), ['id' => 'idmalla']);
    }
	public function getMallarequisito()
    {
        return $this->hasOne(DetalleMalla::className(), ['id' => 'idmallarequisito']);
    }

	#public function getCarrera()
    #{
     #   return $this->hasOne(Carrera::className(), ['idCarr' => 'idcarrera']);
    #}

}
