<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "factura".
 *
 * @property integer $id
 * @property string $cedula
 * @property integer $idper
 * @property string $fecha
 * @property string $iva
 * @property string $descuento
 * @property string $total
 * @property string $documento
 * @property string $pago
 */
class Factura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'factura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		//['pago', 'validatePago'],
		
            [['cedula', 'idper'], 'required'],
            [['idper'], 'integer'],
            [['fecha'], 'safe'],
            [['valor_matricula', 'valor_credito', 'valor_otro', 'total', 'pago'], 'number'],
            [['cedula'], 'string', 'max' => 20],
            [['documento'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Documento No:',
            'cedula' => 'Cédula',
            'idper' => 'Período',
            'fecha' => 'Fecha',
			'valor_matricula' => 'Valor matrícula',
			'valor_credito' => 'Valor crédito',
            'total' => 'Total',
            'documento' => 'Documento',
            'pago' => 'Monto USD',
	    'usuario' => 'Usuario',	
		'periodo.DescPerLec'=> 'Período',
        ];
    }

	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idper' => 'idper']);
    }

	public function getCedula0()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'cedula']);
    }

	public function getNombreAlumno()
    {
		$model=$this->cedula0;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }

	public function validatePago($attribute, $params)
	{
		//echo var_dump($this->total); exit;
		if ($this->$attribute != $this->total )//$this->total)
			{
				$this->addError($attribute, "El pago debe ser igual que el total.");
				return false;
			}
	}

	public function validateDocumento($attribute, $params)
	{
		//echo var_dump($this->total); exit;
		$doc_duplicado = Factura::find()->where(['documento'=>$this->documento])->one();
		if ($doc_duplicado)//$this->total)
			{
				$this->addError($attribute, "Documento duplicado.");
				return false;
			}
	}

	public function sumaAbono()
    {
		$query = (new \yii\db\Query())->from('abono_factura');
		$sum = $query->where(['idfactura'=>$this->id])->sum('valor');
		return $sum;
		//echo var_dump($sum);
		//exit;
    }


}
