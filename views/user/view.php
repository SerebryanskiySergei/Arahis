<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\base\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'User'.' '. Html::encode($this->title) ?></h2>
        </div>
        <div class="col-sm-3" style="margin-top: 15px">
                        
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ])
            ?>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        ['attribute' => 'id', 'hidden' => true],
        'username',
        'course',
        'group',
        'email:email',
        'created_at',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
    
    <div class="row">
<?php
if($providerComments->totalCount){
    $gridColumnComments = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            [
                'attribute' => 'user.username',
                'label' => 'Author'
        ],
            [
                'attribute' => 'documents.created_at',
                'label' => 'Document'
        ],
            'message:ntext',
            'created_at',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerComments,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-comments']],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Comments'.' '. $this->title),
        ],
        'columns' => $gridColumnComments
    ]);
}
?>
    </div>
    
    <div class="row">
<?php
if($providerDocuments->totalCount){
    $gridColumnDocuments = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            'title',
            [
                'attribute' => 'user.username',
                'label' => 'User'
        ],
            'created_at',
    ];
    echo Gridview::widget([
        'dataProvider' => $providerDocuments,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-documents']],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Documents'.' '. $this->title),
        ],
        'columns' => $gridColumnDocuments
    ]);
}
?>
    </div>
    
    <div class="row">
<?php
if($providerFiles->totalCount){
    $gridColumnFiles = [
        ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'id', 'hidden' => true],
            'filename',
            [
                'attribute' => 'documents.created_at',
                'label' => 'Document'
        ],
            'tag',
            'created_at',
            [
                'attribute' => 'user.username',
                'label' => 'Author'
        ],
    ];
    echo Gridview::widget([
        'dataProvider' => $providerFiles,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-files']],
        'panel' => [
        'type' => GridView::TYPE_PRIMARY,
        'heading' => '<span class="glyphicon glyphicon-book"></span> ' . Html::encode('Files'.' '. $this->title),
        ],
        'columns' => $gridColumnFiles
    ]);
}
?>
    </div>
</div>