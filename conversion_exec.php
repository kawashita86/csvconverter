<?php
include_once('config/config.php');

use League\Csv\Reader;
use League\Csv\Writer;
//use Maatwebsite\Excel;


require 'vendor/autoload.php';

$converter = new CSVConverter();
if($converter->uploadCSV()) {
    $filename = $_FILES['file']['name'];

    $template = new Template((int)Tools::getValue('template_name'));
    $cell_title = $template->getCellsHeader();
    $validators = $template->getCellValidators();

    //switch for different file, must treat if not csv
    if(Configuration::get('IMPORT_FILE_TYPE') != 'csv'){
       /* Excel::load('import/.'.$filename.Configuration::get('IMPORT_FILE_TYPE') , function($reader) {

            // reader methods

        })->convert('csv');*/
    }

    $reader = Reader::createFromPath(new SplFileObject('import/'.$filename));

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


    //$headers = $reader->fetchOne();
    if((int)Configuration::get('HEADER_LINE') == 0){
        $data = $reader->fetchAll(function ($row) use($validators) {
            return CSVConverter::getValidatedRow($row, $validators);
        });
    } else {
        $data = $reader->setOffset((int)Configuration::get('HEADER_LINE'))->fetchAll(function ($row) use($validators) {
            return CSVConverter::getValidatedRow($row, $validators);
        });
    }

    //create new file and print_it
    $csv = Writer::createFromFileObject(new SplTempFileObject());
    if($template->separator == '\t' || $template->separator == 't')
        $csv->setDelimiter("\t");
    else
        $csv->setDelimiter($template->separator);

    if(empty($template->text_container) || $template->text_container == 'n')
        $csv->setEnclosure(" ");
    else
        $csv->setEnclosure(stripslashes($template->text_container));

    $csv->setNewline("\n");

    $csv->setEscape('\\');
    $csv->setOutputBOM(Reader::BOM_UTF8);
    $csv->insertOne($cell_title);
    $csv->insertAll($data);

    if(Tools::getValue('file_type') == 'txt') {
        $filename = $template->name.'_'.date('Y-m-d_H-i');
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="'.$filename.'.txt"');
        $csv->output($filename.".txt");
    } else if(Tools::getValue('file_type') == 'csv') {
        $filename = $template->name.'_'.date('Y-m-d_H-i');

        //header('Content-Type: text/csv; charset=UTF-8');
        //header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        //$csv->output($filename.".csv");
        echo $csv->toHTML();
    }

}