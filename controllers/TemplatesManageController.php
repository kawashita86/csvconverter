<?php

class TemplatesManageController extends Controller
{

    public $php_self = 'templates-manage';
    public $id_template = 0;
    public $errors = [];

    public function init()
    {
        parent::init();
        if(Tools::getValue('id_template'))
            $this->id_template = (int)Tools::getValue('id_template');
    }

    public function initContent()
    {
        $cell_type = CellType::getAll();
        $cell_conversion = CellConversion::getAll();

        if($this->id_template != 0){
            $template = new Template($this->id_template);
            $cell_made = $template->getAllCells();

            $this->smarty->assign(array(
                'template' => $template,
                'cell_made' => $cell_made,
            ));
        }

        $this->smarty->assign(array(
            'cell_type' => $cell_type,
            'cell_conversion' => $cell_conversion,
            'main_page' => $this->php_self
        ));

        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);
        $this->setTemplate('templates_manage.tpl');

        parent::initContent();
    }

    public function postProcess(){
        parent::postProcess();
    }
}