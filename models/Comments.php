<?php

namespace app\models;

use Yii;
use \app\models\base\Comments as BaseComments;

/**
 * This is the model class for table "comments".
 */
class Comments extends BaseComments
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['author_id', 'document_id'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe']
        ]);
    }
	
}
