<?php
include_once('config/config.php');


if(Tools::getValue('submitTemplate')){
    if(Tools::getValue('id_template')){
        $template = new Template((int)Tools::getValue('id_template'));
        $template->name = Tools::getValue('template_name');
        $template->description = '';
        $template->line_header = (int)Tools::getValue('heading_lines');
        $template->separator = Tools::getValue('separator');
        $template->text_container = Tools::getValue('text_container');
        if($template->update())
            $template->updateCell($_POST);
        else
            echo 'errore generale';
    } else {
        $template = new Template();
        $template->name = Tools::getValue('template_name');
        $template->description = '';
        $template->line_header = (int)Tools::getValue('heading_lines');
        $template->separator = Tools::getValue('separator');
        $template->text_container = Tools::getValue('text_container');
        if($template->add())
            $template->updateCell($_POST);
        else
            echo 'errore generale';
    }

    Tools::redirect('templates.php');
} else if(Tools::getValue('deleteTemplate')) {
    if(Tools::getValue('id_template')){
        $template = new Template((int)Tools::getValue('id_template'));
        $template->delete();
        Tools::redirect('templates.php');
    }
}
