<?php

namespace app\controllers;

use Yii;
use app\models\MallaCarrera;
use app\models\MallaCarreraSearch;
use app\models\Carrera;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * MallacarreraController implements the CRUD actions for MallaCarrera model.
 */
class MallacarreraController extends Controller
{
    public function behaviors()
    {
        return [
			'access' => [
                'class' => AccessControl::className(),
                'only' => ['delete','update', 'create', 'index'],
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
     * Lists all MallaCarrera models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MallaCarreraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MallaCarrera model.
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
     * Creates a new MallaCarrera model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MallaCarrera();
		$this->view->params['carrera'] = $this->getCarrera();
		$usuario = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
				$model->save();
			}
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MallaCarrera model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$this->view->params['carrera'] = $this->getCarrera();
		$usuario = Yii::$app->user->identity;
        if ($model->load(Yii::$app->request->post()) ) {
			if ($usuario->idperfil == 'sa' || $usuario->idperfil == 'diracad') {
				$model->save();
			}
			return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MallaCarrera model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
		
        return $this->redirect(['index']);
    }


	public function getCarrera() {
		$carreras = [];
		if (isset(Yii::$app->user->identity->idcarr)) {
			$carreras_user = explode("'", Yii::$app->user->identity->idcarr);
			if (in_array('%', $carreras_user)) {
				$carreras = ArrayHelper::map(Carrera::find()
									#->where(['StatusCarr' => 1])
									->orderBy(['nombcarr'=>SORT_ASC])
									->all(), 'idCarr', 'NombCarr');
			}
		
			else {
				$carreras = ArrayHelper::map(Carrera::find()->where(['in', 'idcarr', $carreras_user])
														->orderBy(['nombcarr'=>SORT_DESC])
														->all(), 'idCarr', 'NombCarr');
			}
		
		}
		
		return $carreras;
	}

    /**
     * Finds the MallaCarrera model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MallaCarrera the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MallaCarrera::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
