<?php


class CellConversion extends ObjectModel {

    public $id;
    public $name;
    public $function;
    public $active = 1;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'cell_conversions',
        'primary' => 'id_cell_conversion',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING),
            'active' => array('type' => self::TYPE_INT),
            'function' => array('type' => self::TYPE_STRING)
        ),
    );

    public static function getAll(){
        return Db::getInstance()->executeS('SELECT * FROM cell_conversions');

    }

}