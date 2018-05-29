<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacionpersonal".
 *
 * @property string $CIInfPer
 * @property string $cedula_pasaporte
 * @property string $TipoDocInfPer
 * @property string $ApellInfPer
 * @property string $ApellMatInfPer
 * @property string $NombInfPer
 * @property string $NacionalidadPer
 * @property integer $EtniaPer
 * @property string $FechNacimPer
 * @property string $LugarNacimientoPer
 * @property string $GeneroPer
 * @property string $EstadoCivilPer
 * @property string $CiudadPer
 * @property string $DirecDomicilioPer
 * @property string $Telf1InfPer
 * @property string $CelularInfPer
 * @property string $TipoInfPer
 * @property integer $statusper
 * @property string $mailPer
 * @property string $mailInst
 * @property integer $GrupoSanguineo
 * @property string $tipo_discapacidad
 * @property string $carnet_conadis
 * @property string $num_carnet_conadis
 * @property integer $porcentaje_discapacidad
 * @property resource $fotografia
 * @property string $codigo_dactilar
 * @property integer $hd_posicion
 * @property resource $huella_dactilar
 * @property string $ultima_actualizacion
 * @property string $codigo_verificacion
 *
 * @property AcademicoAlumno[] $academicoAlumnos
 * @property Matricula[] $matriculas
 * @property Notasalumnoasignatura[] $notasalumnoasignaturas
 */
class Informacionpersonal extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'informacionpersonal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CIInfPer', 'ApellInfPer', 'ApellMatInfPer', 'NombInfPer'], 'required'],
            [['CIInfPer'], 'string', 'max' => 20],
            //[['cedula_pasaporte'], 'string', 'max' => 13],
            [['TipoDocInfPer'], 'string', 'max' => 1],
            [['ApellInfPer', 'ApellMatInfPer', 'NombInfPer'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CIInfPer' => 'Cédula',
            'cedula_pasaporte' => 'Cédula Pasaporte',
            'TipoDocInfPer' => 'Tipo Documento',
            'ApellInfPer' => 'Apellido Paterno',
            'ApellMatInfPer' => 'Apellido Materno',
            'NombInfPer' => 'Nombres',
            'NacionalidadPer' => 'Nacionalidad',
            'EtniaPer' => 'Etnia',
            'FechNacimPer' => 'Fecha nacimiento',
            'LugarNacimientoPer' => 'Lugar de nacimiento',
            'GeneroPer' => 'Género',
            'EstadoCivilPer' => 'Estado civil',
            'CiudadPer' => 'Ciudad',
            'DirecDomicilioPer' => 'Dirección domicilio',
            'Telf1InfPer' => 'Teléfono',
            'CelularInfPer' => 'Celular',
            'TipoInfPer' => 'Tipo información',
            'statusper' => 'Estado',
            'mailPer' => 'email',
            'mailInst' => 'email Institución',
            'GrupoSanguineo' => 'Grupo sanguíneo',
            'tipo_discapacidad' => 'Tipo discapacidad',
            'carnet_conadis' => 'Carnet conadis',
            'num_carnet_conadis' => 'Número carnet Conadis',
            'porcentaje_discapacidad' => 'Porcentaje discapacidad',
            'fotografia' => 'Fotografía',
            'codigo_dactilar' => 'Código dactilar',
            'hd_posicion' => 'Hd Posición',
            'huella_dactilar' => 'Huella dactilar',
            'ultima_actualizacion' => 'Última actualización',
            'codigo_verificacion' => 'Código Verificación',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicoAlumnos()
    {
        return $this->hasMany(AcademicoAlumno::className(), ['CIInfPer' => 'CIInfPer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculas()
    {
        return $this->hasMany(Matricula::className(), ['CIInfPer' => 'CIInfPer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotasalumnoasignaturas()
    {
        return $this->hasMany(Notasalumnoasignatura::className(), ['CIInfPer' => 'CIInfPer']);
    }

	/*public function beforeSave($options = array()) {
	
		$pass = md5($this->CIInfPer);
		$this->TipoDocInfPer = 'C';
		$this->codigo_dactilar = $pass;
		$this->cedula_pasaporte = $this->CIInfPer;
		$this->TipoInfPer = 'E';
		$this->statusper = 1;
		$this->ultima_actualizacion = date('Y-m-d H:i:s');
		return true;
	}*/

}
