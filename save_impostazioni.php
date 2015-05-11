<?php
include_once('config/config.php');


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
        Tools::redirect('impostazioni.php?conf=1');
   else
       Tools::redirect('impostazioni.php?conf=2');
}