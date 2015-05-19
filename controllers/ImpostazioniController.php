<?php

class ImpostazioniController extends Controller
{

    public $php_self = 'impostazioni';
    public $errors = [];

    public function init()
    {
        parent::init();
    }

    public function initContent()
    {

        Configuration::loadConfiguration();
        $files = preg_grep('~\.(csv|txt)$~', scandir('import'));

        if(Tools::getValue('conf')){
            $this->smarty->assign('conf', (int)Tools::getValue('conf'));
        }

        $this->smarty->assign(array(
            'files' => $files,
            'file_type' => Configuration::get('IMPORT_FILE_TYPE'),
            'header_line' => Configuration::get('HEADER_LINE'),
            'separator' => Configuration::get('SEPARATOR'),
            'text_container' => Configuration::get('TEXT_CONTAINER'),
            'new_line' => Configuration::get('NEW_LINE'),
            'main_page' => $this->php_self
        ));

        $this->smarty->assign('current_link', $_SERVER['PHP_SELF']);
        $this->setTemplate('impostazioni.tpl');

        parent::initContent();
    }

    public function postProcess(){
        if(Tools::isSubmit('updateImpostazioni')){
            $res = true;
            //setup all the configuration with validation of the fields
            if(Tools::getValue('IMPORT_FILE_TYPE')){
                $res &= Configuration::updateValue('IMPORT_FILE_TYPE', Tools::getValue('IMPORT_FILE_TYPE'));
            }

            if(Tools::isSubmit('HEADER_LINE') ){
                $res &=  Configuration::updateValue('HEADER_LINE', (int)Tools::getValue('HEADER_LINE'));
            }

            if(Tools::getValue('SEPARATOR')){
                $res &=  Configuration::updateValue('SEPARATOR', Tools::getValue('SEPARATOR'));
            }

            if(Tools::getValue('TEXT_CONTAINER')){
                $res &=  Configuration::updateValue('TEXT_CONTAINER', Tools::getValue('TEXT_CONTAINER'));
            }

            if(Tools::getValue('NEW_LINE')){
                $res &=  Configuration::updateValue('NEW_LINE', $_POST['NEW_LINE']);
            }

            if($res)
                Tools::redirect('index.php?controller=impostazioni&conf=1');
            else
                Tools::redirect('index.php?controller=impostazioni&conf=2');
        }

        if(Tools::isSubmit('deleteFile')){
            if(Tools::getValue('filename')){
                if(unlink ( 'import/'.Tools::getValue('filename')))
                    Tools::redirect('index.php?controller=impostazioni&conf=1');
                else
                    Tools::redirect('index.php?controller=impostazioni&conf=2');
            }
        }

        parent::postProcess();
    }
}