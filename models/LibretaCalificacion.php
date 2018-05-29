<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "libreta_calificacion".
 *
 * @property integer $id
 * @property integer $idper
 * @property integer $iddocenteperasig
 * @property string $iddocente
 * @property string $fecha
 * @property integer $hemisemestre
 * @property integer $idparametro
 * @property integer $idcomponente
 * @property string $tema
 */
class LibretaCalificacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'libreta_calificacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idper', 'iddocente', 'fecha', 'hemisemestre', 'idparametro', 'idcomponente'], 'required'],
            [['idper', 'iddocenteperasig', 'hemisemestre', 'idparametro', 'idcomponente'], 'integer'],
            [['fecha'], 'safe'],
            [['iddocente'], 'string', 'max' => 20],
            [['tema'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idper' => 'período',
            'iddocenteperasig' => 'Iddocenteperasig',
            'iddocente' => 'Iddocente',
            'fecha' => 'Fecha',
            'hemisemestre' => 'Hemisemestre',
            'idparametro' => 'Parámetro',
			'parametrosigla' => 'Parámetro',
            'idcomponente' => 'Componente',
			'componente' => 'Componente',
            'tema' => 'Tema',
			'idcurso'=> 'Curso',
        ];
    }
	
	public function getDocente()
    {
        return $this->hasOne(InformacionpersonalD::className(), ['CIInfPer' => 'iddocente']);
	}

	public function getCurso()
    {
        return $this->hasOne(CursoOfertado::className(), ['id' => 'idcurso']);
	}
	public function getDocenteasignatura()
    {
        return $this->hasOne(Docenteperasig::className(), ['dpa_id' => 'iddocenteperasig']);
	}

	public function getComponente0()
    {
        return $this->hasOne(Componentescalificacion::className(), ['idcomponente' => 'idcomponente']);
    }
	
	public function getParametro0()
    {
        return $this->hasOne(Parametroscalificacion::className(), ['idparametro' => 'idparametro']);
    }
	
	public function getComponente()
    {
		$model=$this->componente0;
		return $model?$model->componente:'';
    }
	public function getParametro()
    {
		$model=$this->parametro0;
		return $model?$model->parametro:'';
    }
	public function getParametrosigla()
    {
		$model=$this->componente0;
		return $model?$model->parametro->sigla:'';
    }
	public function getPeriodo()
    {
        return $this->hasOne(Periodolectivo::className(), ['idPer' => 'idper']);
	}
}
