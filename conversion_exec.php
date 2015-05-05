<?php
include_once('config/config.php');

use League\Csv\Reader;
use League\Csv\Writer;


require 'vendor/autoload.php';

$converter = new CSVConverter();
if($converter->uploadCSV()) {
    $filename = $_FILES['file']['name'];

    $template = new Template((int)Tools::getValue('template_name'));
    $positions = $template->getPositionList();
    $validators = $template->getCellValidators();

    $reader = Reader::createFromPath(new SplFileObject('import/'.$filename));

    $reader->setDelimiter('|');
    $reader->setEnclosure('"');
    $reader->setEscape('\\');
    $reader->setNewline("\r\n");
    $data = $reader->fetchAssoc($positions, function($row) use ($validators) {
        // return array_map('strtoupper', $row);
        if(!empty($row))
            return CSVConverter::getValidatedRow($row, $validators);
    });
    echo '<pre>';
    print_r($data);
    echo '</pre>';
//$csv->setOutputBOM(Reader::BOM_UTF8);
//$bom = $csv->getInputBOM();
//$reader->setEncodingFrom('iso-8859-15');
//$csv->setFlags(SplFileObject::READ_AHEAD|SplFileObject::SKIP_EMPTY);

//$delimiters_list = $reader->detectDelimiterList(10, [' ', '|']);

//foreach ($reader as $index => $row) { }
//echo $reader->__toString();

//header('Content-Type: text/csv; charset=UTF-8');
//header('Content-Disposition: attachment; filename="name-for-your-file.csv"');
//$reader->output();
//"name-for-your-file.csv"

//$data = $reader->fetchAssoc(['firstname', 'lastname', 'email']);
//$data = $reader->fetchColumn(2);
//$data = $reader->fetchAll(function ($row) {
//    return array_map('strtoupper', $row);
//});


    /*
    $res = [];
    $func = null;
    $nbIteration = $reader->each(function ($row, $index, $iterator) use (&$res, $func)) {
        if (is_callable($func)) {
            $res[] = $func($row, $index, $iterator);
            return true;
        }
        $res[] = $row;
        return true;
    });
     */

//$writer = Writer::createFromPath(new SplFileObject('/path/to/your/csv/file.csv', 'a+'), 'w');
}