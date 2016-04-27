<?php

namespace app\models;

use Yii;
use \app\models\base\Files as BaseFiles;

/**
 * This is the model class for table "files".
 */
class Files extends BaseFiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['document_id'], 'integer'],
            [['created_at'], 'safe'],
            [['filename', 'tag'], 'string', 'max' => 255]
        ]);
    }
	
}
