<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property string $LoginUsu
 * @property string $ClaveUsu
 * @property string $StatusUsu
 * @property string $NombUsu
 * @property string $idperfil
 * @property string $ciinfper
 * @property string $idcarr
 * @property string $id_actdist
 * @property integer $usa_biometrico
 * @property string $fecha_reg
 * @property string $fecha_ultimo_acceso
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LoginUsu', 'idperfil', 'id_actdist', 'usa_biometrico', 'fecha_reg', 'fecha_ultimo_acceso'], 'required'],
            [['usa_biometrico'], 'integer'],
            [['fecha_reg', 'fecha_ultimo_acceso'], 'safe'],
            [['LoginUsu', 'ciinfper'], 'string', 'max' => 20],
            [['ClaveUsu', 'NombUsu', 'id_actdist'], 'string', 'max' => 100],
            [['StatusUsu'], 'string', 'max' => 1],
            [['idperfil'], 'string', 'max' => 10],
            [['idcarr'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LoginUsu' => 'Login Usu',
            'ClaveUsu' => 'Clave Usu',
            'StatusUsu' => 'Status Usu',
            'NombUsu' => 'Nomb Usu',
            'idperfil' => 'Idperfil',
            'ciinfper' => 'Ciinfper',
            'idcarr' => 'Idcarr',
            'id_actdist' => 'Id Actdist',
            'usa_biometrico' => 'Usa Biometrico',
            'fecha_reg' => 'Fecha Reg',
            'fecha_ultimo_acceso' => 'Fecha Ultimo Acceso',
        ];
    }


	/**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


	/**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['LoginUsu' => $username, 'StatusUsu' => 1]);
    }



    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->LoginUsu;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        //return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        //return $this->getAuthKey() === $authKey;
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
        return $this->ClaveUsu === md5($password);
	//return $this->password === $password;
    }

}
