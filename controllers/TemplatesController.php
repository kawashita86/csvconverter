<?php

class TemplatesController extends Controller
{

    public $php_self = 'templates';
    public $errors = [];

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
            $templates = Template::getAll();
        if(Tools::getValue('conf')){
            $this->smarty->assign('conf', (int)Tools::getValue('conf'));
        }
            $this->smarty->assign(array(
                'templates' => $templates,
                'main_page' => $this->php_self
            ));

        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);
        $this->setTemplate('templates.tpl');

        parent::initContent();
    }

    public function postProcess(){
        if(Tools::getValue('submitTemplate')){
            if(Tools::getValue('id_template')){
                $template = new Template((int)Tools::getValue('id_template'));
                $template->name = Tools::getValue('template_name');
                $template->description = Tools::getValue('template_description');
                $template->line_header = (int)Tools::getValue('heading_lines');
                $template->separator = Tools::getValue('separator');
                $template->text_container = Tools::getValue('text_container');
                $template->concatenation_char = Tools::getValue('concatenation_char');
                $template->set_bom =  Tools::getValue('set_bom');
                if($template->update())
                    $template->updateCell($_POST);
                else
                    Tools::redirect('index.php?controller=templates&conf=2');
            } else {
                $template = new Template();
                $template->name = Tools::getValue('template_name');
                $template->description = strip_tags(Tools::getValue('template_description'));
                $template->line_header = (int)Tools::getValue('heading_lines');
                $template->separator = Tools::getValue('separator');
                $template->text_container = Tools::getValue('text_container');
                $template->concatenation_char = Tools::getValue('concatenation_char');
                $template->set_bom =  Tools::getValue('set_bom');
                if($template->add())
                    $template->updateCell($_POST);
                else
                    Tools::redirect('index.php?controller=templates&conf=2');
            }

            Tools::redirect('index.php?controller=templates&conf=1');

        } else if(Tools::getValue('deleteTemplate')) {
            if(Tools::getValue('id_template')){
                $template = new Template((int)Tools::getValue('id_template'));
                if($template->delete())
                    Tools::redirect('index.php?controller=templates&conf=1');
                else
                    Tools::redirect('index.php?controller=templates&conf=2');
            }
        }
        parent::postProcess();
    }
}