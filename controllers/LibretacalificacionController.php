<?php

namespace app\controllers;

use Yii;
use app\models\LibretaCalificacion;
use app\models\LibretaCalificacionSearch;
use app\models\Componentescalificacion;
use app\models\Periodolectivo;
use app\models\Configuracion;
use app\models\Parametroscalificacion;
use app\models\NotasDetalle;
use app\models\Notasalumnoasignatura;
use app\models\DetalleMatricula;
use app\models\Informacionpersonal;
use app\models\InformacionpersonalD;
use app\models\CursoOfertado;
use app\models\Docenteperasig;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\helpers\Url;
use yii\db\Query;
require_once(__DIR__ . '/../vendor/tcpdf/tcpdf.php');
//usar clase TCPDF
use TCPDF;
/**
 * LibretaCalificacionController implements the CRUD actions for LibretaCalificacion model.
 */
class LibretacalificacionController extends Controller
{
    public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'index', 'consolidado'],
                'rules' => [
                    [
                        //'actions' => ['delete','update', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all LibretaCalificacion models.
     * @return mixed
     */
    public function actionIndex($idcurso)
    {
		$modelCurso = CursoOfertado::find()->where(['id'=>$idcurso])->one();
		$matriculados = Detallematricula::find()
						->joinWith('factura')
						->joinWith('factura.cedula0')
						->where(['idcurso' => $idcurso, 'estado' => 1])
						->orderBy(['informacionpersonal.ApellInfPer' => SORT_ASC, 
								'informacionpersonal.ApellMatInfPer' => SORT_ASC, 
								'informacionpersonal.NombInfPer' => SORT_ASC]);
		$datamatriculados = new ActiveDataProvider([
			'query' => $matriculados,
			'pagination' => [
				'pageSize' => 80,
			],
			'sort' => [
				'defaultOrder' => [
				    #'informacionpersonal.ApellInfPer' => SORT_ASC,
				    #'cedula' => SORT_ASC,
					#'factura.cedula0.NombInfPer' => SORT_ASC, 
				]
			],
		]);
		#echo var_dump($idcurso, ' ' ,$model);exit;
        $searchModel = new LibretaCalificacionSearch();
		$searchModel->idcurso = $idcurso;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		// Remember current URL 
		Url::remember();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'datamatriculados' => $datamatriculados,
			'modelCurso' => $modelCurso,
        ]);
    }

	public function actionDocente($id)
    {
		$modelDocente = Docenteperasig::find()->where(['dpa_id'=>$id])->one();
		#echo var_dump($idcurso, ' ' ,$model);exit;
        $searchModel = new LibretaCalificacionSearch();
		$searchModel->iddocenteperasig = $modelDocente?$modelDocente->dpa_id:'';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		// Remember current URL 
		//Url::remember();
        return $this->render('docente', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'modelDocente' => $modelDocente,
        ]);
    }
    /**
     * Displays a single LibretaCalificacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$modelLibreta = $this->findModel($id);
		
		//echo var_dump($porciones); exit;
		$detallematricula = DetalleMatricula::find()
							->select(['detalle_matricula.id', 
							'concat(informacionpersonal.ApellInfPer, " ", 
							informacionpersonal.ApellMatInfPer,	" ", informacionpersonal.NombInfPer) as nombre'])
							->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
							->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
							->where(['idcurso'=>$modelLibreta->idcurso, 'estado'=> 1])
							->orderBy(['ApellInfPer'=>SORT_ASC]);
		$alumnos = ArrayHelper::map($detallematricula
							//->select('detalle_matricula.id, informacionpersonal.ApellInfPer as nombre')
							->asArray()
							->all(), 'id', 'nombre');
		

		$model = new NotasDetalle();
		$model->idlibreta = $id;
		$query = NotasDetalle::find()
						->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
						->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
						->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
						->where(['idlibreta'=>$id])
						->orderBy(['ApellInfPer'=>SORT_ASC]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 80,],
		]);
				/*
				if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
					$alumno = $query->andWhere(['iddetallematricula'=>$model->iddetallematricula])->one();
					if (count($alumno)) {
						$alumno->nota = $model->nota;
						$cedulad = $alumno->libreta->iddocente;
						$cedula = $alumno->detallematricula->factura->cedula0->CIInfPer;
						if ($alumno->save()) {
							$texto = 'De acuerdo a lo solicitado,  
									Vicerrectorado Académico ha procedido con la actualización de : Cédula: '. 
									$cedula . ' = ' . 	$dato . ' hemisemestre: ' . $hemi . ' componente: '.
									$componente . ' nota: ' . $alumno->nota;
							try {
								$this->enviarMail($cedulad, $cedula, $texto);
							}catch (Exception $e) {
								echo 'Excepción capturada: ',  $e->getMessage(), "\n";
							}
						}
					}
					else {
						if ($model->save()) {
							//echo var_dump($model->detallematricula->factura->cedula0->CIInfPer); exit;
							$cedulad = $model->libreta->iddocente;
							$cedula = $model->detallematricula->factura->cedula0->CIInfPer;
							$texto = 'De acuerdo a lo solicitado, 
									Vicerrectorado Académico ha procedido con la actualización de : Cédula: ' .$cedula. 
									' = '.$dato . ' hemisemestre: '. $hemi. ' componente: '.
									$componente. ' nota: '.$model->nota;
							try {
								$this->enviarMail($cedulad, $cedula, $texto);
							}catch (Exception $e) {
								echo 'Excepción capturada: ',  $e->getMessage(), "\n";
							}
						}

					}
					return $this->redirect(Yii::$app->request->referrer);
				}
				*/
		return $this->render('view', [
				'dataProvider' => $dataProvider, 
				'model'=> $model, 
				'alumnos'=> $alumnos,
				'modelLibreta'=> $modelLibreta
		]);
			
		//return $this->redirect(Yii::$app->request->referrer);
    }

	public function actionVer($id)
    {
		$modelLibreta = $this->findModel($id);
				
		$model = new NotasDetalle();
		$model->idlibreta = $id;
		$query = NotasDetalle::find()
						->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
						->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
						->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
						->where(['idlibreta'=>$id])
						->orderBy(['ApellInfPer'=>SORT_ASC]);
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => ['pageSize' => 80,],
		]);
				
		return $this->render('ver', [
				'dataProvider' => $dataProvider, 
				'model'=> $model, 
				'modelLibreta'=> $modelLibreta
		]);
			
		//return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Creates a new LibretaCalificacion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($iddocenteperasig)
    {
		$identidad = Yii::$app->user->identity;
		$modelDocente = Docenteperasig::find()->where(['dpa_id'=>$iddocenteperasig])->one();
		if (count($identidad)) {
			if ($identidad->idperfil == 'diracad' || $identidad->idperfil == 'sa') {

				$componente = ArrayHelper::map(componentescalificacion::find()
								->select('idcomponente, componente')
								->asArray()
								->all(), 'idcomponente', 'componente');
				$parametro = ArrayHelper::map(parametroscalificacion::find()
								->select('idparametro, parametro')
								->asArray()
								->all(), 'idparametro', 'parametro');
				$periodo = ArrayHelper::map(periodolectivo::find()
								->select('idper, DescPerLec')
								->orderBy(['idper'=>SORT_DESC])
								->all(), 'idper', 'DescPerLec');
		
				$this->view->params['componente'] = $componente;
				$this->view->params['parametro'] = $parametro;
				$this->view->params['periodo'] = $periodo;
				
				$model = new LibretaCalificacion();
				$model->idper = $modelDocente->idPer;
				$model->iddocenteperasig = $modelDocente->dpa_id;
				$model->iddocente = $modelDocente->CIInfPer;
				$model->fecha = date('Y-m-d');
				if ( $model->load(Yii::$app->request->post()) ) {
					if ($model->idparametro == 5)
						$model->hemisemestre = 0;
					if ($model->save()) {
				    	return $this->redirect(['docente', 'id' => $iddocenteperasig]);
					}
				} else {
					//echo var_dump($model->getErrors()); exit;
				    return $this->render('create', [
				        'model' => $model,
						'modelDocente' => $modelDocente,
				    ]);
				}
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
    }

	public function actionCrearcomponente($idcurso)
    {
		$identidad = Yii::$app->user->identity;
		$modelcurso = Cursoofertado::find()->where(['id'=>$idcurso])->one();
		if ($identidad && $modelcurso) {
			if ($identidad->idperfil == 'diracad' || $identidad->idperfil == 'sa') {

				$componente = ArrayHelper::map(componentescalificacion::find()
								->select('idcomponente, componente')
								->asArray()
								->all(), 'idcomponente', 'componente');
				$parametro = ArrayHelper::map(parametroscalificacion::find()
								->select('idparametro, parametro')
								->asArray()
								->all(), 'idparametro', 'parametro');
				$periodo = ArrayHelper::map(periodolectivo::find()
								->select('idper, DescPerLec')
								->orderBy(['idper'=>SORT_DESC])
								->all(), 'idper', 'DescPerLec');
		
				$this->view->params['componente'] = $componente;
				$this->view->params['parametro'] = $parametro;
				$this->view->params['periodo'] = $periodo;
				
				$model = new LibretaCalificacion();
				$model->idper = $modelcurso->idper;
				$model->idcurso = $modelcurso->id;
				$model->iddocente = $modelcurso->iddocente;
				$model->fecha = date('Y-m-d');
				if ( $model->load(Yii::$app->request->post()) ) {
					#if ($model->idparametro == 5)
					#	$model->hemisemestre = 0;
					if ($model->save()) {
						#echo var_dump($model); exit;
				    	return $this->redirect(['index', 'idcurso' => $modelcurso->id]);
					}
				} else {
					//echo var_dump($model->getErrors()); exit;
				    return $this->render('crearcomponente', [
				        'model' => $model,
						'modelcurso' => $modelcurso,
				    ]);
				}
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Updates an existing LibretaCalificacion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$modelLibreta = $this->findModel($id);

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //    return $this->redirect(['view', 'id' => $model->id]);
        //} else {
        //    return $this->render('update', [
        //        'model' => $model,
        //    ]);
        //}
		//return $this->redirect(Yii::$app->request->referrer);

		$identidad = Yii::$app->user->identity;
		if ($identidad && $modelLibreta) {
			if ($identidad->idperfil == 'diracad' || $identidad->idperfil == 'sa') {
				$this->view->params['hemi'] = $modelLibreta->hemisemestre;
				$this->view->params['componente'] = $modelLibreta->idcomponente;
				$this->view->params['docente'] = $modelLibreta->iddocente;
				$this->view->params['dato'] = '';#$dato;
				#$porciones = explode(";", $dato);
				//echo var_dump($porciones); exit;
				$detallematricula = DetalleMatricula::find()
								->select(['detalle_matricula.id', 
								'concat(informacionpersonal.ApellInfPer, " ", 
								informacionpersonal.ApellMatInfPer,	" ", informacionpersonal.NombInfPer) as nombre'])
								->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
								->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
								->where(['idcurso'=>$modelLibreta->idcurso])
								#->where(['idcarr'=>$modelLibreta->docenteasignatura->idCarr, 
								#		'idasig' => $modelLibreta->docenteasignatura->idAsig, 
								#		'nivel' => $modelLibreta->docenteasignatura->idSemestre, 
								#		'paralelo' => $modelLibreta->docenteasignatura->idParalelo, 
								#		'idper'=> $modelLibreta->docenteasignatura->idPer])
								->orderBy(['ApellInfPer'=>SORT_ASC]);
				$alumnos = ArrayHelper::map($detallematricula
								//->select('detalle_matricula.id, informacionpersonal.ApellInfPer as nombre')
								->asArray()
								->all(), 'id', 'nombre');
		

				$model = new NotasDetalle();
				$model->idlibreta = $id;
				$model->fecha = date('Y-m-d');
				$model->usuario = $identidad->LoginUsu;
				$query = NotasDetalle::find()
									->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
									->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
									->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
									->where(['idlibreta'=>$id])
									->orderBy(['ApellInfPer'=>SORT_ASC]);
				$dataProvider = new ActiveDataProvider([
						'query' => $query,
					'pagination' => ['pageSize' => 80,],
				]);
				if ($model->load(Yii::$app->request->post()) && $model->validate() ) {
					$alumno = $query->andWhere(['iddetallematricula'=>$model->iddetallematricula])->one();
					if (count($alumno)) {
						$alumno->nota = $model->nota;
						$alumno->fecha = date('Y-m-d');
						$alumno->usuario = $identidad->LoginUsu;
						$cedulad = $alumno->libreta->iddocente;
						$cedula = $alumno->detallematricula->factura->cedula0->CIInfPer;
						if ($alumno->save()) {
							$this->publicarNota($model->iddetallematricula);
							$texto = 'De acuerdo a lo solicitado,  
									Vicerrectorado Académico ha procedido con la actualización de : Cédula: '. 
									$cedula . ' hemisemestre: ' . $modelLibreta->hemisemestre . ' componente: '.
									$modelLibreta->idcomponente . ' nota: ' . $alumno->nota;
							try {
								$this->enviarMail($cedulad, $cedula, $texto);
							}catch (Exception $e) {
								echo 'Excepción capturada: ',  $e->getMessage(), "\n";
							}
						}
					}
					else {
						if ($model->save()) {
							$this->publicarNota($model->iddetallematricula);
							//echo var_dump($model->detallematricula->factura->cedula0->CIInfPer); exit;
							$cedulad = $model->libreta->iddocente;
							$cedula = $model->detallematricula->factura->cedula0->CIInfPer;
							$texto = 'De acuerdo a lo solicitado, 
									Vicerrectorado Académico ha procedido con la actualización de : Cédula: ' .$cedula. 
									' hemisemestre: '. $modelLibreta->hemisemestre. ' componente: '.
									$modelLibreta->idcomponente. ' nota: '.$model->nota;
							try {
								$this->enviarMail($cedulad, $cedula, $texto);
							}catch (Exception $e) {
								echo 'Excepción capturada: ',  $e->getMessage(), "\n";
							}
						}

					}
					return $this->redirect(Yii::$app->request->referrer);
				}
		
				return $this->render('update', [
					'dataProvider' => $dataProvider, 
					'model'=> $model, 
					'alumnos'=> $alumnos,
					'modelLibreta'=> $modelLibreta
			   	]);
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing LibretaCalificacion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
       // $this->findModel($id)->delete();

        //return $this->redirect(['index']);
		return $this->redirect(Yii::$app->request->referrer);
    }

	public function actionConsolidado($idcurso)
    {
		$modelcurso = $this->findCurso($idcurso);
		if ($modelcurso) {

		$sql = "SELECT c.cedula, c.nombre, c.idnota, c.iddetallematricula, 
			max(c.GA1) as GA1, max(c.NGA1) as NGA1, max(c.GA2) as GA2 , 
			max(c.NGA2) as NGA2, max(c.PA) as PA , max(c.NPA) as NPA , max(c.X1) as X1, max(c.NX1) as NX1,
			max(c.X2) as X2, max(c.NX2) as NX2,
			round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5) as suma, 
			max(c.M) as MJ, max(c.NM) as NM,
			max(c.AT) as AT, max(c.NAT) as NAT,
			if( max(c.NAT) >= 7 and (max(c.NM) >= 7 or 
					round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5) >= 7),
			 'Aprobada','Reprobada') as Estado,
			if(max(c.NM) > round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5), max(c.NM),
				round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5)	 ) as notafinal
			from(
			SELECT notas_detalle.idnota, libreta_calificacion.idcurso, libreta_calificacion.id, libreta_calificacion.idcomponente,
			notas_detalle.iddetallematricula, notas_detalle.nota, 
			if(libreta_calificacion.idcomponente = 27, notas_detalle.idnota, '') as GA1,
			if(libreta_calificacion.idcomponente = 27, notas_detalle.nota, '') as NGA1,
			if(libreta_calificacion.idcomponente = 28, notas_detalle.idnota, '') as GA2,
			if(libreta_calificacion.idcomponente = 28, notas_detalle.nota, '') as NGA2,
			if(libreta_calificacion.idcomponente = 29, notas_detalle.idnota, '') as PA,
			if(libreta_calificacion.idcomponente = 29, notas_detalle.nota, '') as NPA,
			if(libreta_calificacion.idcomponente = 30, notas_detalle.idnota, '') as X1,
			if(libreta_calificacion.idcomponente = 30, notas_detalle.nota, '') as NX1,
			if(libreta_calificacion.idcomponente = 31, notas_detalle.idnota, '') as X2,
			if(libreta_calificacion.idcomponente = 31, notas_detalle.nota, '') as NX2,
			if(libreta_calificacion.idcomponente = 32, notas_detalle.idnota, '') as M,
			if(libreta_calificacion.idcomponente = 32, notas_detalle.nota, '') as NM, 
			if(libreta_calificacion.idcomponente = 33, notas_detalle.idnota, '') as AT,
			if(libreta_calificacion.idcomponente = 33, notas_detalle.nota, '') as NAT,
			concat(informacionpersonal.ApellInfPer, ' ', informacionpersonal.ApellMatInfPer, ' ',
				informacionpersonal.NombInfPer ) as nombre, factura.cedula
			FROM `notas_detalle`
			LEFT JOIN libreta_calificacion on libreta_calificacion.id = notas_detalle.idlibreta
			LEFT JOIN curso_ofertado on curso_ofertado.id = libreta_calificacion.idcurso
			LEFT JOIN detalle_matricula on detalle_matricula.id = notas_detalle.iddetallematricula
			LEFT JOIN factura on factura.id = detalle_matricula.idfactura
			LEFT JOIN informacionpersonal on informacionpersonal.CIInfPer = factura.cedula
			where curso_ofertado.id = $idcurso) c
			GROUP by c.iddetallematricula 
			order by c.nombre";
			#echo var_dump($modelcurso); exit;
			$this->view->params['idper'] = $modelcurso->idper;
			$this->view->params['idcurso'] = $idcurso;
			$this->view->params['idcarr'] = $modelcurso->detallemalla->malla->idcarrera;
			$this->view->params['idasig'] = $modelcurso->detallemalla->idasignatura;
			$this->view->params['periodo'] = $modelcurso->periodo->DescPerLec;
			$this->view->params['carrera'] = $modelcurso->detallemalla->malla->carrera->NombCarr;
			$this->view->params['asignatura'] = $modelcurso->detallemalla->asignatura->NombAsig;
			$this->view->params['nivel'] = $modelcurso->detallemalla->nivel;
			$this->view->params['paralelo'] = $modelcurso->paralelo;
				#echo var_dump($sql); exit;
			#$query = new Query;
		    #$query1 = Yii::$app->db->createCommand($sql);
			#$query->select($sql);
			#$model = $query1->queryAll();
			#echo var_dump($model);exit;
		//////////////////////////////////
			
			$provider = new SqlDataProvider([
				'sql' => $sql,
				#'params' => [':status' => 1],
				#'totalCount' => $count,
				'pagination' => [
					'pageSize' => 100,
				],
				#'sort' => [
				#	'attributes' => [
				#		'title',
				#		'view_count',
				#		'created_at',
				#	],
				#],
			]);
        
			$model = $provider->getModels();
			#foreach($model as $dato) {
			#	echo var_dump($dato['suma']);			
			#}#exit;
			 #$sourceModel = new \namespace\YourGridModel;
   			 #$dataProvider = $sourceModel->search(Yii::$app->request->getQueryParams());
   			 #$models = $dataProvider->getModels();
			#if (Model::loadMultiple($model, Yii::$app->request->post())) {
				#echo var_dump($model[0]['suma'], Yii::$app->request->getQueryParams());exit;
			#}

            return $this->render('consolidado', [
				'dataProvider' => $provider,
                'model' => $model,
				'idcurso'=> $idcurso,
            ]);
		}
		return $this->redirect(Url::previous());
       
    }

	public function actionListar($id)
    {
		//echo var_dump($id); exit;
        $countPosts = Parametroscalificacion::find()
                ->where(['idparametro' => $id])
                ->count();
 
        $posts = Componentescalificacion::find()
                ->where(['idparametro' => $id])
                ->orderBy('componente ASC')
                ->all();
 
        if($countPosts>0){
            foreach($posts as $post){
                echo "<option value='".$post->idcomponente."'>".$post->componente."</option>";
            }
        }
        else{
            echo "<option>-</option>";
        }
	}

	public function enviarMail($cedulad, $cedula, $texto)
	{
		$emailtis = 'tics@utelvt.edu.ec';
		$docente = InformacionpersonalD::find()
												->where(['CIInfPer'=> $cedulad])
												->one();
		$alumno = Informacionpersonal::find()
												->where(['CIInfPer'=> $cedula])
												->one();
		$emaildocente = (count($docente))?
					($docente->mailInst !== null?$docente->mailInst:'tics@utelvt.edu.ec'):'tics@utelvt.edu.ec';
		$emailalumno = count($alumno)?
					($alumno->mailPer !== null?$alumno->mailPer:'tics@utelvt.edu.ec'):'tics@utelvt.edu.ec';
		
		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emaildocente)
				->setCc($emailalumno)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();

		$message = Yii::$app->mailer->compose();
		$message->setFrom(Yii::$app->params['adminEmail'])
				->setTo($emailtis)
				->setSubject('Modificación de nota')
				->setTextBody($texto)
				->send();		
	}

	public function getNotas($idmatricula)
    {
		$nota1 = 0;
		$nota2 = 0;
		$ca = configuracion::find()->where(['dato'=> 'CA'])->one();
		$cb = configuracion::find()->where(['dato'=> 'CB'])->one();
		$cc = configuracion::find()->where(['dato'=> 'CC'])->one();
		$ex = configuracion::find()->where(['dato'=> 'EX'])->one();
		$as = configuracion::find()->where(['dato'=> 'AS'])->one();
		$ct = configuracion::find()->where(['dato'=> 'CT'])->one();
		$compA = $ca?$ca->valor/100:0;
		$compB = $cb?$cb->valor/100:0;
		$compC = $cc?$cc->valor/100:0;
		$compEx = $ex?$ex->valor/100:0;
		$compAs = $as?$as->valor/100:0;
		$compT = $ct?$ct->valor/100:0;

		$subquery = NotasDetalle::find()
					->select(['factura.cedula,libreta_calificacion.idparametro, idlibreta, iddetallematricula, 
						hemisemestre, idfactura, idcomponente, nota, 
						concat(ApellInfPer, " ", ApellMatInfPer, " ", NombInfPer) as estudiante,
						IF(hemisemestre = 1 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA1,
						IF(hemisemestre = 1 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB1,
						IF(hemisemestre = 1 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC1,
						IF(hemisemestre = 1 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM1,
						IF(hemisemestre = 1 && sigla = "T", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Ast1,
						IF(hemisemestre = 2 && sigla = "A", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompA2,
						IF(hemisemestre = 2 && sigla = "B", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompB2,
						IF(hemisemestre = 2 && sigla = "C", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS CompC2,
						IF(hemisemestre = 2 && sigla = "X", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS EXM2,
						IF(hemisemestre = 2 && sigla = "T", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Ast2,
						IF(hemisemestre = 0 && sigla = "R", (cast(sum(nota)/count(nota) as UNSIGNED)), "") AS Rec
						'])
					->leftJoin('libreta_calificacion', 'libreta_calificacion.id = notas_detalle.idlibreta')
					->leftJoin('parametroscalificacion', 'parametroscalificacion.idparametro = libreta_calificacion.idparametro')
					->leftJoin('detalle_matricula', 'detalle_matricula.id = notas_detalle.iddetallematricula')
					->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
					->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
					->where(['iddetallematricula'=>$idmatricula])
					->groupBy(['libreta_calificacion.hemisemestre','libreta_calificacion.idparametro'])
					->orderBy(['libreta_calificacion.hemisemestre'=>SORT_ASC, 'libreta_calificacion.idparametro'=>SORT_ASC]);
		$query = new Query();
		$query->select(['*'])
			->addSelect(['sum(c.CompA1) as A1, sum(c.CompB1) as B1, sum(c.CompC1) as C1, sum(c.EXM1) as Ex1, sum(c.Ast1) as As1,
						sum(c.CompA2) as A2, sum(c.CompB2) as B2, sum(c.CompC2) as C2, sum(c.EXM2) as Ex2, sum(c.Ast2) as As2,
						sum(c.Rec) as Suf
						'])
			->from(['c' => $subquery]);
		$row = $query->one();
		$estado = '';
		//$recuperacion = 0;
		//$sumanotas = 0;
		$notas = [];
		$aprobada = 0;
		
		if (count($row)) { 
			$nota1 = round( ($row["A1"]*$compA + $row["B1"]*$compB + $row["C1"]*$compC)*$compT + $row["Ex1"]*$compEx );
			$nota2 = round( ($row["A2"]*$compA + $row["B2"]*$compB + $row["C2"]*$compC)*$compT + $row["Ex2"]*$compEx );
			$asis1 = round($row["As1"] >=0 && $row["As1"] <=10)?$row["As1"]*10:$row["As1"];
			$asis2 = round($row["As2"] >=0 && $row["As2"] <=10)?$row["As2"]*10:$row["As2"];
			$promedionota = round((round($nota1) + round($nota2))/2, 2);
			$promedioasistencia = round((round($asis1) + round($asis2))/2, 2);
			$recp = round($row["Suf"]?$row["Suf"]:0);
			if ( $promedionota >= 7 && ($promedioasistencia >= 70 && $promedioasistencia <= 100) ) {
				$estado = 'APROBADA';
				$aprobada = 1;	
			}
			elseif ( $promedionota >= 5 && $promedionota < 7 && ($promedioasistencia >= 70 && $promedioasistencia <= 100) )
				if ($promedionota*2 + $recp >= 20){
					$estado = 'APROBADA';
					$promedionota = 7;
					$aprobada = 1;
				}
				else {
					$estado = 'REPROBADA';
					$aprobada = 0;
				}
			else {
				$estado = 'REPROBADA';
				$aprobada = 0;
			}
			
			$notas = ['nota1'=> $nota1, 'nota2'=> $nota2, 'asis1'=> $asis1, 'asis2'=> $asis2, 'recp'=> $recp,
					'nota'=> $promedionota, 'asistencia'=> $promedioasistencia, 
					'estado'=> $estado, 'aprobada'=> $aprobada];
		}
		return $notas;
		// echo var_dump($notas); exit;
    }

	public function publicarNota($idmatricula)
    {
		
		if ($idmatricula) {
			//ini_set('max_execution_time', 900);
			//ini_set('memory_limit', '1024MB');
			//$ids = ArrayHelper::getColumn($detallematricula, 'idnota');
			$notaalumno = Notasalumnoasignatura::find()
									->where(['iddetalle'=> $idmatricula])
									//->andWhere(['StatusCalif' => 1])
									->one();
			$matricula = DetalleMatricula::find()
				#->select('id')
				->where(['id'=>$idmatricula, 'estado'=> 1])
				->one();
			$notas = $this->getNotas($idmatricula);
			if ($notas["nota"] >= 0 && $notas["asistencia"] >=0 && $notas["aprobada"] >= 0) {
				if ($notaalumno) {
					$notaalumno->CalifFinal = $notas["nota"];
					$notaalumno->asistencia = $notas["asistencia"];
					$notaalumno->StatusCalif = 3;
					$notaalumno->observacion = $notas["estado"];
					$notaalumno->aprobada = $notas["aprobada"];
					if (!$notaalumno->save(false)) {
						echo var_dump($notaalumno->getErrors()); exit;
					}
				}	
				elseif ($matricula) {
					$modelnota = new Notasalumnoasignatura();
					$modelnota->idPer = $matricula->factura->idper;
					$modelnota->CIInfPer = $matricula->factura->cedula;
					$modelnota->idAsig = $matricula->idasig;
					$modelnota->iddetalle = $matricula->id;
					$modelnota->CalifFinal = $nota["nota"];
					$modelnota->asistencia = $nota["asistencia"];
					$modelnota->StatusCalif = 3;
					$modelnota->observacion = $nota["estado"];
					$modelnota->aprobada = $nota["aprobada"];
					$modelnota->VRepite = 1;
					$modelnota->registro = date('Y-m-d H:i:s');
					$modelnota->convalidacion = 0;
								//$modelnota->observacion_efa = 'nota homologada';
					$modelnota->save();
								#echo var_dump($modelnota->errors); exit;
						
				}
			}
	
		}
	
		//return $this->redirect(Yii::$app->request->referrer);

	}

	public function actionAgregar($idcurso)
    {	
		$modelLibreta = LibretaCalificacion::find()->where(['idcurso'=> $idcurso])->all();
		$hoy  = date('Y-m-d');
		if ($modelLibreta) {
			foreach($modelLibreta as $libreta) {
				$modelNotas = NotasDetalle::find()
							->select('iddetallematricula')
							//->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
							//->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
							->where(['idlibreta'=> $libreta->id])->column();
				$detallematricula = DetalleMatricula::find()
							->select('detalle_matricula.id')
							->leftJoin('factura', 'factura.id = detalle_matricula.idfactura')
							->leftJoin('informacionpersonal', 'informacionpersonal.CIInfPer = factura.cedula')
							->where(['idcurso'=>$idcurso, 'estado' => 1])
							#->where(['idcarr'=>$idcarr, 'idasig' => $idasig, 'nivel' => $nivel, 
							#	'paralelo' => $paralelo, 'idper'=>$idper])
							->andWhere(['not in','detalle_matricula.id', $modelNotas])
							//->orderBy(['ApellInfPer'=>SORT_ASC])
							->all();
							//->column();

				
				if ($detallematricula) {
					foreach($detallematricula as $detalle){
						$modelnota = new NotasDetalle();
						$modelnota->idlibreta = $libreta->id;
						$modelnota->iddetallematricula = $detalle->id;
						$modelnota->nota = 0;
						$modelnota['fecha_crea'] = date("Y-m-d H:i:s");
						$modelnota->save();
					}
				}
			}
			
		}

        return $this->redirect(Yii::$app->request->referrer);
    }

	public function actionImprimir($idcurso)
    {

		$cursomodel = $this->findCurso($idcurso);
		$idcursomodel = $cursomodel?$cursomodel->id:0;
		$idper = $cursomodel?$cursomodel->idper:'';
		$periodo = $cursomodel?$cursomodel->periodo->DescPerLec:'';
		$asignatura = $cursomodel?$cursomodel->detallemalla->asignatura->NombAsig:'';
		$idasignatura = $cursomodel?$cursomodel->detallemalla->idasignatura:'';
		$docente = $cursomodel?$cursomodel->getNombreDocente():'';
		$iddocente = $cursomodel?$cursomodel->iddocente:'';
		$carrera = $cursomodel?$cursomodel->detallemalla->malla->carrera->NombCarr:'';
		$nivel = $cursomodel?$cursomodel->detallemalla->nivel:'';
		$paralelo = $cursomodel?$cursomodel->paralelo:'';

		$sql = "SELECT c.cedula, c.nombre, c.idnota, c.iddetallematricula, c.idper, c.idasignatura,
			max(c.GA1) as GA1, max(c.NGA1) as NGA1, max(c.GA2) as GA2 , 
			max(c.NGA2) as NGA2, max(c.PA) as PA , max(c.NPA) as NPA , max(c.X1) as X1, max(c.NX1) as NX1,
			max(c.X2) as X2, max(c.NX2) as NX2,
			round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5) as suma, 
			max(c.M) as MJ, max(c.NM) as NM,
			max(c.AT) as AT, max(c.NAT) as NAT,
			if( max(c.NAT) >= 7 and (max(c.NM) >= 7 or 
					round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5) >= 7),
			 'APROBADA','REPROBADA') as Estado,
			if( max(c.NAT) >= 7 and (max(c.NM) >= 7 or 
					round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5) >= 7),
			 1,0) as aprobada, 
			if(max(c.NM) > round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5), max(c.NM),
				round((max(c.NGA1) + max(c.NGA2) + max(c.NPA) + max(c.NX1) + max(c.NX2))/5)	 ) as notafinal
			from(
			SELECT notas_detalle.idnota, libreta_calificacion.idcurso, libreta_calificacion.id, libreta_calificacion.idcomponente,
			notas_detalle.iddetallematricula, notas_detalle.nota, curso_ofertado.idper, detalle_malla.idasignatura,
			if(libreta_calificacion.idcomponente = 27, notas_detalle.idnota, '') as GA1,
			if(libreta_calificacion.idcomponente = 27, notas_detalle.nota, '') as NGA1,
			if(libreta_calificacion.idcomponente = 28, notas_detalle.idnota, '') as GA2,
			if(libreta_calificacion.idcomponente = 28, notas_detalle.nota, '') as NGA2,
			if(libreta_calificacion.idcomponente = 29, notas_detalle.idnota, '') as PA,
			if(libreta_calificacion.idcomponente = 29, notas_detalle.nota, '') as NPA,
			if(libreta_calificacion.idcomponente = 30, notas_detalle.idnota, '') as X1,
			if(libreta_calificacion.idcomponente = 30, notas_detalle.nota, '') as NX1,
			if(libreta_calificacion.idcomponente = 31, notas_detalle.idnota, '') as X2,
			if(libreta_calificacion.idcomponente = 31, notas_detalle.nota, '') as NX2,
			if(libreta_calificacion.idcomponente = 32, notas_detalle.idnota, '') as M,
			if(libreta_calificacion.idcomponente = 32, notas_detalle.nota, '') as NM, 
			if(libreta_calificacion.idcomponente = 33, notas_detalle.idnota, '') as AT,
			if(libreta_calificacion.idcomponente = 33, notas_detalle.nota, '') as NAT,
			concat(informacionpersonal.ApellInfPer, ' ', informacionpersonal.ApellMatInfPer, ' ',
				informacionpersonal.NombInfPer ) as nombre, factura.cedula
			FROM `notas_detalle`
			LEFT JOIN libreta_calificacion on libreta_calificacion.id = notas_detalle.idlibreta
			LEFT JOIN curso_ofertado on curso_ofertado.id = libreta_calificacion.idcurso
			LEFT JOIN detalle_malla on detalle_malla.id = curso_ofertado.iddetallemalla
			LEFT JOIN detalle_matricula on detalle_matricula.id = notas_detalle.iddetallematricula
			LEFT JOIN factura on factura.id = detalle_matricula.idfactura
			LEFT JOIN informacionpersonal on informacionpersonal.CIInfPer = factura.cedula
			where curso_ofertado.id = $idcurso) c
			GROUP by c.iddetallematricula 
			order by c.nombre";

		$identidad = Yii::$app->user->identity;
		if ($identidad->idperfil == 'diracad' || $identidad->idperfil == 'sa') {	

			$provider = new SqlDataProvider([
				'sql' => $sql,
				#'params' => [':status' => 1],
				#'totalCount' => $count,
				'pagination' => [
					'pageSize' => 100,
				],
				#'sort' => [
				#	'attributes' => [
				#		'title',
				#		'view_count',
				#		'created_at',
				#	],
				#],
			]);
        
			$rows = $provider->getModels();
				#echo var_dump($sql); exit;
			#$query = new Query;
		    #$query1 = Yii::$app->db->createCommand($sql);
			#$query->select($sql);
			#$model = $query1->queryAll();
			#echo var_dump($model);exit;
			//////////////////////////////////
			$pdf = new MYPDF();	 
			//echo var_dump($rows, ' ', $ids); exit;
			$img_file = K_PATH_IMAGES.'logo.jpg';

			// set document information
			$pdf->SetCreator(PDF_CREATOR);  
			$pdf->SetAuthor('tics');
			$pdf->SetTitle("Notas publicadas");                
			//$pdf->SetHeaderData(PDF_HEADER_LOGO,PDF_HEADER_LOGO_WIDTH, "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS"., "" .
			//		 "\n" . "Esmeraldas-Ecuador");
			$pdf->setFooterData(array(0,64,0), array(0,64,128));

			$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
			$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
			$pdf->SetFont('helvetica', '', 8);
			$pdf->SetTextColor(0,0,0);
		
				// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
			$pdf->AddPage();

			$fecha = date('d-m-Y');
			//$row = $query->one();
			//$asignatura = $row["NombAsig"];
			//$periodo = $row["DescPerLec"];
			//$carrera = $row["NombCarr"];
			//$periodo = $row["DescPerLec"];
			$html = "<div style='margin-bottom:12px;'>
			Esmeraldas, $fecha 	.	.	.	.	.	Período: $periodo
			 <br><address>
				<b>Asignatura: $asignatura  </b><br>
				Docente: $docente <br>
				Carrera: $carrera .	.	.
				Nivel: $nivel.	.	.
				Paralelo: $paralelo <br>
				</address>  
				</div>";
			//Convert the Html to a pdf document
			$pdf->writeHTML($html, true, false, true, false, '');
		 
			$header = array('Cédula', 'Alumno', 'Nota Final', 'Asistencia', 'Observación'); 
		 
			// print colored table
			$this->ColoredTable($pdf,$header, $rows);
			$pdf->setY(263);
			//if(isset($matricula)){
			//$pdf->write1DBarcode($idcursomodel, 'C39', '', '', '', 5, 0.2, '', 'N');
			$pdf->Cell(0, 0, $idcursomodel, 0, 1);
			

			// reset pointer to the last page
			$pdf->lastPage();
			$file = $iddocente . '_' . $idcursomodel . '.' . 'pdf';
			//Close and output PDF document
			$pdf->Output($file, 'D');	
		}
       return $this->redirect(Yii::$app->request->referrer);
       
    }

	// Colored table
    public function ColoredTable($pdf,$header,$data) {
        // Colors, line width and bold font
        $pdf->SetFillColor(120, 185, 120);
        $pdf->SetTextColor(255);
        $pdf->SetDrawColor(120, 185, 120);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('', 'B', '7');
        // Header
        $w = array(20, 70, 15, 20, 50);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $pdf->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
        }
        $pdf->Ln();
        // Color and font restoration
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('');
        // Data
        $fill = 0;
	
        foreach($data as $row) {
		
			


			#echo var_dump($row['suma']);
			#exit;
			$observacion = $row['Estado'];
			$pdf->Cell($w[0], 6, $row['cedula'], 'LR', 0, 'L', $fill);
			#$pdf->Cell($w[1], 6, number_format($row['idsemestre']), 'LR', 0, 'C', $fill);
			$pdf->Cell($w[1], 6, $row['nombre'], 'LR', 0, 'L', $fill);
			$pdf->Cell($w[2], 6, $row['notafinal'], 'LR', 0, 'C', $fill);
			$pdf->Cell($w[3], 6, ($row['NAT']*10).'%', 'LR', 0, 'C', $fill);
			#$pdf->Cell($w[3], 6, $row['observacion'], 'LR', 0, 'C', $fill);
			$pdf->Cell($w[4], 6, $observacion, 'LR', 0, 'L', $fill);
			$pdf->Ln();
			$fill=!$fill;
			
			//***************************************************************************
			$notasalumno = Notasalumnoasignatura::find()
										->where(['iddetalle'=> $row["iddetallematricula"]])
										->one();
				#$alumno = $matricula?$matricula->idFactura0->cedula:'';
				
				if ($notasalumno) {
					#$alumno = $notasalumno?$notasalumno->CIInfPer:'';
					#echo var_dump($matricula->id); exit;
					#$notasalumno->idPer = $idper;
					#$notasalumno->CIInfPer = $alumno;	
									
					$notasalumno->CalifFinal = $row["notafinal"];
					$notasalumno->asistencia = $row["NAT"]*10;
					$notasalumno->StatusCalif = 3;
					$notasalumno->observacion = $row["Estado"];
					$notasalumno->aprobada = $row["aprobada"];
					$notasalumno->save();
					#if ($notasalumno->CIInfPer == '0803544097') {
					#	echo var_dump($notasalumno->getErrors(), $notasalumno); exit;}
					#echo var_dump($notasalumno->getErrors(), $notasalumno->CalifFinal); exit;
				}
				else {
					#echo var_dump($matricula->id); exit;
					#$alumno = $matricula?$matricula->idFactura0->cedula:'';
					$modelnota = new Notasalumnoasignatura();
					$modelnota->idPer = $row["idper"];
					$modelnota->CIInfPer = $row["cedula"];
					$modelnota->idAsig = $row["idasignatura"];
					$modelnota->iddetalle = $row["iddetallematricula"];
					$modelnota->CalifFinal = $row["notafinal"];
					$modelnota->asistencia = $row["NAT"]*10;
					$modelnota->StatusCalif = 3;
					$modelnota->observacion = $row["Estado"];
					$modelnota->aprobada = $row["aprobada"];
					$modelnota->VRepite = 1;
					$modelnota->registro = date('Y-m-d H:i:s');
					$modelnota->convalidacion = 0;
						//$modelnota->observacion_efa = 'nota homologada';
					$modelnota->save();
					#echo var_dump($modelnota); exit;
					#echo var_dump($modelnota->errors); exit;
					
				}
				
			


			#**************************************************************************


        }
	
        $pdf->Cell(array_sum($w), 0, '', 'T');
    }



    /**
     * Finds the LibretaCalificacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LibretaCalificacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LibretaCalificacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	protected function findCurso($idcurso)
    {
        if (($model = CursoOfertado::findOne($idcurso)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

//************************************************************************************************************************
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
		
	// Set font
	$this->SetFont('helvetica', 'B', 11);
	// Title
	$titulo = "UNIVERSIDAD TÉCNICA LUIS VARGAS TORRES DE ESMERALDAS";
		
	$this->Cell(0, 10, $titulo, 0, false, 'C', 0, '', 0);
	$this->Cell(0, 20, "Vicerectorado Académico                                                             . ", 0, false, 'R', 0, '', 0);
	$this->Cell(0, 30, "Esmeraldas Ecuador                                                                 . ", 0, false, 'R', 0, '', 0);
	
	$imager_file = K_PATH_IMAGES.'logo.jpg';
	$imagel_file = K_PATH_IMAGES.'sello_Ecuador.png';
	$this->Image($imagel_file, 15, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
	$this->Image($imager_file, 175, 5, 20, 20, '', '', '', false, 300, '', false, false, 0);
    }

    // Page footer
	
    public function Footer() {
	// Position at 15 mm from bottom
		$texto = "F. :__________________________";
	$this->SetY(-15);
	// Set font
	$this->SetFont('helvetica', 'I', 8);
	$this->Cell(0, 1, $texto, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	//$pdf->write1DBarcode($this->$iddocente, 'C39', '', '', '', 5, 0.2, '', 'N');
	// Page number
	$this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
	
}
