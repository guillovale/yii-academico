<?php

namespace app\controllers;
use Yii;
//use app\models\Matricula;
use app\models\MatriculaSearch;
use app\models\Periodolectivo;
use app\models\DetalleMatricula;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\data\ActiveDataProvider;

require_once(__DIR__ . '/../vendor/yii2-google-chart-master/GoogleChart.php');

class MatriculaController extends \yii\web\Controller
{
    public function actionIndex() {
	
	$parametros = Yii::$app->request->queryParams;
	#echo var_dump($parametros); exit;
	if ($parametros) {
		$idper = $parametros ["MatriculaSearch"]["idperiodo"];
		$periodo = Periodolectivo::find()
			->where(['idper' => $idper ])
			->one();
	}
	else {
		$periodo = Periodolectivo::find()
			->orderBy(['idper' => SORT_DESC])
			->one();
		$idper =  $periodo?$periodo->idper:0;
	}

	
	$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	$searchModel = new MatriculaSearch();
	#echo var_dump($parametros); exit;
	$searchModel->idPer = $idper;
    $dataProvider = $searchModel->search($parametros);
	$dataProvider1 = $searchModel->reporte_promedio_optativa($parametros);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'dataProvider1' => $dataProvider1,
        ]);
        // return $this->render('index');
    }

	public function actionReporte_promedio() {

		$parametros = Yii::$app->request->queryParams;
		#echo var_dump($parametros); exit;
		if ($parametros) {
			$idper = $parametros ["MatriculaSearch"]["idperiodo"];
			$periodo = Periodolectivo::find()
				->where(['idper' => $idper])
				->one();
		}
		else {
			$periodo = Periodolectivo::find()
				->orderBy(['idper' => SORT_DESC])
				->one();
			$idper =  $periodo?$periodo->idper:0;
		}

	
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	
		$searchModel = new MatriculaSearch();
		$searchModel->idPer = $idper;
        $dataProvider = $searchModel->reporte_promedio(Yii::$app->request->queryParams);
		$dataProvider1 = $searchModel->reporte_promedio_optativa(Yii::$app->request->queryParams);

        return $this->render('reporte_promedio', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'dataProvider1' => $dataProvider1,
        ]);
        // return $this->render('index');
    }

	public function actionReporte_etnia() {

		$parametros = Yii::$app->request->queryParams;
		#echo var_dump($parametros); exit;
		if ($parametros) {
			$idper = $parametros ["MatriculaSearch"]["idperiodo"];
			$periodo = Periodolectivo::find()
				->where(['idper' => $idper])
				->one();
		}
		else {
			$periodo = Periodolectivo::find()
				->orderBy(['idper' => SORT_DESC])
				->one();
			$idper =  $periodo?$periodo->idper:0;
		}

	
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	
		$searchModel = new MatriculaSearch();
		$searchModel->idPer = $idper;
		$dataProvider = $searchModel->reporte_etnia(Yii::$app->request->queryParams);

        return $this->render('reporte_etnia', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        // return $this->render('index');
    }

	public function actionReporte_estado() {

		$parametros = Yii::$app->request->queryParams;
		#echo var_dump($parametros); exit;
		if ($parametros) {
			$idper = $parametros ["MatriculaSearch"]["idperiodo"];
			$periodo = Periodolectivo::find()
				->where(['idper' => $idper])
				->one();
		}
		else {
			$periodo = Periodolectivo::find()
				->orderBy(['idper' => SORT_DESC])
				->one();
			$idper =  $periodo?$periodo->idper:0;
		}

	
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	
		$searchModel = new MatriculaSearch();
		$searchModel->idPer = $idper;
	
		#$searchModel = new MatriculaSearch();
        $dataProvider = $searchModel->reporte_estado(Yii::$app->request->queryParams);
	
	
        return $this->render('reporte_estado', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        // return $this->render('index');
    }

	public function actionReporte_discapacidad() {

		$parametros = Yii::$app->request->queryParams;
		#echo var_dump($parametros); exit;
		if ($parametros) {
			$idper = $parametros ["MatriculaSearch"]["idperiodo"];
			$periodo = Periodolectivo::find()
				->where(['idper' => $idper])
				->one();
		}
		else {
			$periodo = Periodolectivo::find()
				->orderBy(['idper' => SORT_DESC])
				->one();
			$idper =  $periodo?$periodo->idper:0;
		}

	
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	
		$searchModel = new MatriculaSearch();
		$searchModel->idPer = $idper;
	
		#$searchModel = new MatriculaSearch();
        $dataProvider = $searchModel->reporte_discapacidad(Yii::$app->request->queryParams);
	
	
        return $this->render('reporte_discapacidad', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        // return $this->render('index');
    }

	public function actionReporte_porparalelo($idper, $idcarr) {

		$query = new Query;
		$subQuery = new Query;
		$periodo = Periodolectivo::find()
			->where(['idper' => $idper ])
			#->orderBy(['idper' => SORT_DESC])
			->one();
		#$idper =  $periodo?$periodo->idper:0;
		
	
		$this->view->params['nombreperiodo'] = $periodo?$periodo->DescPerLec:0;
	
		$subQuery->select(['f.idper', 'f.cedula','m.idcarr', 'c.NombCarr', 
						'nivel', 'paralelo', 'max(nivel) as nivelm'])
		->from('detalle_matricula m')
		->leftJoin('factura f', 'f.id = m.idfactura')
		->leftJoin('carrera c', 'm.idcarr = c.idcarr')
		->where(['f.idper'=> $idper, 'm.idcarr'=> $idcarr, 'm.estado'=>1, 'f.tipo_documento'=> 'MATRICULA'])
		//->andwhere('idcarr not in("056", "197", "206", "601", "602","603")')
		#->andwhere("c.optativa = 0 or c.optativa is null")
		#->andwhere(" f.tipo_documento = 'MATRICULA' ")
		//->andwhere(" m.observmatricula = '' or m.observmatricula is null")
		//->andwhere("m.statusMatricula = 'APROBADA'")
		->groupBy(['f.cedula'])
		->orderBy(['nivelm' => SORT_DESC]);
		#->orderBy(['m.nivel' => SORT_DESC]);
		
		$query->select(['idper','idcarr', 'NombCarr', 'nivelm', 'paralelo', 'count(*) as total', 'cedula'

			])
		->from([$subQuery])
		->groupBy(['nivelm', 'paralelo']);


		$dataProvider = new ActiveDataProvider([
            'query' => $query,
				'sort' =>false,
				'pagination' => [
				'pageSize' => 200,
			    ],
        ]);
	
		
		
        return $this->render('reporte_porparalelo', [
            'idper' => $idper,
			'dataProvider' => $dataProvider,
            
        ]);
        // return $this->render('index');
    }


}
