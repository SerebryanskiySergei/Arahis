<?php

/* @var $this yii\web\View */
/* @var $documents app\models\Documents[] */
/* @var $model app\models\Documents */
$this->title = 'Документы';
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?
            if (\app\models\User::isAdmin(Yii::$app->user->id)) {
                ?><h1>Документы пользователей</h1>
                <h3> Статистика последнего года:
                    <span class="label label-primary">Новых <?=$new_count?></span>
                    <span class="label label-danger">Нуждается в исправлении <?=$warn_count?></span>
                    <span class="label label-success">Готовых <?=$ready_count?></span></h3>
                <?if($documents == null){?>
                    <p>Пользователи не загрузили еще ни одного документа.</p>

                <?}
                else {
                    foreach ($years as $year){?>
                        <h3> <span class="label label-info">Год: <?=$year?></span></h3> 
                        
                        <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>Автор</td>
                            <td>Название</td>
                            <td>Дата последнего изменения</td>
                            <td>Статус</td>
                            <td><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?
                        foreach ($documents as $document) {
                            if (Yii::$app->formatter->asDate($document->updated_at, 'Y')==$year) {
                                ?>
                                <tr>
                                    <td><?= $document->user->username ?></td>
                                    <td><?= $document->title ?></td>
                                    <td><?= $document->updated_at ?></td>
                                    <td><?
                                        switch ($document->status) {
                                            case \app\models\Documents::STATUS_NEW:
                                                echo "<span class=\"label label-primary\">Новый</span>";
                                                break;
                                            case \app\models\Documents::STATUS_WARNING:
                                                echo "<span class=\"label label-danger\">Нуждается в исправлении</span>";
                                                break;
                                            case \app\models\Documents::STATUS_READY:
                                                echo "<span class=\"label label-success\">Утвержден</span>";
                                                break;
                                        }
                                        ?></td>
                                    <td>
                                        <a href="<?= \yii\helpers\Url::toRoute(['documents/view', 'id' => $document->id]) ?>"><span
                                                class="glyphicon glyphicon glyphicon-log-in" aria-hidden="true"></span></a>
                                    </td>
                                </tr>
                                <?
                            }
                        }?>
                    </tbody>
                    </table>

                    <?}
                    ?>

                <?}
                ?>
            <?}
            else {?>
             <h1>Ваши документы</h1>  <a href="<?= \yii\helpers\Url::toRoute('documents/create')?>" class="btn btn-success">Добавить документ</a>

            <?
            if($documents == null){?>
               <p>Вы еще не добавили ни одного документа.</p>
            <?}
            else {
            ?>
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>Название</td>
                    <td>Дата последнего изменения</td>
                    <td>Статус</td>
                    <td><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span></td>
                </tr>
                </thead>
                <tbody>
                <?


                    foreach ($documents as $document) {
                        ?>
                        <tr>
                            <td><?= $document->title ?></td>
                            <td><?= $document->updated_at ?></td>
                            <td><?
                                switch ($document->status) {
                                    case \app\models\Documents::STATUS_NEW:
                                        echo "<span class=\"label label-primary\">Новый";
                                        break;
                                    case \app\models\Documents::STATUS_WARNING:
                                        echo "<span class=\"label label-danger\">Нуждается в исправлении";
                                        break;
                                    case \app\models\Documents::STATUS_READY:
                                        echo "<span class=\"label label-success\">Утвержден";
                                        break;
                                }
                                ?></td>
                            <td>
                                <a href="<?= \yii\helpers\Url::toRoute(['documents/view', 'id' => $document->id]) ?>"><span
                                        class="glyphicon glyphicon glyphicon-log-in" aria-hidden="true"></span></a></td>
                        </tr>
                        <?
                    }
                }
                ?>
                </tbody>
            </table>
            <?}?>
        </div>
    </div>
</div>

<?$this->registerJsFile('@web/js/main.js',['position' => yii\web\View::POS_END,'depends' => [\yii\web\JqueryAsset::className()]]);?>

