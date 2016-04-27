<?php


namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class SettingsForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $req_text;
    public $req_file;
    public $working;

    public function rules()
    {
        return [
            [['req_file'], 'file', 'skipOnEmpty' => true,],
            [['req_text'],'required']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $ext = $this->req_file->extension;
            $filename = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = 'uploads/' . $filename;
            $this->req_file->saveAs($path);;
            $this->req_file = $filename;
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'req_file' => 'Файл требований',
            'req_text' => 'Текст требований',
            'working' => 'Сайт работает?'
        ];
    }
}