<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;
//use app\models\Usuario as DbUser;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
	//public $id;    
	public $username;
	public $password;
	public $rememberMe = true;

    public $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
	 //echo var_dump($attribute, $params); exit;
        if (!$this->hasErrors()) {
            $user = $this->getUser();
		//echo var_dump($user); exit;
            if ( !$user || !$user->validatePassword($this->password) ) {
                $this->addError($attribute, 'Incorrecto usuario o password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
		#echo var_dump(Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0)); exit;
			#Yii::$app->user->identity = $this->getUser();
			#Yii::$app->user->login($this->getUser());
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
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
            $this->_user = Usuario::findByUsername($this->username);
        }
		#echo var_dump($this->_user); exit;
        return $this->_user;
    }
}
