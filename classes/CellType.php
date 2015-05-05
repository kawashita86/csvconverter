<?php


class CellType extends  ObjectModel {
    public $id;
    public $name;
    public $function;
    public $active = 1;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'cell_types',
        'primary' => 'id_cell_type',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING),
            'active' => array('type' => self::TYPE_INT),
            'function' => array('type' => self::TYPE_STRING)
        ),
    );


    public static function  getAll(){
        //query to get all
        return Db::getInstance()->executeS('SELECT * FROM cell_types');
    }

    public static function getAllById(){
        $res = Db::getInstance()->executeS('SELECT * FROM cell_types');
        $array_to_return = array();
        if(!$res) return array();
        foreach($res as $r){
            $array_to_return[(int)$r['id_cell_type']] = $r;
        }
        return $array_to_return;
    }
}