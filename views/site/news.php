<?php

/* @var $this yii\web\View */
use yii\widgets\Pjax;

/* @var $news app\models\News[] */
/* @var $model app\models\Documents */
$this->title = 'Документы';
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?
            if (\app\models\User::isAdmin(Yii::$app->user->id)) {
                ?><h1>Последние обновления</h1>
                <?
                if($news == null){?>
                    <p>Нет обновлений.</p>

                <?}
                else {?>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <td>Тип</td>
                                <td>Сообщение</td>
                                <td>Автор</td>
                                <td>Дата</td>
                                <td><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span></td>
                                <td><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            foreach ($news as $new) {?>
                                <tr>
                                    <td><?
                                        switch ($new->type) {
                                            case \app\models\News::NEW_VERSION:
                                                echo "<span class=\"label label-primary\">Версия</span>";
                                                break;
                                            case \app\models\News::NEW_COMMENT:
                                                echo "<span class=\"label label-warning\">Комментарий</span>";
                                                break;
                                            case \app\models\News::NEW_DOC:
                                                echo "<span class=\"label label-success\">Документ</span>";
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td><?= $new->message ?></td>
                                    <td><?= $new->author->username ?></td>
                                    <td><?= $new->created_at ?></td>
                                    <td>
                                        <a href="<?=$new->link?>"><span
                                                class="glyphicon glyphicon glyphicon-log-in" aria-hidden="true"></span></a>
                                    </td>
                                    <td>
                                        <a href="<?=\yii\helpers\Url::toRoute(['site/delete-new','id'=>$new->id])?>"><span
                                                class="glyphicon glyphicon glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a>
                                    </td>
                                </tr>
                                <?
                            }
                    }?>
                            </tbody>
                        </table>



                <?}
                ?>

        </div>
    </div>
</div>

<?$this->registerJsFile('@web/js/main.js',['position' => yii\web\View::POS_END,'depends' => [\yii\web\JqueryAsset::className()]]);?>

