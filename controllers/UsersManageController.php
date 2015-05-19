<?php

class UsersManageController extends Controller
{

    public $php_self = 'users-manage';
    public $id_user = 0;
    public $errors = [];

    public function init()
    {
        parent::init();
        if(Tools::getValue('id_user'))
            $this->id_user = (int)Tools::getValue('id_user');
    }

    public function initContent()
    {


        if($this->id_user != 0){
            $user = new User($this->id_user);

            $this->smarty->assign(array(
                'user' => $user,
            ));
        }

        $this->smarty->assign(array(
            'main_page' => $this->php_self
        ));

        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);
        $this->setTemplate('users_manage.tpl');

        parent::initContent();
    }

    public function postProcess(){
        parent::postProcess();
    }
}