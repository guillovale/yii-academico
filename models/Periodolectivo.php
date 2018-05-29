<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodolectivo".
 *
 * @property integer $idper
 * @property string $fechinicioperlec
 * @property string $fechfinalperlec
 * @property string $DescPerLec
 * @property integer $StatusPerLec
 * @property integer $cicloPerLec
 * @property string $inicioClases
 * @property string $finClases
 * @property string $examfinal_ini
 * @property string $examfinal_fin
 * @property string $examsupletorio_ini
 * @property string $examsupletorio_fin
 * @property string $ci_fechinicio
 * @property string $ci_fechfin
 * @property string $examsuficiencia_ini
 * @property string $examsuficiencia_fin
 * @property string $org_mallacurr
 * @property string $periodosUnificado
 * @property string $descripcion_perlec
 *
 * @property Docenteperasig[] $docenteperasigs
 * @property Mallacurricularperiodo[] $mallacurricularperiodos
 * @property Matricula[] $matriculas
 */
class Periodolectivo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'periodolectivo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fechinicioperlec', 'fechfinalperlec', 'inicioClases', 'finClases', 'examfinal_ini', 
				'examfinal_fin', 'examsupletorio_ini', 'examsupletorio_fin', 'ci_fechinicio', 
				'ci_fechfin', 'examsuficiencia_ini', 'examsuficiencia_fin', 'fechamaxeliminarmatricula'], 'safe'],
            [['StatusPerLec', 'cicloPerLec'], 'integer'],
            [['inicioClases', 'finClases', 'examfinal_ini', 'examfinal_fin', 'examsupletorio_ini', 
				'examsupletorio_fin', 'ci_fechinicio', 'ci_fechfin', 'examsuficiencia_ini', 
				'examsuficiencia_fin', 'org_mallacurr', 'periodosUnificado', 'descripcion_perlec'], 'required'],
            [['DescPerLec'], 'string', 'max' => 10],
            [['org_mallacurr'], 'string', 'max' => 2],
            [['periodosUnificado'], 'string', 'max' => 20],	
            [['descripcion_perlec'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idper' => 'Idper',
            'fechinicioperlec' => 'Fechinicioperlec',
            'fechfinalperlec' => 'Fechfinalperlec',
            'DescPerLec' => 'Desc Per Lec',
            'StatusPerLec' => 'Status Per Lec',
            'cicloPerLec' => 'Ciclo Per Lec',
            'inicioClases' => 'Inicio Clases',
            'finClases' => 'Fin Clases',
            'examfinal_ini' => 'Examfinal Ini',
            'examfinal_fin' => 'Examfinal Fin',
            'examsupletorio_ini' => 'Examsupletorio Ini',
            'examsupletorio_fin' => 'Examsupletorio Fin',
            'ci_fechinicio' => 'Ci Fechinicio',
            'ci_fechfin' => 'Ci Fechfin',
            'examsuficiencia_ini' => 'Examsuficiencia Ini',
            'examsuficiencia_fin' => 'Examsuficiencia Fin',
            'org_mallacurr' => 'Org Mallacurr',
            'periodosUnificado' => 'Periodos Unificado',
            'descripcion_perlec' => 'Descripcion Perlec',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteperasigs()
    {
        return $this->hasMany(Docenteperasig::className(), ['idPer' => 'idper']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallacurricularperiodos()
    {
        return $this->hasMany(Mallacurricularperiodo::className(), ['idPer' => 'idper']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculas()
    {
        return $this->hasMany(Matricula::className(), ['idPer' => 'idper']);
    }
}
