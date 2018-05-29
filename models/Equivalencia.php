<?php

namespace app\models;

use Yii;
use app\models\Asignatura;

/**
 * This is the model class for table "equivalencia".
 *
 * @property integer $idequivalencia
 * @property string $asignatura
 * @property string $equivalencia
 * @property string $fecha
 * @property string $usuario
 */
class Equivalencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'equivalencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asignatura', 'equivalencia'], 'required'],
            [['fecha'], 'safe'],
            [['asignatura', 'equivalencia'], 'string', 'max' => 10],
            [['usuario'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idequivalencia' => 'Idequivalencia',
            'asignatura' => 'Asignatura',
            'equivalencia' => 'Equivalencia',
            'fecha' => 'Fecha',
            'usuario' => 'Usuario',
        ];
    }


	// source here


	public function beforeSave($insert) {


		if (parent::beforeSave($insert)) {
			// ...custom code here...
			
			$verifica = false;

			$asignatura = Asignatura::find()
					->where(['IdAsig' => $this->asignatura])
					->one();
			

			if (!isset($asignatura)){
				$this->addError('asignatura', "No existe la Asignatura.");
				$verifica = false;
				return $verifica;
				exit;}
			else {
				 $verifica = true;
				// Yii::app()->Informacion->CIInfPer.setFlash('error', "Data2 failed!");
					
			}

			$equivalencia = Asignatura::find()
					->where(['IdAsig' => $this->equivalencia])
					->one();
		
			if (!isset($equivalencia)){
	
				$this->addError('equivalencia', "No existe la Asignatura.");
				$verifica = false;
				return $verifica;
				exit;}
			else {
				 $verifica = true;
				// Yii::app()->Informacion->CIInfPer.setFlash('error', "Data2 failed!");
		
			}

			return $verifica;
				//return true;

		} 

		else {
			return false;
		}

	}

	
}
