<?php

class ConversionController extends Controller
{

    public $php_self = 'conversion';
    public $errors = [];

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $templates = Template::getAll();
        $files = preg_grep('~\.(csv|txt)$~', scandir('import'));

        $this->smarty->assign(array(
            'templates' => $templates,
            'files' => $files,
            'main_page' => $this->php_self
        ));

        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);
        $this->setTemplate('conversion.tpl');

        parent::initContent();
    }

    public function postProcess(){

        parent::postProcess();
    }
}