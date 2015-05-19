<?php

class IstruzioniController extends Controller
{

    public $php_self = 'istruzioni';
    public $errors = [];

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $this->smarty->assign(array(
            'main_page' => $this->php_self,
            'current_link', $_SERVER['PHP_SELF']
        ));
        $this->setTemplate('istruzioni.tpl');

        parent::initContent();
    }

    public function postProcess(){


        parent::postProcess();
    }
}