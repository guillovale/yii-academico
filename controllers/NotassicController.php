<?php

namespace app\controllers;

use Yii;
use app\models\NotasSic;
use app\models\NotasSicSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\Notasalumnoasignatura;
use app\models\Periodolectivo;
use app\models\Matricula;

/**
 * NotasSicController implements the CRUD actions for NotasSic model.
 */
class NotassicController extends Controller
{

	public $layout = "/main";

    public function behaviors()
    {
        return [

		'access' => [
                'class' => AccessControl::className(),
                //'only' => ['index'],
                'rules' => [/*
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],*/
			[
                        //'actions' => ['create'],
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
     * Lists all NotasSic models.
     * @return mixed
     */
    public function actionIndex()
    {
	$this->layout = "/column2";
        $searchModel = new NotasSicSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NotasSic model.
     * @param integer $codigo
     * @param string $cedula
     * @return mixed
     */
    public function actionView($codigo, $cedula)
    {
		/*
        return $this->render('view', [
            'model' => $this->findModel($codigo, $cedula),
        ]);
		*/
		return $this->redirect(['index']);
    }

    /**
     * Creates a new NotasSic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		/*
        $model = new NotasSic();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'codigo' => $model->codigo, 'cedula' => $model->cedula]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
		*/
		return $this->redirect(['index']);
    }

    /**
     * Updates an existing NotasSic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $codigo
     * @param string $cedula
     * @return mixed
     */
    public function actionUpdate($codigo, $cedula)
    {
		/*
        $model = $this->findModel($codigo, $cedula);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'codigo' => $model->codigo, 'cedula' => $model->cedula]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
		*/
		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing NotasSic model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $codigo
     * @param string $cedula
     * @return mixed
     */
    public function actionDelete($codigo, $cedula)
    {
		
        // $this->findModel($codigo, $cedula)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the NotasSic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $codigo
     * @param string $cedula
     * @return NotasSic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($codigo, $cedula, $idcarrera, $nivel, $estado, $fecha)
    {
        if (($model = NotasSic::findOne(['codigo' => $codigo, 'cedula' => $cedula, 
										'idcarrera' => $idcarrera, 'nivel' => $nivel, 'estado' => $estado, 
										'fecha' => $fecha])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('la página no existe....');
        }
    }


	public function actionSubirnota($cedula, $idcarrera, $nivel, $codigo, $calificacion, $estado, $fecha)
	{	
		$periodo = Periodolectivo::find()
			->where(['>=','fechinicioperlec',$fecha])
			->one();
		$usuario = Yii::$app->user->identity;
		$model = $this->findModel($codigo, $cedula, $idcarrera, $nivel,$estado, $fecha);
		
		
		if ($usuario && $periodo && $model) {
			if ( $usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' ) {


				$matricula = Matricula::find()
					->where("idPer = $periodo->idper and CIInfPer = '.$cedula.' and idCarr = $idcarrera and idsemestre = $nivel")
					->one();
				
				if ($matricula) {
					$codmatricula = $matricula->idMatricula;
				}
				else {
					
					$carrera = (strlen($idcarrera) >= 3) ? $idcarrera : ((strlen($idcarrera) == 2) ? 
						('0'.$idcarrera) : ('00'.$idcarrera));

					$matricula = Matricula::find()
							->where("idCarr = $idcarrera and idPer = 
								$periodo->idper and idsemestre =$nivel")
							->select('max(cast(idMatricula as UNSIGNED))')
							->scalar();

					if ($matricula) {
						$matriculaid = $this->getIdmatricula($matricula);
						if (!$matriculaid) {
						
							Yii::$app->session->setFlash('error', 'No grabó cambios !!');
							return $this->redirect(Yii::$app->request->referrer);//$this->goBack();
						}

					}

					else {
						$matriculaid = $periodo->DescPerLec.$carrera.'0'.$nivel.'001';
					}
				
					//completar matrícula
					$modelmat = new Matricula;
					$modelmat->idMatricula = $matriculaid;
					$modelmat->CIInfPer = $cedula;
					$modelmat->idCarr = $carrera;
					$modelmat->idPer = $periodo->idper;
					$modelmat->idsemestre = $nivel;
					$modelmat->idanio = 0;
					$modelmat->FechaMatricula = date('Y-m-d H:i:s');
					$modelmat->statusMatricula = 'APROBADA';
					$modelmat->observMatricula = 'sic_a_siad';
					$modelmat->Usu_registra = $usuario->LoginUsu;
					$codmatricula = $matriculaid;
				
				
					if (!$modelmat->save()){	
					
						Yii::$app->session->setFlash('error', 'No grabó cambios !!');
						return $this->redirect(Yii::$app->request->referrer);//$this->goBack();
					}
				}

				//completar notas
				$model=new Notasalumnoasignatura;
				$model->CIInfPer = $cedula;
				$model->idPer = $periodo->idper;
				$model->idMatricula = $codmatricula;
				$model->idAsig = $codigo;
				$model->CalifFinal = $calificacion;
				$model->StatusCalif = 3;
				$model->VRepite = 1;
				$model->observacion = $estado;
				$model->aprobada = 1;
				$model->asistencia = 80;
				$model->registro = date('Y-m-d H:i:s');
				$model->usu_pregistro = $usuario->LoginUsu;
			
				$model->save();
						
			}
		}
		

        return $this->redirect(Yii::$app->request->referrer);//$this->goBack();
		
	}


	public function getIdmatricula($matricula)
	{
		$hay_matricula = true;
		$cont = 0;

		while ($hay_matricula) {
			$cont++;
		
			$matriculaid = intval(substr($matricula,13)) + $cont;
			$matriculaid = (strlen($matriculaid) >= 3) ? $matriculaid : ((strlen($matriculaid) == 2) ? 
				('0'.$matriculaid) : ('00'.$matriculaid));
			$matriculaid = substr($matricula,0,13).$matriculaid;
			$hay_matricula = Matricula::find()->where(['idMatricula' => $matriculaid])->one();
			if ($cont >= 1000) break;

		}
		if ($hay_matricula) {
			
			Yii::$app->session->setFlash('error', 'No grabó cambios !!');
			return null;
		}
		else return $matriculaid;

	}

}
