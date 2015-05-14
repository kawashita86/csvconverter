<?php
include_once('config/config.php');
use League\Csv\Reader;
use League\Csv\Writer;

require 'vendor/autoload.php';

$converter = new CSVConverter();
if(Tools::isSubmit('previous_files') && Tools::getValue('previous_files') != '') {
    $filename = trim(Tools::getValue('previous_files'));
} else if($converter->uploadCSV()) {
    $filename = $_FILES['file']['name'];
}

if(isset($filename) && !empty($filename)){
    $template = new Template((int)Tools::getValue('template_name'));
    $cell_title = $template->getCellsHeader();
    $validators = $template->getCellValidators();

    $reader = Reader::createFromPath('import/'.$filename);

    Configuration::loadConfiguration();

    if(Configuration::get('SEPARATOR') == '\t' || Configuration::get('SEPARATOR') == 't')
        $reader->setDelimiter("\t");
    else
        $reader->setDelimiter(Configuration::get('SEPARATOR'));
    if(Configuration::get('TEXT_CONTAINER') != '' && Configuration::get('TEXT_CONTAINER') != 'n')
        $reader->setEnclosure(Configuration::get('TEXT_CONTAINER'));
    $reader->setEscape('\\');
    $reader->setNewline(Configuration::get('NEW_LINE'));
    $reader->setFlags(SplFileObject::SKIP_EMPTY);


    if((int)Configuration::get('HEADER_LINE') == 0){
        $data = $reader->fetchAll(function ($row) use($validators) {
            return CSVConverter::getValidatedRow($row, $validators);
        });
    } else {
        $data = $reader->setOffset((int)Configuration::get('HEADER_LINE'))->fetchAll(function ($row) use($validators) {
            return CSVConverter::getValidatedRow($row, $validators);
        });
    }

    $csv = Writer::createFromFileObject(new SplTempFileObject());
    if($template->separator == '\t' || $template->separator == 't')
        $csv->setDelimiter("\t");
    else
        $csv->setDelimiter($template->separator);

    $csv->setNewline("\r\n");

    $csv->setEscape('\\');
    $csv->setOutputBOM(Writer::BOM_UTF8);
    $csv->insertOne($cell_title);
    if(empty($template->text_container) || $template->text_container == 'n') {
        $csv->setSpecial(true);
    }
    else
        $csv->setEnclosure(stripslashes($template->text_container));
    $csv->insertAll($data);

    if(Tools::getValue('file_type') == 'txt') {
        $filename = $template->name.'_'.date('Y-m-d_H-i');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '. strlen($csv));
        header('Content-Disposition: attachment; filename="'.$filename.'.txt"');
        $csv->output($filename.".txt");
        exit;
    } else if(Tools::getValue('file_type') == 'csv') {
        $filename = $template->name.'_'.date('Y-m-d_H-i');

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '. strlen($csv));
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        if(Tools::getValue('encoding') == 'default'){
            $csv->output($filename.".csv");
        } else if(Tools::getValue('encoding') == 'windows') {
            $encoded_csv = mb_convert_encoding($csv->__toString(), 'UTF-16LE', 'UTF-8');
            echo chr(255) . chr(254) . $encoded_csv;
        }
        exit;
       // $csv->output($filename.".csv");
       // echo $csv->toHTML();
       /* ?>
        <!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="utf-8">
                    </head>
                <body>
                    <p><?php
                       echo $csv->toHTML();
                       //call_user_func_array('mb_convert_encoding', array(&$data[21],'HTML-ENTITIES','WINDOWS-1252'));
                     //   echo mb_convert_encode($data[21],'HTML-ENTITIES','UTF-8'); //mb_detect_encoding ( $data[21]); ?>
                    </p>
                </body>
            </html>
  <?php*/
    }
}else {

}