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

    public static function getValidatedRow($row, $validators){
        $new_row = array();
        foreach($validators as $key => $validator){
           if($validator['type'] != null) {
               $new_row[(int)$key] = CSVConverter::$validator['type']($row,$validator['position'], $validator['fixed_value'], $validator['concatenation_char']);
           }
        }

        return $new_row;
    }

    public function rewindBomAware($handle)
    {
        // A rewind wrapper that skip BOM signature wrongly
        rewind($handle);
        if (($bom = fread($handle, 3)) != "\xEF\xBB\xBF")
            rewind($handle);
    }

    protected static function getPrice($row, $field, $fixed_value)
    {
        $row[$field] = ((float)str_replace(',', '.', $row[$field]));
        $row[$field] = ((float)str_replace('%', '', $row[$field]));
        return $row[$field];
    }

    protected static function getText($row, $field, $fixed_value){
        return strip_tags($row[$field]);
    }

    protected static function getPriceComma($row, $field, $fixed_value) {
        $row[$field] = ((float)str_replace('%', '', $row[$field]));
        return $row[$field];
    }


    protected static function getIntegerNumber($row, $field, $fixed_value)
    {
        $row[$field] = ((float)str_replace(',', '.', $row[$field]));
        $row[$field] = ((float)str_replace('%', '', $row[$field]));
        return (int)$row[$field];
    }

    protected static function getHTML($row, $field)
    {
        return $row[$field];
    }

    protected static function getFixedValue($row, $field, $fixed_value)
    {
        return $fixed_value;
    }

    protected static function getConcatenation($row, $field, $fixed_value, $special_chars){
        $indexes = explode(',', $fixed_value);
        $concatenation = array();
        foreach($indexes as $v){
           if(isset($row[(int)$v]))
            $concatenation[] = $row[(int)$v];
        }

        return implode($special_chars, $concatenation);
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