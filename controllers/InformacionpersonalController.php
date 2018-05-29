<?php

namespace app\controllers;

use Yii;
use app\models\Informacionpersonal;
use app\models\InformacionpersonalSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * InformacionpersonalController implements the CRUD actions for Informacionpersonal model.
 */
class InformacionpersonalController extends Controller
{
    public function behaviors()
    {
        return [

		'access' => [
                'class' => AccessControl::className(),
                
                'rules' => [
			[
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
     * Lists all Informacionpersonal models.
     * @return mixed
     */

    public function actionIndex()
    {
        $searchModel = new InformacionpersonalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Informacionpersonal model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Informacionpersonal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$usuario = Yii::$app->user->identity;
        if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa'){
			$model = new Informacionpersonal();
			if ($model->load(Yii::$app->request->post())) {
				#echo var_dump($model->CIInfPer);exit;
				$pass = md5($model->CIInfPer);
				$model->TipoDocInfPer = 'C';
				$model->codigo_dactilar = $pass;
				$model->cedula_pasaporte = $model->CIInfPer;
				$model->TipoInfPer = 'E';
				$model->statusper = 1;
				$model->ultima_actualizacion = date('Y-m-d H:i:s');
				$model->save();
				#return $this->redirect(['view', 'id' => $model->CIInfPer]);
				return $this->redirect(['index']);
			} else {
				//echo var_dump($model->errors); exit;
				return $this->render('create', [
				    'model' => $model,
				]);
			}
		}
		else {
			return $this->redirect(\Yii::$app->request->getReferrer());
		}
    }

    /**
     * Updates an existing Informacionpersonal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$usuario = Yii::$app->user->identity;
        if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa'){

		    $model = $this->findModel($id);

		    if ($model->load(Yii::$app->request->post()) && $model->save()) {
		        //return $this->redirect(['view', 'id' => $model->CIInfPer]);
				return $this->redirect(['index']);
		    } else {
		        return $this->render('update', [
		            'model' => $model,
		        ]);
		    }
		}
		else {
			return $this->redirect(\Yii::$app->request->getReferrer());
		}

    }

    /**
     * Deletes an existing Informacionpersonal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	//if (Yii::$app->user->identity->homologar == 1){
	//	$this->findModel($id)->delete();
	//}

        return $this->redirect(['index']);
    }

	public function actionResetearclave($id)
    {
		$usuario = Yii::$app->user->identity;
        if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa'){

		    $model = $this->findModel($id);

		    if ($model) {
				$pass = md5($model->CIInfPer);
				$model->codigo_dactilar = $pass;
				$model->ultima_actualizacion = date('Y-m-d H:i:s');
				$model->save();
			}
		    
			#return $this->redirect(['index']);
		    
		}
		return $this->redirect(\Yii::$app->request->getReferrer());
		

    }


    /**
     * Finds the Informacionpersonal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Informacionpersonal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Informacionpersonal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
