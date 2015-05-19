<?php
class IndexController extends Controller
{

    public $php_self = 'index';
    public $errors = [];

    public function viewAccess() {
        return true;
    }

    public function init()
    {
        parent::init();
        if(Tools::isSubmit('mylogout'))
        {
            setcookie('csv_user', "", time()-3600);
            unset($_COOKIE['csv_user']);
            header('Location: index.php');
        }

    }

    public function initContent()
    {
        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);

        if($this->isLogged) {
           $templates = Template::getAll();
           $this->setTemplate('index.tpl');
           $this->smarty->assign(array(
               'templates' => $templates,
               'main_page' => $this->php_self
           ));

       }
       else {
           $this->smarty->assign('errors', $this->errors);
           $this->setTemplate('login.tpl');
       }

        parent::initContent();
    }

    public function postProcess(){
        if(Tools::isSubmit('loginMe')){
            if(!Tools::getValue('email') || !Tools::getValue('password')){
                $this->errors[] = 'Inserire Email e Password per effettuare il login';
            } else {
                if($res = User::login(Tools::getValue('email'), Tools::getValue('password'))){
                    if(Tools::getValue('remember') && Tools::getValue('remember') == 1){
                        $expire_time = time()+60*60*24*30;
                    } else
                        $expire_time = 0;

                    setcookie('csv_user', $res['id_user'], $expire_time);
                    Tools::redirect('index.php');
                } else {
                    $this->errors[] = 'Email o Password errate';
                }
            }
        }
        parent::postProcess();
    }
}