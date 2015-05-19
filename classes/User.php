<?php

class User extends ObjectModel {

    public $id;
    public $email;
    public $password;
    public $active;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'users',
        'primary' => 'id_user',
        'fields' => array(
            'email' => array('type' => self::TYPE_STRING),
            'password' => 	array('type' => self::TYPE_STRING),
            'active' => array('type' => self::TYPE_INT)
        ),
    );


    public static function getAll(){
        return Db::getInstance()->executeS('SELECT * FROM users');
    }

    public static function login($email = '', $password = ''){
        if(!Validate::isEmail($email) || empty($password))
            return false;

        $password = md5($password);
        return Db::getInstance()->getRow('SELECT * FROM users WHERE email = "'.$email.'" AND password = "'.$password.'" AND active = 1');
    }

    public function setPassword($password){
        $this->password = md5($password);
    }

    public function toggleActive(){
        $this->active = !$this->active;
    }
}