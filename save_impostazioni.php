<?php
include_once('config/config.php');


if(Tools::isSubmit('updateImpostazioni')){


    //setup all the configuration with validation of the fields
    if(Tools::getValue('IMPORT_FILE_TYPE')){
        Configuration::updateValue('IMPORT_FILE_TYPE', Tools::getValue('IMPORT_FILE_TYPE'));
    }

    if(Tools::isSubmit('HEADER_LINE') ){
        Configuration::updateValue('HEADER_LINE', (int)Tools::getValue('HEADER_LINE'));
    }

    if(Tools::getValue('SEPARATOR')){
        Configuration::updateValue('SEPARATOR', Tools::getValue('SEPARATOR'));
    }

    if(Tools::getValue('TEXT_CONTAINER')){
        Configuration::updateValue('TEXT_CONTAINER', Tools::getValue('TEXT_CONTAINER'));
    }

    if(Tools::getValue('NEW_LINE')){
        Configuration::updateValue('NEW_LINE', $_POST['NEW_LINE']);
    }

    Tools::redirect('impostazioni.php');
}