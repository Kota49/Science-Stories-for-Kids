<?php

/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author	 : Shiv Charan Panjeta < shiv@toxsl.com >
 */
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{

    public $username;

    public $password;

    public $rememberMe = true;

    private $_user = false;

    public $device_token;

    public $device_type;

    public function asJson()
    {
        $Json = [];
        $Json['username'] = $this->username;
        $Json['device_token'] = $this->device_token;
        $Json['device_type'] = $this->device_type;
        return $Json;
    }

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Email')
        ];
    }

    /**
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [
                [
                    'username',
                    'password'
                ],
                'required'
            ],
            [
                [
                    'username',
                    'password'
                ],
                'filter',
                'filter' => 'trim'
            ],
            [
                [
                    'username'
                ],
                'email',
                'message' => 'Email is not a valid email address.'
            ],
            // rememberMe must be a boolean value
            [
                'rememberMe',
                'boolean'
            ],
            [
                [
                    'device_token',
                    'device_type'
                ],
                'safe'
            ],
            // password is validated by validatePassword()
            [
                'password',
                'validatePassword'
            ]
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute
     *            the attribute currently being validated
     * @param array $params
     *            the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (! $this->hasErrors()) {
            $user = $this->getUser();

            if ($user == null) {
                $this->addError('username', 'Incorrect email');
                return false;
            }
            if (defined('ENABLE_LDAP')) {
                return $user->ldapValidatePassowrd($this->password);
            } else if (! $user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect Password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            LoginHistory::add(true, $user, null);
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        LoginHistory::add(false, $this->getUser(), $this->errors);
        return false;
    }

    public function applogin()
    {
        $user = $this->getAppUser();
        if ($user) {
            
            
            if (! $user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect Password.');
            }
            
            
            
            
            if (! $this->hasErrors()) {
                LoginHistory::add(true, $user, null);
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            $this->addError('username', 'Incorrect User Name');
        }
        LoginHistory::add(false, $this->getUser(), $this->errors);
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);

            if (IS_ADMIN_ONLY && $this->_user && ! $this->_user->role_id == User::ROLE_ADMIN) {
                throw new ForbiddenHttpException('You are not authorised');
            }
        }

        return $this->_user;
    }

    public function loginApp()
    {
        $user = $this->getUser();
        
        if ($user) {
            if (! $user->validatePassword($this->password)) {
                $this->addError('password', 'Incorrect Email or Password.');
            }
            if (! $this->hasErrors()) {
                LoginHistory::add(true, $user, null);
                return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
            }
        } else {
            $this->addError('username', 'Incorrect Email');
        }

        LoginHistory::add(false, $this->getUser(), $this->errors);
        return false;
    }

    public function getAppUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
