<?php

class UsersController extends Controller
{

    public $php_self = 'users';
    public $errors = [];

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $users = User::getAll();
        if(Tools::getValue('conf')){
            $this->smarty->assign('conf', (int)Tools::getValue('conf'));
        }
        $this->smarty->assign(array(
            'users' => $users,
            'main_page' => $this->php_self,
            'current_link', $_SERVER['PHP_SELF']
        ));
        $this->setTemplate('users.tpl');

        parent::initContent();
    }

    public function postProcess(){
        if(Tools::getValue('submitUser')){
            if(Tools::getValue('id_user')){
                $user = new User((int)Tools::getValue('id_user'));
                $user->email = Tools::getValue('email');
                $user->setPassword(trim(Tools::getValue('password')));
                $user->active = 1;
                if(!$user->update())
                    Tools::redirect('index.php?controller=users&conf=2');
            } else {
                $user = new User();
                $user->email = Tools::getValue('email');
                $user->setPassword(trim(Tools::getValue('password')));
                $user->active = 1;
                if(!$user->add())
                    Tools::redirect('index.php?controller=users&conf=2');
            }

            Tools::redirect('index.php?controller=users&conf=1');

        } else if(Tools::getValue('toggleUser')) {
            if(Tools::getValue('id_user')){
                $user = new User((int)Tools::getValue('id_user'));
                $user->toggleActive();
                if($user->update())
                    Tools::redirect('index.php?controller=users&conf=1');
                else
                    Tools::redirect('index.php?controller=users&conf=2');
            }
        }
        parent::postProcess();
    }
}