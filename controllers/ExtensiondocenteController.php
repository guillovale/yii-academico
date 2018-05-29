<?php

namespace app\controllers;

use Yii;
use app\models\ExtensionDocente;
use app\models\ExtensionDocenteSearch;
use app\models\Usuario;
use app\models\Asignatura;
use app\models\Carrera;
use app\models\CursoOfertado;
use app\models\Periodolectivo;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * ExtensiondocenteController implements the CRUD actions for ExtensionDocente model.
 */
class ExtensiondocenteController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'vercupos', 'vernotas', 
							'index', 'enviarMail', 'veraprobados', 'reporte_egresados'],
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
     * Lists all ExtensionDocente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtensionDocenteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExtensionDocente model.
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
     * Creates a new ExtensionDocente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExtensionDocente();
		$hoy = date('Y-m-d');
		$model->fecha_inicio = $hoy;
		$model->fecha_fin = $hoy;
		$usuario = Yii::$app->user->identity;
		
		if ($usuario) {
			$dataCarrera=ArrayHelper::map(Carrera::find()
					->Where(['StatusCarr' => 1,])
					->orderBy(['nombcarr'=>SORT_ASC])
					->all(), 'idCarr', 'NombCarr');

			$this->view->params['carrera'] = $dataCarrera?$dataCarrera:null;	


		    if ( $model->load(Yii::$app->request->post()) ) {
				if ( $usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa'  ) {
					$model->save();
				}
				return $this->redirect(['index']);
		        #return $this->redirect(['view', 'id' => $model->id]);
		    } else {
		        return $this->render('create', [
		            'model' => $model,
		        ]);
		    }
		}
		return $this->redirect('index');
    }

    /**
     * Updates an existing ExtensionDocente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExtensionDocente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	public function actionListasignatura($nivel)	{
		$porciones = explode(";", $nivel);
		if ($porciones[0]) $nivel = $porciones[0];
		if ($porciones[1]) $idcarr = $porciones[1];
		$periodo = Periodolectivo::find()
						->where(['StatusPerLec'=>1])
						->one();
		$fecha = date('Y-m-d');
		#$malla = MallaCarrera::find()
         #       	->where(['id' => $idMc])
          #      	->one();

		#if ($malla) {
		if ($periodo) {
 
			$cursos = CursoOfertado::find()
				->joinWith(['detallemalla'])
				->joinWith(['detallemalla.malla'])
				->joinWith(['detallemalla.asignatura'])
				->where(['curso_ofertado.idper'=> $periodo->idper, 'idcarrera' => $idcarr, 'nivel' => $nivel])
				->andwhere(['>=', 'curso_ofertado.fecha_fin', $fecha])
				
				//->groupBy(['anio_habilitacion', 'idsemestre'])
				->orderBy('curso_ofertado.paralelo ASC, asignatura.NombAsig ASC')
				->all();
			if ($cursos){
				echo "<option>-</option>";
				foreach($cursos as $curso){
					#$nombreasig = Asignatura::find()
					#		->where(['idAsig' => $asignaturamalla->detallemalla->idasignatura])
					#		->one();
					#$asignatura = $nombreasig?$nombreasig->NombAsig:'';


					echo "<option value='".$curso->id."'>"
						. $curso->paralelo . '--('
						.$curso->detallemalla->idasignatura.'-'
						.$curso->detallemalla->asignatura->NombAsig . ')' ."</option>";
				}
			}
		}
		//echo var_dump($idnivel); exit;
 
		
		else{
			echo "<option>-</option>";
		}
 
	}


    /**
     * Finds the ExtensionDocente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtensionDocente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExtensionDocente::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
