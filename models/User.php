<?php

namespace app\models;

use Yii;
use \app\models\base\User as BaseUser;

/**
 * This is the model class for table "user".
 */
class User extends BaseUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['role'], 'required'],
            [['role', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['course', 'group'], 'string', 'max' => 50]
        ]);
    }

    
	
}
