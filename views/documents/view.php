<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $document app\models\Documents */
/* @var $history app\models\Files[] */
/* @var $comments app\models\Comments[] */


$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="wrapper">

    <div id="main" class="clearfix">

        <div class="col-lg-12" style="border-bottom: 1px solid grey; padding-bottom: 20px;">
            <h1 > <?=$document->title?></h1>
            <h3 style="color: dimgrey"><?=$document->user->username?></h3>
        </div>
        <!--HISTORY-->
        <div class="col-md-5 history">
            <h1> История</h1>

            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Название документа</th>
                    <th>Дата загрузки</th>
                    <th>Скачать</th>
                </tr>
                </thead>
                <tbody>
                <?
                foreach ($history as $row){
                    if(\app\models\User::isAdmin($row->author_id))
                        echo "<tr style='background-color: blanchedalmond;'>";
                    else
                        echo "<tr>";
                        ?>
                        <td><?=$row->tag?></td>
                        <td><?=$row->created_at?></td>
                        <td class="text-center"><a href="<?=\yii\helpers\Url::toRoute(['documents/download-file','file'=>$row->filename])?>"><span class="glyphicon glyphicon-download" aria-hidden="true"></span></a></td>
                    </tr>

                <?}
                ?>

                </tbody>
            </table>
            <?if(\app\models\Settings::find()->where(['name'=>\app\models\Settings::WORK])->one()->value == 1){?><a href="<?=\yii\helpers\Url::toRoute(['documents/upload','id'=>$document->id]);?>" class="btn btn-primary">Загрузить новую версию</a><?}?>
            


        </div>

        <!--REQUIREMENTS-->
        <div class="col-md-7 requirements">
            <h1>Оформление</h1>
            <p><?=$req_text[value]?></p>
            <h4>Файл с требованиями: <a href="<?=\yii\helpers\Url::toRoute(['site/download-file','file'=>$req_file[value]])?>">Скачать</a></h4>

        </div>

    </div>
    <div class="row">
        <div class="col-md-12 details" style="margin: 35px;border-top: 1px solid #5C6065;;border-bottom: 1px solid #5C6065;;padding: 30px 0px;">
            <!--STATUS-->
            <h3 class="text-center">Статус
                    <? switch ($document->status){
                        case \app\models\Documents::STATUS_NEW: echo"<span class=\"label label-primary\">Новый";break;
                        case \app\models\Documents::STATUS_WARNING: echo"<span class=\"label label-danger\">Нуждается в исправлении";break;
                        case \app\models\Documents::STATUS_READY: echo"<span class=\"label label-success\">Утвержден";break;
                    }?>
                </span></h3>
            <? if(\app\models\User::isAdmin(Yii::$app->user->id)){?>
            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                <div class="btn-group" role="group">
                    <?= Html::a('Новый', ['documents/update-status', 'id' => $document->id,'status'=>\app\models\Documents::STATUS_NEW], ['class' => 'btn btn-info']) ?>
                </div>
                <div class="btn-group" role="group">
                    <?= Html::a('Нуждается в исправлении', ['documents/update-status', 'id' => $document->id,'status'=>\app\models\Documents::STATUS_WARNING], ['class' => 'btn btn-danger']) ?>

                </div>
                <div class="btn-group" role="group">
                    <?= Html::a('Утвержден', ['documents/update-status', 'id' => $document->id,'status'=>\app\models\Documents::STATUS_READY], ['class' => 'btn btn-success']) ?>

                </div>
            </div>
            <?}?>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 comments">
            <!--COMMENTS-->
            <h1>Обсуждение</h1>

            <?
            foreach ($comments as $msg){?>
                <div class="media">
                    <div class="media-left">
                        <img src="<?= \app\models\User::isAdmin($msg->author_id) ? "images/admin.png" : "images/user.png"?>" style="max-width: 60px">
                    </div>
                <div class="media-body">
                    <h4 class="media-heading"><?=$msg->author->username?></h4>
                    <p><?=$msg->message?></p>
                </div>
            </div>
            <?}?>
            <br>

            <!--ADD NEW COMMENT-->
            <?if(\app\models\Settings::find()->where(['name'=>\app\models\Settings::WORK])->one()->value == 1){?>
            <form role="form" action="<?=\yii\helpers\Url::toRoute('documents/add-comment')?>" method="post">
                <div class="form-group">
                    <label for="message">Новый комментарий:</label>
                    <textarea class="form-control" rows="5" name="message"></textarea>
                    <input type="hidden" name="document_id" value="<?=$document->id?>">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                </div>
                <button class="btn btn-default" type="submit" >Отправить</button>
            </form>
            <?}?>

        </div>
    </div>

</div><!-- #wrapper -->
