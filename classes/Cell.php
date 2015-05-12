<?php

class Cell extends ObjectModel{

    public $id;
    public $id_template;
    public $name;
    public $position;
    public $id_type;
    public $fixed_value = '';
    public $cell_position;
    public $price_round = 0;
    public $strip_element = 0;
    public $quantity_round = 0;
    public $no_negative = 0;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'cells',
        'primary' => 'id_cell',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING),
            'id_template' => array('type' => self::TYPE_INT),
            'position' => array('type' => self::TYPE_INT),
            'id_type' => array('type' => self::TYPE_INT),
            'fixed_value' => array('type' => self::TYPE_STRING),
            'cell_position' => array('type' => self::TYPE_INT),
            'price_round' => array('type' => self::TYPE_INT),
            'strip_element' => array('type' => self::TYPE_INT),
            'quantity_round' => array('type' => self::TYPE_INT),
            'no_negative' => array('type' => self::TYPE_INT)
        ),
    );


    public static function getAll($id_template){
        if(!$id_template) return false;
        //query to get all cell
        return Db::getInstance()->executeS('SELECT * FROM cells WHERE id_template= '.$id_template .' ORDER BY cell_position ASC');
    }

    public static function deleteById($ids = array(), $id_template = 0){
        if(!empty($ids)){
            Db::getInstance()->execute('DELETE FROM cells WHERE id_cell NOT IN ('.implode(',',$ids).') AND id_template = '.$id_template);
        }
    }

    public static function deleteByTemplate($id_template = 0){
        if(!empty($id_template)){
            Db::getInstance()->execute('DELETE FROM cells WHERE id_template ='.$id_template);
        }
    }


}