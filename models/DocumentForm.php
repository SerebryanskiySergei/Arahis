<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class DocumentForm extends Model
{
    public $title;
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['title', 'file'], 'required']
        ];
    }
    public function attributeLabels()
    {
        return [
            'title' => 'Название документы',
            'file' => 'Файл'
        ];
    }
}
