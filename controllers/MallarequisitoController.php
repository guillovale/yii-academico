<?php

namespace app\controllers;

use Yii;
use app\models\MallaRequisito;
use app\models\MallaRequisitoSearch;
use app\models\Carrera;
use app\models\MallaCarrera;
use app\models\DetalleMalla;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * MallarequisitoController implements the CRUD actions for MallaRequisito model.
 */
class MallarequisitoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'delete','update', 'create', 'crearcurso'],
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
     * Lists all MallaRequisito models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MallaRequisitoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MallaRequisito model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MallaRequisito model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {		
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			$this->view->params['carreras'] = [];
				
			if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			}
		
			else {
				$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
												->orderBy(['nombcarr'=>SORT_DESC])
												->all(), 'idCarr', 'NombCarr');
			}
		
			$this->view->params['carreras'] = $carreras;
			
			
			$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		

        	$model = new MallaRequisito();

        	if ($model->load(Yii::$app->request->post()) && $model->save()) {
            	return $this->redirect(['index']);
       		}
			else {
           		 return $this->render('create', [
            		    'model' => $model,
           		 ]);
			}
        }

		return $this->redirect(['index']);
    }

    /**
     * Updates an existing MallaRequisito model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
		if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {	
			$model = $this->findModel($id);	
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			$this->view->params['carreras'] = [];
				
			if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			}
		
			else {
				$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
												->orderBy(['nombcarr'=>SORT_DESC])
												->all(), 'idCarr', 'NombCarr');
			}
		
			$this->view->params['carreras'] = $carreras;
			
			
			$this->view->params['nivel'] = [0,1,2,3,4,5,6,7,8,9,10];
		

        	$model = new MallaRequisito();

        	if ($model->load(Yii::$app->request->post()) && $model->save()) {
            	return $this->redirect(['index']);
       		}
			else {
           		 return $this->render('update', [
            		    'model' => $model,
           		 ]);
			}
        }

		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing MallaRequisito model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	public function actionListamalla($id)	{

		$idcarr = ($id?$id:'');
		
		//echo "<option>ok</option>";
		$mallas = MallaCarrera::find()
				->where(['idcarrera' => $idcarr])
				//->andwhere(['idSemestre' => $idnivel])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion'])
				->orderBy('detalle DESC')
				->all();
			//echo var_dump($idcarr); exit;
			if ($mallas){
				echo "<option>-</option>";
				foreach($mallas as $malla){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$malla->id."'>"
						.$malla->detalle."</option>";
				}
			}
	}

	public function actionSetearnivel()	{
		$niveles = array('1'=>'1','2'=>'2', '3'=>'3','4'=>'4', '5'=>'5','6'=>'6', '7'=>'7','8'=>'8', '9'=>'9','10'=>'10');
		echo "<option>-</option>";
		foreach($niveles as $nivel){
					//echo "<option value='".$malla->anio_habilitacion"'>".$malla->anio_habilitacion."</option>";
					echo "<option value='".$nivel."'>"
						.$nivel."</option>";
				}
		
	}
	public function actionListasignatura($nivel)	{

		$idcarr = '';
		$malla = '';
		$idmalla = 0;
		$str = str_replace("'", "", $nivel);
		$porciones = explode(";", $str);
		
		// echo var_dump($porciones); exit;
		//echo $porciones[1]; // porción1
		//echo "--";
		//echo $porciones[2]; // porción2
		//exit;
		//$pos = strpos($nivel, ';'); // $pos = 7, no 0
		//$pos1 = strpos($nivel, ';', 1); // $pos = 7, no 0
		
		if ($porciones[0]) $idnivel = $str[0];
		//if ($porciones[1]) $idcarr = $porciones[1];
		if ($porciones[1]) $idmalla = $porciones[1];

		$asignmallas = DetalleMalla::find()
				->where(['idmalla' => $idmalla, 'nivel'=> $idnivel, 'estado'=> 1])
				//->joinWith(['asignatura'])
				//->groupBy(['anio_habilitacion', 'idsemestre'])
				//->orderBy('asignatura.NombAsig ASC')
				->all();
		if ($asignmallas){
			echo "<option>-</option>";
			foreach($asignmallas as $asignaturamalla){
				//$nombreasig = Asignatura::find()
				//		->where(['idAsig' => $asignaturamalla->asignatura->idasignatura])
				//		->one();
				//	$asignatura = $nombreasig?$nombreasig->NombAsig:'';
				//$asignatura = $asignaturamalla->asignatura->IdAsig . '-' . $asignaturamalla->asignatura->NombAsig;


					echo "<option value='".$asignaturamalla->id."'>"
						.$asignaturamalla->asignatura->IdAsig.'-'.$asignaturamalla->asignatura->NombAsig."</option>";
			}
		}
		//}
		
 
		
		else{
			echo "<option>-</option>";
			echo var_dump($idnivel, '_', $idmalla); exit;
		}
	}

    /**
     * Finds the MallaRequisito model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MallaRequisito the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MallaRequisito::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
