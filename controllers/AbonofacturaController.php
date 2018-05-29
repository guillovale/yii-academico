<?php

namespace app\controllers;

use Yii;
use app\models\AbonoFactura;
use app\models\AbonoFacturaSearch;
use app\models\FacturaSearch;
use app\models\Factura;
use app\models\DetalleMatricula;
use app\models\Matricula;
use app\models\Informacionpersonal;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\filters\AccessControl;
/**
 * AbonoFacturaController implements the CRUD actions for AbonoFactura model.
 */
class AbonofacturaController extends Controller
{

	public $alumno = '';
    public function behaviors()
    {
        return [


		'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete','update', 'create', 'abonar'],
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
     * Lists all AbonoFactura models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AbonoFacturaSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->setPagination(false);
		#echo var_dump(count($dataProvider->setPagination(false))); exit;	
		return $this->render('index', [
	        'searchModel' => $searchModel,
	        'dataProvider' => $dataProvider,
	    ]);
    }

    /**
     * Displays a single AbonoFactura model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		$model = $this->findModel($id);
		$this->view->params['factura'] = $model?$model->idfactura:0;
	    return $this->render('view', [
	        'model' => $this->findModel($id),
	    ]);
    }

	
    /**
     * Creates a new AbonoFactura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idfactura)
    {
		$modelfactura = Factura::findOne(['id' => $idfactura]);
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'fin' || $usuario->idperfil == 'sa') && $modelfactura) {
		
			$model = new AbonoFactura();
			$model->idfactura = $modelfactura->id;
			$model->fecha = date("Y-m-d H:i:s");
			$model->usuario = $usuario->LoginUsu;
			$suma_abono = $modelfactura?$modelfactura->sumaAbono():0;
			$total = $modelfactura?$modelfactura->total:0;#(float)$suma_abono;
			$saldo = $total - $suma_abono;
			#echo var_dump($modelabono, ' ', $total); exit;
			$this->view->params['alumno'] = $modelfactura->nombreAlumno;
			$this->view->params['factura'] = $modelfactura->id;
			$this->view->params['cedula'] = $modelfactura->cedula;
			$this->view->params['suma'] = $suma_abono;
			$this->view->params['total'] = $total;
		
			if (isset($_POST['AbonoFactura']['valor'])) {
				$valor = (float) $_POST['AbonoFactura']['valor'];
				if (round($valor,2) > round($saldo, 2))
					return $this->redirect(Yii::$app->request->referrer);
			}
		
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				
			    return $this->redirect(['view', 'id' => $model->id, 
				]);
			} 
			else {
			    return $this->render('create', [
				'model' => $model,
			    ]);
			}
		}
		else {
			return $this->redirect(Yii::$app->request->referrer);
			#return $this->redirect(['abonofactura/index']);
		}	
	//}
	//else {
	//		return $this->redirect(['factura/index']);
	//	}
    }

    /**
     * Updates an existing AbonoFactura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'fin' || $usuario->idperfil == 'sa') && $model) {
	
	       if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
		    return $this->redirect(['view', 'id' => $model->id]);
		} 
		else {
		    return $this->render('update', [
		        'model' => $model,
		    ]);
		}
	}
	else {
		return $this->redirect(['abonofactura/index']);
	}
    }

    /**
     * Deletes an existing AbonoFactura model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		$usuario = Yii::$app->user->identity;
		if ( ($usuario->idperfil == 'fin' || $usuario->idperfil == 'sa') && $model) {
			if ($model->usuario == $usuario->LoginUsu) {
				$model->delete();
			}
		}
		//return $this->redirect(Yii::app()->request->urlReferrer);
        	//return $this->redirect(['factura/index']);
		return $this->redirect(['abonofactura/index']);
    }

	public function actionAbonar()
    {

		$searchModel = new FacturaSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$alumno = '';#Informacionpersonal::find()->where(['CIInfPer'=>$cedula])->one();
		$suma = 0;#$this->sumaAbono($idfactura)?$this->sumaAbono($idfactura):0;
		$saldo = 0;#(float)($total - $suma);
		$this->view->params['suma'] = $suma;
			//AbonoFactura::find()->where(['idfactura'=>$idfactura])->sum('valor');
		if ($saldo <= 0)
			#$this->cambiarEstado($idfactura);
		if ($alumno) {
			$this->view->params['alumno'] = $alumno->ApellInfPer . ' ' . $alumno->ApellMatInfPer . ' ' . $alumno->NombInfPer;
			$this->view->params['cedula'] = $cedula;
			$this->view->params['total'] = $total;
			$this->view->params['factura'] = $idfactura;
		}
		
		
		return $this->render('abonar', [
	        'searchModel' => $searchModel,
	        'dataProvider' => $dataProvider,
	    ]);
    }

    /**
     * Finds the AbonoFactura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AbonoFactura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AbonoFactura::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	protected function sumaAbono($idfactura)
    {
	$query = (new \yii\db\Query())->from('abono_factura');
	$sum = $query->where(['idfactura'=>$idfactura])->sum('valor');
	return $sum;
	//echo var_dump($sum);
	//exit;
    }

	protected function cambiarEstado($idfactura)
    {	
		$detalle_matricula = DetalleMatricula::find()
							->where(['idfactura'=>$idfactura])->all();
				if (!empty($detalle_matricula)) {
					foreach($detalle_matricula as $detalle){
						//if ($detalle->idasig == 'CC01') {echo var_dump($detalle->estado); exit;}
						if ($detalle->estado !== 1) {
								$detalle->estado = 1;
								$detalle->save();
								//echo var_dump($detalle->getErrors()); exit;
						}
														
						$matricula = Matricula::find()
									->where(['idMatricula'=>$detalle->idmatricula])
									->one();
						if (!empty($matricula)) {
							if ($matricula->statusMatricula != 'APROBADA'){
								$matricula->statusMatricula = 'APROBADA';
								$matricula->save();
								//echo var_dump($matricula->getErrors()); exit;
							}
							
						}
					}
			
				}
	}

}
