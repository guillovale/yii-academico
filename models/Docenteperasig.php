<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "docenteperasig".
 *
 * @property integer $dpa_id
 * @property string $CIInfPer
 * @property integer $idPer
 * @property string $idAsig
 * @property string $idCarr
 * @property integer $idAnio
 * @property integer $idSemestre
 * @property string $idParalelo
 * @property integer $status
 * @property double $idMc
 * @property string $tipo_orgmalla
 * @property integer $id_actdist
 * @property double $id_contdoc
 * @property integer $transf_asistencia
 * @property integer $transf_frecuente
 * @property integer $transf_parcial
 * @property integer $transf_final
 * @property integer $arrastre
 * @property integer $publicar
 */
class Docenteperasig extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'docenteperasig';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CIInfPer', 'idPer', 'idMc', 'tipo_orgmalla', 'id_actdist', 'id_contdoc', 'transf_asistencia', 'transf_frecuente', 'transf_parcial', 'transf_final'], 'required'],
            [['idPer', 'idAnio', 'idSemestre', 'status', 'id_actdist', 'transf_asistencia', 'transf_frecuente', 'transf_parcial', 'transf_final', 'arrastre', 'publicar'], 'integer'],
            [['idMc', 'id_contdoc'], 'number'],
            [['CIInfPer'], 'string', 'max' => 20],
            [['idAsig'], 'string', 'max' => 10],
            [['idCarr'], 'string', 'max' => 6],
            [['idParalelo', 'tipo_orgmalla'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dpa_id' => 'Dpa ID',
            'CIInfPer' => 'Cédula',
            'idPer' => 'Período',
            'idAsig' => 'Id Asig',
            'idCarr' => 'Id Carr',
            'idAnio' => 'Id Anio',
            'idSemestre' => 'Nivel',
            'idParalelo' => 'Paralelo',
            'status' => 'Estatus',
            'idMc' => 'Id Mc',
            'tipo_orgmalla' => 'Tipo Orgmalla',
            'id_actdist' => 'Id Actdist',
            'id_contdoc' => 'Id Contdoc',
            'transf_asistencia' => 'Transf Asistencia',
            'transf_frecuente' => 'Transf Frecuente',
            'transf_parcial' => 'Transf Parcial',
            'transf_final' => 'Transf Final',
            'arrastre' => 'Arrastre',
            'publicar' => 'publicar',
        ];
    }
	
	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idPer' => 'idPer']);
    }
	public function getCedula0()
    {
        return $this->hasOne(InformacionpersonalD::className(), ['CIInfPer' => 'CIInfPer']);
    }
	
	public function getNombreDocente()
    {
		$model=$this->cedula0;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }
	public function getAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['idAsig' => 'idAsig']);
    }
	public function getCarrera()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'idCarr']);
    }
}
