<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "abono_factura".
 *
 * @property integer $id
 * @property integer $idfactura
 * @property string $fecha
 * @property string $documento
 * @property double $valor
 * @property string $usuario
 */
class AbonoFactura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'abono_factura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfactura', 'documento', 'valor'], 'required'],
            [['idfactura'], 'integer'],
            [['fecha'], 'safe'],
            [['valor'], 'number'],
            [['documento'], 'string', 'max' => 50],
            [['usuario'], 'string', 'max' => 20],
	//	[['valor'], 'validatePago']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idfactura' => 'Documento',
            'fecha' => 'Fecha',
            'documento' => 'Referencia',
            'valor' => 'Valor',
            'usuario' => 'Usuario',
			'factura.nombreAlumno' => 'Alumno',
        ];
    }

	public function getFactura()
    {
        return $this->hasOne(Factura::className(), ['id' => 'idfactura']);
    }

	public function validatePago($attribute,$param)
	{
		//echo var_dump($param); exit;
		if ($this->$attribute != $this->valor )//$this->total)
			{
				$this->addError($attribute, "El pago debe ser igual que el total.");
				return false;
			}
	}

	public static function getTotal($provider, $fieldName)
	{
		$total = 0;

		foreach ($provider as $item) {
		    $total += $item[$fieldName];
		}

		return $total;
	}


}
