<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacionpersonal_d".
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
 * @property string $Telf2InfPer
 * @property string $CelularInfPer
 * @property string $TipoInfPer
 * @property string $StatusPer
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
 * @property string $LoginUsu
 * @property string $ClaveUsu
 * @property integer $StatusUsu
 * @property string $idcarr
 * @property integer $usa_biometrico
 * @property string $fecha_reg
 * @property string $fecha_ultimo_acceso
 * @property string $usu_registra
 * @property string $usu_modifica
 * @property string $fecha_ultima_modif
 * @property string $usu_modifica_clave
 * @property string $fecha_ultima_modif_clave
 */
class InformacionpersonalD extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'informacionpersonal_d';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CIInfPer', 'cedula_pasaporte', 'EtniaPer', 'tipo_discapacidad', 'carnet_conadis', 'num_carnet_conadis', 'porcentaje_discapacidad', 'codigo_dactilar', 'hd_posicion', 'huella_dactilar', 'ultima_actualizacion', 'LoginUsu', 'ClaveUsu', 'StatusUsu', 'idcarr', 'usa_biometrico', 'fecha_reg', 'fecha_ultimo_acceso', 'usu_registra', 'usu_modifica', 'fecha_ultima_modif'], 'required'],
            [['EtniaPer', 'GrupoSanguineo', 'porcentaje_discapacidad', 'hd_posicion', 'StatusUsu', 'usa_biometrico'], 'integer'],
            [['FechNacimPer', 'ultima_actualizacion', 'fecha_reg', 'fecha_ultimo_acceso', 'fecha_ultima_modif', 'fecha_ultima_modif_clave'], 'safe'],
            [['fotografia', 'huella_dactilar'], 'string'],
            [['CIInfPer', 'num_carnet_conadis', 'LoginUsu', 'usu_registra', 'usu_modifica', 'usu_modifica_clave'], 'string', 'max' => 20],
            [['cedula_pasaporte'], 'string', 'max' => 13],
            [['TipoDocInfPer', 'GeneroPer', 'EstadoCivilPer', 'StatusPer', 'tipo_discapacidad'], 'string', 'max' => 1],
            [['ApellInfPer', 'ApellMatInfPer', 'NombInfPer', 'NacionalidadPer', 'LugarNacimientoPer', 'CiudadPer', 'DirecDomicilioPer'], 'string', 'max' => 45],
            [['Telf1InfPer', 'Telf2InfPer', 'CelularInfPer'], 'string', 'max' => 12],
            [['TipoInfPer', 'carnet_conadis'], 'string', 'max' => 2],
            [['mailPer', 'mailInst'], 'string', 'max' => 60],
            [['codigo_dactilar'], 'string', 'max' => 15],
            [['ClaveUsu'], 'string', 'max' => 100],
            [['idcarr'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'CIInfPer' => 'Ciinf Per',
            'cedula_pasaporte' => 'Cedula Pasaporte',
            'TipoDocInfPer' => 'Tipo Doc Inf Per',
            'ApellInfPer' => 'Apell Inf Per',
            'ApellMatInfPer' => 'Apell Mat Inf Per',
            'NombInfPer' => 'Nomb Inf Per',
            'NacionalidadPer' => 'Nacionalidad Per',
            'EtniaPer' => 'Etnia Per',
            'FechNacimPer' => 'Fech Nacim Per',
            'LugarNacimientoPer' => 'Lugar Nacimiento Per',
            'GeneroPer' => 'Genero Per',
            'EstadoCivilPer' => 'Estado Civil Per',
            'CiudadPer' => 'Ciudad Per',
            'DirecDomicilioPer' => 'Direc Domicilio Per',
            'Telf1InfPer' => 'Telf1 Inf Per',
            'Telf2InfPer' => 'Telf2 Inf Per',
            'CelularInfPer' => 'Celular Inf Per',
            'TipoInfPer' => 'Tipo Inf Per',
            'StatusPer' => 'Status Per',
            'mailPer' => 'Mail Per',
            'mailInst' => 'Mail Inst',
            'GrupoSanguineo' => 'Grupo Sanguineo',
            'tipo_discapacidad' => 'Tipo Discapacidad',
            'carnet_conadis' => 'Carnet Conadis',
            'num_carnet_conadis' => 'Num Carnet Conadis',
            'porcentaje_discapacidad' => 'Porcentaje Discapacidad',
            'fotografia' => 'Fotografia',
            'codigo_dactilar' => 'Codigo Dactilar',
            'hd_posicion' => 'Hd Posicion',
            'huella_dactilar' => 'Huella Dactilar',
            'ultima_actualizacion' => 'Ultima Actualizacion',
            'LoginUsu' => 'Login Usu',
            'ClaveUsu' => 'Clave Usu',
            'StatusUsu' => 'Status Usu',
            'idcarr' => 'Idcarr',
            'usa_biometrico' => 'Usa Biometrico',
            'fecha_reg' => 'Fecha Reg',
            'fecha_ultimo_acceso' => 'Fecha Ultimo Acceso',
            'usu_registra' => 'Usu Registra',
            'usu_modifica' => 'Usu Modifica',
            'fecha_ultima_modif' => 'Fecha Ultima Modif',
            'usu_modifica_clave' => 'Usu Modifica Clave',
            'fecha_ultima_modif_clave' => 'Fecha Ultima Modif Clave',
        ];
    }
	public function getNombre()
    {
		$nombre = '';
		$nombre = $this->ApellInfPer .' '. $this->ApellMatInfPer . ' ' . $this->NombInfPer;
		return $nombre;
    }
}
