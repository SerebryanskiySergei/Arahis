<?php


namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFileForm extends Model
{
/**
* @var UploadedFile
*/
    public $docFile;
    public $document_id;
    public $tag;

    public function rules()
    {
        return [
            [['docFile'], 'file', 'skipOnEmpty' => false,],
            [['tag','docFile'],'required']
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $ext = $this->docFile->extension;
            $filename = Yii::$app->security->generateRandomString() . ".{$ext}";
            $path = 'uploads/' . $filename;
            $this->docFile->saveAs($path);;
            $this->docFile = $filename;
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'tag' => 'Метка версии',
            'docFile' => 'Файл'
        ];
    }
}