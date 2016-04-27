<?php

namespace app\models;

use Yii;
use \app\models\base\Settings as BaseSettings;

/**
 * This is the model class for table "settings".
 */
class Settings extends BaseSettings
{

    const REQ_TEXT = "Текст требований к документу";
    const REQ_FILE = "Название файла требований к документу";
    const WORK = "Сайт запущен";
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'value'], 'required'],
            [['name', 'value']]
        ]);
    }
	
}
