<?php
define('MAX_LINE_SIZE', 0);


class CSVConverter
{
    public $skip = 1;
    public $errors = array();
    public $separator = '|';
    public static $column_mask = array(
        'reference' => 0,
        'tax_rate' => 6,
        'price_tin' => 8,
        'quantity' => 40
    );
    public static $validators = array(
        'tax_rate' => array('CVSHandle', 'getPrice'),
        'price_tex' => array('CVSHandle', 'getPrice'),
        'price_tin' => array('CVSHandle', 'getPrice')
    );

    public function __construct()
    {

    }

    private function loadSettings(){
        $this->separator = Configuration::get('CSV_SEPARATOR');
        $this->skip = (int)Configuration::get('CSV_SKIP');
        self::$column_mask  = array(
            'reference' => (int)Configuration::get('CSV_REFERENCE'),
            'tax_rate' => (int)Configuration::get('CSV_TAX_RATE'),
            'price_tin' => (int)Configuration::get('CSV_PRICE_TIN'),
            'quantity' => (int)Configuration::get('CSV_QUANTITY')
        );
    }

    protected function openCsvFile($filename)
    {
        //$dir = _PS_ROOT_DIR_.'\modules\csvimporter\import\\';
        $dir = _PS_ROOT_DIR_.'/modules/csvimporter/import/';
        $handle = fopen($dir.strval(preg_replace('/\.{2,}/', '.', $filename)), 'r');

        if (!$handle)
            $this->errors[] = Tools::displayError('Cannot read the .CSV file');

        $this->rewindBomAware($handle);

        for ($i = 0; $i < (int)$this->skip; ++$i)
            $line = fgetcsv($handle, MAX_LINE_SIZE, $this->separator);
        return $handle;
    }

    protected function closeCsvFile($handle)
    {
        fclose($handle);
    }

    public function rewindBomAware($handle)
    {
        // A rewind wrapper that skip BOM signature wrongly
        rewind($handle);
        if (($bom = fread($handle, 3)) != "\xEF\xBB\xBF")
            rewind($handle);
    }

    protected static function getPrice($field)
    {
        $field = ((float)str_replace(',', '.', $field));
        $field = ((float)str_replace('%', '', $field));
        return $field;
    }

    public static function getMaskedRow($row)
    {
        $res = array();
        if (is_array(self::$column_mask))
            foreach (self::$column_mask as $type => $nb)
                $res[$type] = isset($row[$nb]) ? $row[$nb] : null;

        return $res;
    }

    protected function receiveTab()
    {
        $type_value = Tools::getValue('type_value') ? Tools::getValue('type_value') : array();
        foreach ($type_value as $nb => $type)
            if ($type != 'no')
                self::$column_mask[$type] = $nb;
    }

    public function importProduct($filename){
        $handle = $this->openCsvFile($filename);
        $query = 'INSERT IGNORE INTO '._DB_PREFIX_. 'csvimporter (reference, price_tin, price, quantity) VALUES ';
        for ($current_line = 0; $line = fgetcsv($handle, MAX_LINE_SIZE, $this->separator); $current_line++)
        {
            $info = CSVImporter::getMaskedRow($line);
            $info['price_tin'] = CSVImporter::getPrice($info['price_tin']);
            $info['price'] = (float)number_format($info['price_tin'] / (1 + $info['tax_rate'] / 100), 6, '.', '');
            $info['quantity'] = CSVImporter::getPrice($info['quantity']);
            $query .= '("'.$info['reference'].'", '.$info['price_tin'].', '.$info['price'].', '.(int)$info['quantity'].'),';
        }
        $this->closeCsvFile($handle);
        $query = rtrim($query, ",");
        Db::getInstance()->execute('TRUNCATE '._DB_PREFIX_. "csvimporter");
        return Db::getInstance()->execute($query.';');


    }

    private function importCSVtoTable()
    {
        $this->loadSettings();
        if($this->uploadCSV()) {
            $filename = $_FILES['file']['name'];
            if ($this->importProduct($filename)) {

            } else {
                $this->errors[] = Tools::displayError('There was problem while reading the file content');
                return false;
            }
        }
        return false;
    }

    public function uploadCSV(){
        //$dir = _PS_ROOT_DIR_.'\modules\csvimporter\import\\';
        $dir = 'import/';
        if (isset($_FILES['file']) && !empty($_FILES['file']['error']))
        {
            switch ($_FILES['file']['error'])
            {
                case UPLOAD_ERR_INI_SIZE:
                    $this->errors[] = Tools::displayError('The uploaded file exceeds the upload_max_filesize directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess.');
                    return false;
                case UPLOAD_ERR_FORM_SIZE:
                    $this->errors[] = Tools::displayError('The uploaded file exceeds the post_max_size directive in php.ini.
							If your server configuration allows it, you may add a directive in your .htaccess, for example:');
                    return false;
                case UPLOAD_ERR_PARTIAL:
                    $this->errors[] = Tools::displayError('The uploaded file was only partially uploaded.');
                    return false;
                case UPLOAD_ERR_NO_FILE:
                    $this->errors[] = Tools::displayError('No file was uploaded.');
                    return false;
            }
        }
        else if (!file_exists($_FILES['file']['tmp_name']) ||
            !@move_uploaded_file($_FILES['file']['tmp_name'], $dir.$_FILES['file']['name'])) {
            $this->errors[] = 'An error occurred while uploading / copying the file.';
            return false;
        }
        else
            return true;
    }



}