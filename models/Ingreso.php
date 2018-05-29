<?php

namespace app\models;

use Yii;
use app\models\Informacionpersonal;

/**
 * This is the model class for table "ingreso".
 *
 * @property integer $id
 * @property integer $idper
 * @property string $idcarr
 * @property string $malla
 * @property string $CIInfPer
 * @property string $fecha
 * @property string $tipo_ingreso
 * @property string $observacion
 * @property string $usuario
 */
class Ingreso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $cedula;
	public $nombrealumno;
	public $nombrecarrera;

    public static function tableName()
    {
        return 'ingreso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idper', 'idcarr', 'idmalla', 'CIInfPer', 'fecha', 'usuario'], 'required'],
            [['idper'], 'integer'],
            [['fecha'], 'safe'],
            [['idcarr'], 'string', 'max' => 6],
            [['malla'], 'string', 'max' => 10],
            [['CIInfPer'], 'string', 'max' => 20],
            [['tipo_ingreso'], 'string', 'max' => 4],
            [['observacion'], 'string', 'max' => 200],
            [['usuario'], 'string', 'max' => 30],
			#['CIInfPer', 'validateCedula'],
			#['CIInfPer','custom_function_validation'],
        ];
    }

	public function custom_function_validation($attribute){
		// add custom validation
		//$this->addError($attribute,'Custom Validation Error');
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idper' => 'IdPer.',
            'idcarr' => 'Id Carr.',
            'malla' => 'Malla',
            'CIInfPer' => 'Cédula',
            'fecha' => 'Fecha',
            'tipo_ingreso' => 'Tipo Ingreso',
            'observacion' => 'Observación',
            'usuario' => 'Usuario',
			'periodo.DescPerLec' => 'Período',
			'nombrecarrera' => 'Carrera',
			'nombrealumno' => 'Alumno',
        ];
    }

	public function getCedula0()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'CIInfPer']);
    }

	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idper' => 'idper']);
    }

	public function getCarrera()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'idcarr']);
    }

	public function getMalla0()
    {
        return $this->hasOne(MallaCarrera::className(), ['id' => 'idmalla']);
    }

	public function getNombreAlumno()
    {
		$model=$this->cedula0;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }
	public function getNombreCarrera()
    {
		$model=$this->carrera;
		return $model?$model->NombCarr:'';
    }
	public function getOptativaCarrera()
    {
		$model=$this->carrera;
		return $model?$model->optativa:'';
    }
	public function validateCedula()
    {
		//$this->CIInfPer = $this->$attribute;
		$user = Informacionpersonal::find()->where(['CIInfPer' => $this->CIInfPer]);

        if ($user) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }

}
