<?php

namespace setrun\sys\controllers\backend;

use Yii;
use yii\web\NotFoundHttpException;
use setrun\sys\services\DomainService;
use setrun\sys\entities\manage\Domain;
use setrun\sys\forms\backend\DomainForm;
use setrun\sys\forms\backend\search\DomainSearchForm;
use setrun\sys\components\controllers\BackController;

/**
 * DomainController implements the CRUD actions for Domain model.
 */
class DomainController extends BackController
{
    /**
     * @var DomainService
     */
    protected $service;

    /**
     * DomainController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param DomainService $service
     * @param array $config
     */
    public function __construct($id, $module, DomainService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all Domain models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new DomainSearchForm();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Domain model.
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
     * Creates a new Domain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new DomainForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $domain = $this->service->create($form);
                return $this->redirect(['view', 'id' => $domain->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Edites an existing Domain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $domain = $this->findModel($id);
        $form   = new DomainForm($domain);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($form->id, $form);
                return $this->redirect(['view', 'id' => $form->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('edit', [
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing Domain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Domain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Domain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Domain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}