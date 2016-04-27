<?php

/* @var $this yii\web\View */
/* @var $model \app\models\SettingsForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->errorSummary($model); ?>

            <?= $form->field($model, 'req_text')->textarea(['rows'=>'7']) ?>

            <?= $form->field($model, 'req_file')->fileInput();?>

            <?= $form->field($model, 'working')->checkbox();?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
