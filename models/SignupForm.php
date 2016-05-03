<?php
namespace app\models;

use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $group;
    public $course;

    public function attributeLabels()
    {
        return [
            'username' => 'ФИО',
            'email' => 'Email',
            'password' => 'Пароль',
            'group' => 'Группа',
            'course' => 'Курс',
            'rememberMe' => 'Запомнить?'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Такой пользователь уже существует.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
//            ['username', 'match', 'pattern' => '/^([А-Я][а-я]{1,}\s+){2}([А-Я][а-я]{1,}\s*)$/', 'message' => 'Необохдимо ввести имя фамилию отчество полностью.'],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот email уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['group', 'string'],
            ['course', 'string'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->group = $this->group;
        $user->course = $this->course;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = User::ROLE_USER;
        return $user->save() ? $user : null;
    }
}
