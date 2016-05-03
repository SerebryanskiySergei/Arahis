<?php

use app\models\User;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить нового', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'username',
            'course',
            'group',
//            'auth_key',
            // 'password_hash',
            // 'password_reset_token',
             'email:email',
            [
                'attribute' => 'role',
                'format'=>'html',
                'value' => function ($model) {
                    switch ($model->role){
                        case User::ROLE_ADMIN:
                            return '<span class="label label-warning">Админ</span>';
                        case User::ROLE_USER:
                            return '<span class="label label-default">Студент</span>';
                        default:
                            return '<span class="label label-error">ERROR!!!</span>';
                    }
                }
            ],

            // 'status',
             'created_at:datetime',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
