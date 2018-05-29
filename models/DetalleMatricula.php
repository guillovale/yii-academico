<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detalle_matricula".
 *
 * @property integer $id
 * @property integer $idfactura
 * @property string $idmatricula
 * @property string $idasig
 * @property string $idnota
 * @property integer $credito
 * @property integer $vrepite
 * @property string $costo
 * @property string $horario
 * @property string $fecha
 */
class DetalleMatricula extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	public $asignatura;
	public $cnt;
	//public $paralelo;
	//public $nivel;
	public $carrera;
	public $periodo;
	public $aprobada;
	public $reprobada;
	

    public static function tableName()
    {
        return 'detalle_matricula';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idfactura', 'idasig', 'idcarr'], 'required'],
            [['idfactura', 'idnota', 'credito', 'vrepite', 'nivel', 'idcurso'], 'integer'],
            [['costo'], 'number'],
            [['fecha'], 'safe'],
            [['idmatricula'], 'string', 'max' => 20],
            [['idasig'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Matrícula No.',
            'idfactura' => 'Documento',
            'idmatricula' => 'Matrícula',
            'idasig' => 'Código',
            'idnota' => 'Idnota',
			'paralelo' => 'Paralelo',
            'credito' => 'Crédito',
            'vrepite' => 'Vrepite',
            'costo' => 'Costo',
            'nivel' => 'Nivel',
            'fecha' => 'Fecha',
			'cnt' => 'Total',
			'periodo' => 'Período',
			'idCarr0.NombCarr'=> 'Carrera',
			'matricula.statusMatricula' => 'Estado Matrícula',
			//'matricula.idParalelo' => 'Paralelo',
			'matricula.CIInfPer' => 'Cédula',
        ];
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(Factura::className(), ['id' => 'idfactura']);
    }
	public function getCurso()
    {
        return $this->hasOne(CursoOfertado::className(), ['id' => 'idcurso']);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCarr0()
    {
        return $this->hasOne(Carrera::className(), ['idCarr' => 'idcarr']);
    }


	public function getNotas()
    {
        return $this->hasMany(NotasDetalle::className(), ['iddetallematricula' => 'id']);
    }

	public function getNotasalumno()
    {
        return $this->hasOne(Notasalumnoasignatura::className(), ['iddetalle' => 'id']);
    }

	public function getSumanotas($componente, $hemi)
    {
		$models=$this->notas;
		$suma = 0;
		$cont = 0;
		$total = 0;
		if ($models) {
			foreach($models as &$nota) {
				if ($nota->getLibretasigla() == $componente && $nota->nota >= 0 
					&& $nota->libreta->hemisemestre == $hemi) {
					$suma += $nota->nota;
					$cont += 1;	
					$total = $suma/$cont;
				}
			}
			//$total = $cont>0?$suma/$cont:0;
		}
		//echo var_dump(count($models)); exit;
		return round($total);
    }

	public function getAprobada()
    {
		$models=$this->notasalumno?$this->notasalumno:null;
		$suma = 0;
		$cont = 0;
		$total = 0;
		#echo var_dump($models); exit;
		if ($models) {
			foreach($models as $nota) {
				if ($nota->aprobada == 1) {
					$cont += 1;	
					
				}
			}
			//$total = $cont>0?$suma/$cont:0;
		}
		#echo var_dump(count($models)); exit;
		return $count;
    }

	public function getPromedionotas($hemi)
    {
		$camodel = Configuracion::find()->where(['dato'=> 'CA'])->one();
		$cbmodel = Configuracion::find()->where(['dato'=> 'CB'])->one();
		$ccmodel = Configuracion::find()->where(['dato'=> 'CC'])->one();
		$exmodel = Configuracion::find()->where(['dato'=> 'EX'])->one();
		$asmodel = Configuracion::find()->where(['dato'=> 'AS'])->one();
		$ctmodel = Configuracion::find()->where(['dato'=> 'CT'])->one();
		$ca = $camodel?$camodel->valor/100:0;
		$cb = $cbmodel?$cbmodel->valor/100:0;
		$cc = $ccmodel?$ccmodel->valor/100:0;
		$ex = $exmodel?$exmodel->valor/100:0;
		$as = $asmodel?$asmodel->valor/100:0;
		$ct = $ctmodel?$ctmodel->valor/100:0;
		$total = ($this->getSumanotas('A', $hemi)*$ca + $this->getSumanotas('B', $hemi)*$cb 
					+ $this->getSumanotas('C', $hemi)*$cc)*$ct 
				+ $this->getSumanotas('X', $hemi)*$ex;
		//echo var_dump($this->getSumanotas('B')); exit;
		return round($total);
    }

	public function getNombCarrera()
    {
		$model=$this->idCarr0;
		return $model?$model->NombCarr:'';
    }
	

	public function getMatricula()
    {
        return $this->hasOne(Matricula::className(), ['idMatricula' => 'idmatricula']);
    }


    public function getParalelo0()
    {
		$model=$this->matricula;

//	echo var_dump($model);
//	exit;
		return $model?$model->idParalelo:'';
    }

	public function getNivel()
    {
		$model=$this->matricula;

//	echo var_dump($model);
//	exit;
		return $model?$model->idsemestre:'';
    }

	public function getCarrera()
	{
		$model=$this->matricula;
		return $model?$model->idCarr:'';
	}


	public function getNombreCarrera()
    {
		$carrera = '';
		if(isset($this->matricula)) $carrera = $this->matricula->getNombreCarrera();
		return $carrera;
    }	

    public function getIdAsig()
    {
        return $this->hasOne(Asignatura::className(), ['IdAsig' => 'idasig']);
    }

	//public function getAsignatura()
    //{
	//	$model=$this->idAsig;
	//	return $model?$model->NombAsig:'';
    //}

	public function getPeriodo()
    {
		$model = $this->factura;
		return $model?$model->idper:'';
    }

	public function outputCSV($dataProvider,$fileName = 'myCSVfile.csv') {
						
						#header("Content-type: text/csv");
                        #header("Content-Disposition: attachment; filename=".$fileName);
                        #header("Pragma: no-cache");
                        #header("Expires: 0");

                        $header = array('Column 1', 'Column 2', 'column 3', 'column 4', 'column 5');
                        $list =array();
                        array_push($list, $header);

				
                        foreach ($dataProvider as $data) {

                                        $row = array(
                                                $data->idCarr0->NombCarr,
                                                $data->idAsig->NombAsig,
                                                $data->nivel,
                                                $data->paralelo,
                                                $data->id,
                                                );
                                         array_push($list, $row);
                        }                       
				
                    $output = fopen("php://output", "w");
                    foreach ($list as $row) {
                        fputcsv($output, $row); // here you can change delimiter/enclosure
                    }
                    fclose($output);
                    die(); 
	}


}
