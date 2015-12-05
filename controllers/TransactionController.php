<?php

namespace app\controllers;

use Yii;
use app\models\Transactions;
use app\models\TransactionsSearch;
use app\models\TransactionDetails;
use app\models\TransactionDetailsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionController implements the CRUD actions for Transactions model.
 */
class TransactionController extends Controller
{
    // properties
    private $details = array();

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Transactions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransactionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * Displays a single Transactions model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new TransactionDetailsSearch();
        $dataProvider = $searchModel->search(['TransactionDetailsSearch'=>['trans_id'=>$id]]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'details' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Transactions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transactions();

        // ambil data post variable
        $post = Yii::$app->request->post();
        // coba load ke model Transactions dan TransactionDetails
        if ($model->load($post) && $this->loadDetails($post)) {
            // save master record
            if ($model->save()) {
                foreach ($this->details as $detail) {
                    // set foreign key
                    $detail->trans_id = $model->id;
                    // save detail record
                    $detail->save();
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'post' => $this->details,
                ]);
            }
            // redirect
            return $this->redirect(['view', 'id' => $model->id]);

        } else {
            return $this->render('create', [
                'model' => $model,
                'post' => $this->details,
            ]);
        }
    }

    private function loadDetails($post)
    {
        // ambil data
        if (isset($post['Details-item_id'])) {
            $item = $post['Details-item_id'];
            $quantity = $post['Details-quantity'];
            $remarks = $post['Details-remarks'];
            // buat object-nya
            for ($i=0; $i<count($item); $i++) { 
                $detail = new TransactionDetails();
                $detail->item_id = $item[$i];
                $detail->quantity = $quantity[$i];
                $detail->remarks = $remarks[$i];
                $this->details[] = $detail;
                unset($detail);
            }
        }
        // return status
        return count($this->details) > 0;
    }

    /**
     * Updates an existing Transactions model.
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
     * Deletes an existing Transactions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transactions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transactions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
