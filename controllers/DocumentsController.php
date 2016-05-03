<?php

namespace app\controllers;

use app\models\base\Files;
use app\models\Comments;
use app\models\DocumentForm;
use app\models\News;
use app\models\Settings;
use app\models\UploadFileForm;
use Yii;
use app\models\Documents;
use app\models\DocumentsSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Documents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documents model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $history = Files::find()->where(['document_id'=>$id])->all();
        $document = Documents::find()->where(['id'=>$id])->one();
        $comments = Comments::find()->where(['document_id'=>$id])->all();
        $req_text = Settings::find()->where(['name'=>Settings::REQ_TEXT])->select('value')->asArray(true)->one();
        $req_file = Settings::find()->where(['name'=>Settings::REQ_FILE])->select('value')->asArray(true)->one();

        return $this->render('view', ['document' => $document,'history'=>$history,'comments'=>$comments, 'req_text'=>$req_text,'req_file'=>$req_file]);
    }

    /**
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DocumentForm();

        if ($model->load(Yii::$app->request->post())) {
            if(Documents::find()->where(['title'=>$model->title,'user_id'=>Yii::$app->user->id])->count() == 0) {
                $document = new Documents();
                $document->title = $model->title;
                $document->status = Documents::STATUS_NEW;
                $document->user_id = Yii::$app->user->id;

                $doc_file = UploadedFile::getInstance($model, 'file');
                $ext = $doc_file->extension;
                $filename = Yii::$app->security->generateRandomString() . ".{$ext}";
                $path = 'uploads/' . $filename;

                if ($document->save()) {
                    if ($doc_file !== false) {
                        $doc_file->saveAs($path);
                        $file = new Files();
                        $file->document_id = $document->id;
                        $file->filename = $filename;
                        $file->tag = "Начальная версия";
                        $file->save();

                    }
                    $new = new News();
                    $new->type = News::NEW_DOC;
                    $new->message = "Создан новый документ : ".$document->title;
                    $new->link = Url::toRoute(['documents/view', 'id'=>$document->id]);
                    $new->author_id = Yii::$app->user->id;
                    $new->save();
                    return $this->redirect(['view', 'id' => $document->id]);
                }
            }
        }
        return $this->render('create', [
            'model'=>$model,
        ]);


    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionDownloadFile($file)
    {
        // отдаем файл
        $path ='uploads/'.$file;
        $file = \app\models\Files::find()->where(['filename'=>$file])->one();
        $name = Documents::findOne(['id'=>$file->document_id])->title;
        $ext = explode('.',$file->filename)[1];

        $name = $name.'.'.$ext;
        if(file_exists($path)){
            return \Yii::$app->response->sendFile($path,$name);
        }else{
            throw new NotFoundHttpException('Такого файла не существует ');
        }
    }

    public function actionUpdateStatus($id,$status){
        $doc = Documents::findOne($id);
        $doc->status = $status;
        if($doc->update())
            return $this->redirect(Url::toRoute(['view','id'=>$doc->id]));
    }

    public function actionUpload($id)
    {
        $model = new UploadFileForm();
        $model->document_id = $id;
        $history =  $history = Files::find()->where(['document_id'=>$id])->all();
        if (Yii::$app->request->isPost) {
            $model->docFile = UploadedFile::getInstance($model, 'docFile');
            $model->tag = Yii::$app->request->post('UploadFileForm')['tag'];
            if ($model->upload()) {
                // file is uploaded successfully
                $file = new \app\models\Files();
                $file->tag = $model->tag;
                $file->filename = $model->docFile;
                $file->document_id = $model->document_id;
                $file->author_id = Yii::$app->user->id;
                if ($file->save()) {
                    $new = new News();
                    $new->type = News::NEW_VERSION;
                    $new->message = "Добавлена новая версия для документа : ".Documents::findOne($model->document_id)->title;
                    $new->link = Url::toRoute(['documents/view', 'id'=> $model->document_id]);
                    $new->author_id = Yii::$app->user->id;
                    $new->save();
                    return $this->redirect(Url::toRoute(['view', 'id' => $model->document_id]));
                }
            }
        }

        return $this->render('fileUpload', ['model' => $model, 'history'=>$history]);
    }


    public function actionAddComment(){
        $comment = new Comments();

        if(Yii::$app->request->post()){
            $comment->document_id = Yii::$app->request->post('document_id');
            $comment->message = Yii::$app->request->post('message');
            $comment->author_id=Yii::$app->user->id;
            if($comment->save()) {
                $new = new News();
                $new->type = News::NEW_COMMENT;
                $new->message = "Добавлена комментарий для документа : ".Documents::findOne($comment-->document_id)->title;
                $new->link = Url::toRoute(['documents/view', 'id'=> $comment->document_id]);
                $new->author_id = Yii::$app->user->id;
                $new->save();

                Yii::$app->session->setFlash('success', 'Сообщение добавлено');

            }
            else
                Yii::$app->session->setFlash('error', 'При добавлении сообщения произошла ошибка');


            $this->redirect(['view', 'id' => $comment->document_id]);

        }
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
