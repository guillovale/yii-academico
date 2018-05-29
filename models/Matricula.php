<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matricula".
 *
 * @property string $idMatricula
 * @property string $idMatricula_anual
 * @property integer $idPer
 * @property string $CIInfPer
 * @property string $idCarr
 * @property integer $idanio
 * @property integer $idsemestre
 * @property string $FechaMatricula
 * @property string $idParalelo
 * @property string $idMatricula_ant
 * @property string $tipoMatricula
 * @property string $statusMatricula
 * @property integer $anulada
 * @property string $observMatricula
 * @property integer $promocion
 * @property string $Usu_registra
 * @property string $Fecha_crea
 * @property string $Usu_modifica
 * @property string $Fecha_ultima_modif
 * @property string $archivo_aprobado
 * @property string $archivo_negado
 * @property string $archivo_anulado
 *
 * @property Informacionpersonal $cIInfPer
 * @property Carrera $idCarr0
 * @property Periodolectivo $idPer0
 * @property Notasalumnoasignatura[] $notasalumnoasignaturas
 */
class Matricula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

	public $nivel;
	public $total;
	public $total0;
	public $nombcarr;
	public $idperiodo;
	public $cnt;

    public static function tableName()
    {
        return 'matricula';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idMatricula', 'CIInfPer', 'idCarr', 'idsemestre'], 'required'],
            [['idPer', 'idanio', 'idsemestre'], 'integer'],
            [['idMatricula'], 'string', 'max' => 20],
            [['CIInfPer', 'statusMatricula'], 'string', 'max' => 10],
            [['idCarr'], 'string', 'max' => 6],
            [['idParalelo'], 'string', 'max' => 3]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idMatricula' => 'Id Matricula',
            'idMatricula_anual' => 'Id Matricula Anual',
            'idPer' => 'Id Per',
            'CIInfPer' => 'Ciinf Per',
            'idCarr' => 'Carrera',
            'idanio' => 'Idanio',
            'idsemestre' => 'Nivel',
            'FechaMatricula' => 'Fecha Matricula',
            'idParalelo' => 'Id Paralelo',
            'idMatricula_ant' => 'Id Matricula Ant',
            'tipoMatricula' => 'Tipo Matricula',
            'statusMatricula' => 'Status Matricula',
            'anulada' => 'Anulada',
            'observMatricula' => 'Observ Matricula',
            'promocion' => 'Promocion',
            'Usu_registra' => 'Usu Registra',
            'Fecha_crea' => 'Fecha Crea',
            'Usu_modifica' => 'Usu Modifica',
            'Fecha_ultima_modif' => 'Fecha Ultima Modif',
            'archivo_aprobado' => 'Archivo Aprobado',
            'archivo_negado' => 'Archivo Negado',
            'archivo_anulado' => 'Archivo Anulado',
		'idperiodo' => 'Período'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCIInfPer()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'CIInfPer']);
    }

	public function getCedula()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'CIInfPer']);
    }

	public function getNombreAlumno()
    {
	$model=$this->cedula;
	return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCarr0()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'idCarr']);
    }

	public function getNombreCarrera()
    {
	$model=$this->idCarr0;
	return $model?$model->NombCarr:'';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPer0()
    {
        return $this->hasOne(Periodolectivo::className(), ['idper' => 'idPer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotasalumnoasignaturas()
    {
        return $this->hasMany(Notasalumnoasignatura::className(), ['idMatricula' => 'idMatricula']);
    }
}
