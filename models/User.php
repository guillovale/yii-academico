<?php

namespace app\models;

use app\models\Usuario as DbUser;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;

	// buscar en base
	$dbUser = DbUser::find()
            ->where([
                "CIInfPer" => $id
            ])
            ->one();
	if (!count($dbUser)) {
		return null;
	}
	return new static($dbUser);

    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
	/*        
	foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null; */

	$dbUser = DbUser::find()
            ->where(["accessToken" => $token])
            ->one();
	    if (!count($dbUser)) {
		return null;
	    }
	
	return new static($dbUser);
	

    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        
	/*
	foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
	*/
	
	
	$dbUser = DbUser::find()
            ->where([
                "LoginUsu" => $username
            ])
            ->one();

	    if (!count($dbUser)) {
		return null;
	    }

	    //return new static($dbUser);
	//$this->id = getId();
	//self::setUsuario($dbUser->LoginUsu);
	//$this->password = $dbUser->ClaveUsu;
	//echo var_dump(); exit;	
	
	//return	$this->users[100];
	//return $this;
	//$usuario = $this->users[100];
	return new self;
	

    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

	public function setUsuario($usuario)
    {
        $this->username = $usuario;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {	
	//echo var_dump($this->password); exit;
        //return $this->password === md5($password);
	return $this->password === $password;
    }
}
