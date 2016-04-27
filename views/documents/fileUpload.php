<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Загрузка новой версии';
$this->params['breadcrumbs'][] = $this->title;
$i=0;

?>
<div class="row">
    <div class="col-md-6">
        <h1>История изменений</h1>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>Метка</th>
                <th>Дата загрузки</th>
                <th>Скачать</th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($history as $row){
                $i++;?>
                <tr>
                    <td><?=$i;?></td>
                    <td><?=$row->tag;?></td>
                    <td><?=$row->created_at?></td>
                    <td class="text-center"><a href="<?=\yii\helpers\Url::toRoute(['documents/download-file','file'=>$row->filename])?>"><span class="glyphicon glyphicon-download" aria-hidden="true"></span></a></td>
                </tr>

            <?}
            ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">
        <h1><?= Html::encode($this->title) ?></h1>

        <? $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','style'=>'    margin-top: 16px;']]) ?>
        <?= $form->field($model, 'tag')->textInput() ?>
        <?= $form->field($model, 'docFile')->fileInput() ?>
        <?= $form->field($model, 'document_id')->hiddenInput()->label(false)?>

        <button class="btn btn-primary">Загрузить</button>

        <?php ActiveForm::end() ?>
    </div>
</div>

    
