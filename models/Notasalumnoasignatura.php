<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notasalumnoasignatura".
 *
 * @property string $idnaa
 * @property string $CIInfPer
 * @property string $idAsig
 * @property integer $idPer
 * @property string $CAC1
 * @property string $CAC2
 * @property string $CAC3
 * @property string $TCAC
 * @property string $CEF
 * @property string $CSP
 * @property string $CCR
 * @property string $CSP2
 * @property string $CalifFinal
 * @property integer $asistencia
 * @property integer $StatusCalif
 * @property string $idMatricula
 * @property integer $VRepite
 * @property string $observacion
 * @property integer $op1
 * @property integer $op2
 * @property integer $op3
 * @property integer $pierde_x_asistencia
 * @property integer $repite
 * @property integer $retirado
 * @property integer $excluidaxrepitencia
 * @property integer $excluidaxreingreso
 * @property integer $excluidaxresolucion
 * @property integer $convalidacion
 * @property integer $aprobada
 * @property integer $anulada
 * @property integer $arrastre
 * @property string $registro_asistencia
 * @property string $usu_registro_asistencia
 * @property string $registro
 * @property string $ultima_modificacion
 * @property string $usu_pregistro
 * @property string $usu_umodif_registro
 * @property string $archivo
 * @property double $idMc
 * @property string $institucion_proviene
 * @property string $porcentaje_convalidacion
 * @property integer $exam_final_atrasado
 * @property integer $exam_supl_atrasado
 * @property string $observacion_efa
 * @property string $observacion_espa
 * @property string $usu_habilita_efa
 * @property string $usu_habilita_espa
 * @property integer $dpa_id
 *
 * @property AsistenciaAlumno[] $asistenciaAlumnos
 * @property AsistenciaAlumno[] $asistenciaAlumnos0
 * @property Informacionpersonal $cIInfPer
 * @property Matricula $idMatricula0
 * @property Asignatura $idAsig0
 */
class Notasalumnoasignatura extends \yii\db\ActiveRecord
{

	public $carrera;
	public $asignatura;
	public $nivel;
	public $paralelo;
	public $periodo;
	public $aprobadas;
	public $reprobadas;
	public $cedula;
	public $promedio;
	public $nombre;
	public $contador;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notasalumnoasignatura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
		#['CalifFinal', 'validateNota'],
            [['CIInfPer', 'CalifFinal', 'idAsig', 'observacion', 'asistencia', 'idPer'], 'required'],
            [['idPer', 'asistencia', 'StatusCalif', 'aprobada'], 'integer'],
            [['CalifFinal'], 'number'],
            [['CIInfPer', 'idMatricula'], 'string', 'max' => 20],
            [['observacion'], 'string', 'max' => 200],
		[['observacion_efa'], 'string', 'max' => 60],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idnaa' => 'Idnaa',
            'CIInfPer' => 'Cédula',
            'idAsig' => 'Asignatura',
            'idPer' => 'Período',
            'CAC1' => 'Cac1',
            'CAC2' => 'Cac2',
            'CAC3' => 'Cac3',
            'TCAC' => 'Tcac',
            'CEF' => 'Cef',
            'CSP' => 'Csp',
            'CCR' => 'Ccr',
            'CSP2' => 'Csp2',
            'CalifFinal' => 'Nota',
            'asistencia' => 'Asistencia',
            'StatusCalif' => 'Estado_actual',
            'idMatricula' => 'Id Matricula',
            'VRepite' => 'Vrepite',
            'observacion' => 'Observación',
            'op1' => 'Op1',
            'op2' => 'Op2',
            'op3' => 'Op3',
            'pierde_x_asistencia' => 'Pierde X Asistencia',
            'repite' => 'Repite',
            'retirado' => 'Retirado',
            'excluidaxrepitencia' => 'Excluidaxrepitencia',
            'excluidaxreingreso' => 'Excluidaxreingreso',
            'excluidaxresolucion' => 'Excluidaxresolucion',
            'convalidacion' => 'Convalidacion',
            'aprobada' => 'Estado',
            'anulada' => 'Anulada',
            'arrastre' => 'Arrastre',
            'registro_asistencia' => 'Registro Asistencia',
            'usu_registro_asistencia' => 'Usu Registro Asistencia',
            'registro' => 'Registro',
            'ultima_modificacion' => 'Ultima Modificacion',
            'usu_pregistro' => 'Usu Pregistro',
            'usu_umodif_registro' => 'Usu Umodif Registro',
            'archivo' => 'Archivo',
            'idMc' => 'Malla curricular',
            'institucion_proviene' => 'Institucion Proviene',
            'porcentaje_convalidacion' => 'Porcentaje Convalidacion',
            'exam_final_atrasado' => 'Exam Final Atrasado',
            'exam_supl_atrasado' => 'Exam Supl Atrasado',
            'observacion_efa' => 'Observacion Efa',
            'observacion_espa' => 'Observacion Espa',
            'usu_habilita_efa' => 'Usu Habilita Efa',
            'usu_habilita_espa' => 'Usu Habilita Espa',
            'dpa_id' => 'Dpa ID',

		'nombreCarrera' => Yii::t('app', 'Carrera')

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //public function getAsistenciaAlumnos()
    //{
     //   return $this->hasMany(AsistenciaAlumno::className(), ['idnaa' => 'idnaa']);
    //}

    /**
     * @return \yii\db\ActiveQuery
     */
    //public function getAsistenciaAlumnos0()
    //{
    //    return $this->hasMany(AsistenciaAlumno::className(), ['ciinfper' => 'CIInfPer']);
    //}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCIInfPer()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'CIInfPer']);
    }
	
	public function getCedula0()
    {
        return $this->hasOne(Informacionpersonal::className(), ['CIInfPer' => 'CIInfPer']);
    }

	public function getNombreAlumno()
    {
		$model=$this->cedula0;
		return $model?($model->ApellInfPer . ' ' . $model->ApellMatInfPer . ' ' . $model->NombInfPer):'';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatricula0()
    {
        return $this->hasOne(Matricula::className(), ['idMatricula' => 'idMatricula']);
    }

	   /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetallematricula()
    {
        return $this->hasOne(DetalleMatricula::className(), ['id' => 'iddetalle']);
    }


    public function getSemestre()
    {
	$model=$this->matricula0;

//	echo var_dump($model);
//	exit;
	return $model?$model->idsemestre:'';
    }

	public function getNivel()
    {
	$model=$this->matricula0;

//	echo var_dump($model);
//	exit;
	return $model?$model->idsemestre:'';
    }

	public function getCarrera()
	{
		$model=$this->matricula0;
		return $model?$model->idCarr:'';
	}

	public function getNombreCarrera()
    {
	$carrera = '';
	if(isset($this->matricula0)) $carrera = $this->matricula0->getNombreCarrera();
	elseif(isset($this->detallematricula)) $carrera = $this->detallematricula->getNombCarrera();
	return $carrera;
    }


	public function getPeriodo0()
	{
		return $this->hasOne(Periodolectivo::className(), ['idPer' => 'idPer']);
	}


	public function getNombrePeriodo()
	{
		$periodo=$this->periodo0->DescPerLec;
		return $periodo?$periodo:'';
	}

	
	
	public function getNiveldetalle()
    {
	$model=$this->detallematricula;

//	echo var_dump($model);
//	exit;
	return $model?$model->nivel:'';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdAsig0()
    {
        return $this->hasOne(Asignatura::className(), ['IdAsig' => 'idAsig']);
    }

	public function getAsignatura()
    {
	$model=$this->idAsig0;
	return $model?$model->NombAsig:'';
    }

	public function validateNota($attribute, $params)
	{
		//echo var_dump($params); exit;
		if ($this->$attribute < 7 || $this->$attribute > 10)
			{
				$this->addError($attribute, "Nota final debe ser entre 7 -- 10.");
				return false;
			}
	}

	/*
	public function beforeSave($insert) {
		if (parent::beforeSave($insert)) {
			

			if ($this->idAsig === NULL || $this->idAsig == '' ||  $this->idAsig == '-'){
	
				$this->addError('idAsig', "Debe elegir una Asignatura.");
				return false;
			}
					
			

			if ($this->asistencia < 80 || $this->asistencia > 100)
			{
				$this->addError('asistencia', "Asistencia debe ser entre 80 - 100.");
				return false;
		
			}
		}
		else
			return true;

	}
	*/

}
