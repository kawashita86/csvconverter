<?php
/**
* This file is part of the League.csv library
*
* @license http://opensource.org/licenses/MIT
* @link https://github.com/thephpleague/csv/
* @version 7.1.0
* @package League.csv
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/
namespace League\Csv;

use InvalidArgumentException;
use League\Csv\Modifier;
use ReflectionMethod;
use Traversable;

/**
 *  A class to manage data insertion into a CSV
 *
 * @package League.csv
 * @since  4.0.0
 *
 */
class Writer extends AbstractCsv
{
    /**
     * {@ihneritdoc}
     */
    protected $stream_filter_mode = STREAM_FILTER_WRITE;

    /**
     * The CSV object holder
     *
     * @var \SplFileObject
     */
    protected $csv;

    /**
     * fputcsv method from SplFileObject
     *
     * @var ReflectionMethod
     */
    protected static $fputcsv;

    /**
     * Nb parameters for SplFileObject::fputcsv method
     *
     * @var integer
     */
    protected static $fputcsv_param_count;

    /**
     * Nb parameters for Writer::customfputcsv method
     *
     * @var integer
     */
    protected static $customfputcsv_param_count;

    /**
     * customfputcsv method from Writer
     *
     * @var ReflectionMethod
     */
    protected static $customfputcsv;

    protected $set_special = false;

    /**
     * Row Formatter and Validator trait
     */
    use Modifier\RowFilter;

    /**
     * {@ihneritdoc}
     */
    protected function __construct($path, $open_mode = 'r+')
    {
        parent::__construct($path, $open_mode);
        static::initFputcsv();
    }

    /**
     * initiate a SplFileObject::fputcsv method
     */
    protected static function initFputcsv()
    {
        if (is_null(static::$fputcsv)) {
            static::$fputcsv             = new ReflectionMethod('\SplFileObject', 'fputcsv');
            static::$fputcsv_param_count = static::$fputcsv->getNumberOfParameters();
        }

        if (is_null(static::$customfputcsv)) {
            static::$customfputcsv             = new ReflectionMethod('League\Csv\Writer', 'customFputcsv');
            static::$customfputcsv_param_count = static::$customfputcsv->getNumberOfParameters();
        }
    }

    /**
     * Adds multiple lines to the CSV document
     *
     * a simple wrapper method around insertOne
     *
     * @param \Traversable|array $rows a multidimentional array or a Traversable object
     *
     * @throws \InvalidArgumentException If the given rows format is invalid
     *
     * @return static
     */
    public function insertAll($rows)
    {
        if (! is_array($rows) && ! $rows instanceof Traversable) {
            throw new InvalidArgumentException(
                'the provided data must be an array OR a \Traversable object'
            );
        }

        foreach ($rows as $row) {
            $this->insertOne($row);
        }

        return $this;
    }

    /**
     * Adds a single line to a CSV document
     *
     * @param string[]|string $row a string, an array or an object implementing to '__toString' method
     *
     * @return static
     */
    public function insertOne($row)
    {
        if (! is_array($row)) {
            $row = str_getcsv($row, $this->delimiter, $this->enclosure, $this->escape);
        }
        $row = $this->formatRow($row);
        $this->validateRow($row);

        if (is_null($this->csv)) {
            $this->csv = $this->getIterator();
        }

        if($this->set_special)
            static::$customfputcsv->invokeArgs($this, $this->getFputcsvParameters($row));
        else
            static::$fputcsv->invokeArgs($this->csv, $this->getFputcsvParameters($row));
        if ("\n" !== $this->newline) {
            $this->csv->fseek(-1, SEEK_CUR);
            $this->csv->fwrite($this->newline);
        }

        return $this;
    }

    public function customFputcsv(array $fields, $delimiter = ',', $enclosure = '"', $escape = '\\' ) {
        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();
        foreach ($fields as $field) {
            if ($field === null) {
                continue;
            }

            //|| $field != strip_tags($field)
        $output[] = preg_match("/(?:${delimiter_esc}|${enclosure_esc})|\<br\>|\<b\>/", $field) || strstr($field, "\n")  ? (
        $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure) : $field;
    }

        $this->csv->fwrite(join($delimiter, $output)."\n");
 }

    public function setSpecial($bool){
        $this->set_special = $bool;
    }

    public function getSpecial(){
        return $this->set_special;
    }


/**
     * returns the parameters for SplFileObject::fputcsv
     *
     * @param  array $fields The fields to be add
     *
     * @return array
     */
    protected function getFputcsvParameters(array $fields)
    {
        $parameters = [$fields, $this->delimiter, $this->enclosure];
        if (4 == static::$fputcsv_param_count) {
            $parameters[] = $this->escape;
        }

        return $parameters;
    }

    /**
     *  {@inheritdoc}
     */
    public function isActiveStreamFilter()
    {
        return parent::isActiveStreamFilter() && is_null($this->csv);
    }

    /**
     *  {@inheritdoc}
     */
    public function __destruct()
    {
        $this->csv = null;
        parent::__destruct();
    }
}
