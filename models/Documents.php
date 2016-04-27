<?php

namespace app\models;

use Yii;
use \app\models\base\Documents as BaseDocuments;

/**
 * This is the model class for table "documents".
 */
class Documents extends BaseDocuments
{

    const STATUS_NEW = "new";
    const STATUS_WARNING = "warning";
    const STATUS_READY = "ready";

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['title', 'user_id', 'status'], 'required'],
            [['user_id'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ]);
    }
	
}
