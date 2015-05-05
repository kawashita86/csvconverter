<?php
class Template extends ObjectModel {
    public $id;
    public $name;
    public $separator;
    public $line_header;
    public $text_container;
    public $description;
    public $cells;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'templates',
        'primary' => 'id_template',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING),
            'separator' => 	array('type' => self::TYPE_STRING),
            'line_header' => array('type' => self::TYPE_INT),
            'text_container' => array('type' => self::TYPE_STRING),
            'description' => array('type' => self::TYPE_STRING),
        ),
    );

    public static function getAll(){
        //query to get all from db
        return Db::getInstance()->executeS('SELECT * FROM templates');
    }

    public function getAllCells(){
        if(empty($this->cells))
            $this->cells =  Cell::getAll($this->id);
        return $this->cells;
    }

    public function delete(){
        $id = $this->id;
        if(parent::delete()) {
            Cell::deleteByTemplate($id);
            return true;
        }

        return false;
    }

    public function getPositionList(){
       if(empty($this->cells))
            $this->cells = Cell::getAll($this->id);
        $positions = array();
        foreach($this->cells as $c){
            $positions[] = (int)$c['position'];
        }

        return $positions;
    }

    public function getCellValidators(){
        if(empty($this->cells))
            $this->cells = Cell::getAll($this->id);
         $cell_type = CellType::getAllById();
         $cell_conversion = CellConversion::getAllById();
        $validators = array();
        foreach($this->cells as $c){
            $validators[(int)$c['position']] =
                array(
                    'type' => $c['id_type'] == 0 ? null : $cell_type[$c['id_type']]['function'],
                    'conversion' => $c['id_conversion'] == 0 ? null : $cell_conversion[$c['id_conversion']]['function'],
                    'fixed_value' => $c['fixed_value']
                );
        }

        return $validators;
    }

    public function updateCell($request_data){
        //foreach data must check if exists, then create object and save it with referral
        if(isset($request_data['cell_position'])){
            $ids_query = array();

            for($i = 0; $i < count($request_data['cell_position']); $i++){
                if(!empty($request_data['cell_position'][$i])){
                    if(!empty($request_data['cell_id'][$i]) && (int)$request_data['cell_id'][$i] != 0) {
                        $ids_query[] = (int)$request_data['cell_id'][$i];
                        $cell_id = (int)$request_data['cell_id'][$i];
                    } else {
                        $cell_id = 0;
                    }
                    $name = $request_data['cell_name'][$i];
                    $position = (int)$request_data['cell_position'][$i];
                    $id_conversion = (int)$request_data['special_conversion'][$i];
                    $id_type = (int)$request_data['cell_formatting'][$i];
                    $id_template = $this->id;
                    $fixed_value = $request_data['special_value'][$i];
                    if($cell_id == 0) {
                        Db::getInstance()->execute('INSERT INTO cells (id_template, name, position, id_type, id_conversion, fixed_value) VALUES (' . $id_template . ', "' . pSQL($name, true) . '", ' . $position . ', ' . $id_type . ', ' . $id_conversion . ', "' . pSQL($fixed_value, true) . '")');
                    } else {
                        Db::getInstance()->execute('UPDATE cells SET name = "' . pSQL($name, true) . '",  position = ' . $position . ', id_type = ' . $id_type . ', id_conversion = ' . $id_conversion . ', fixed_value = "' . pSQL($fixed_value, true) . '" WHERE id_cell = '.$cell_id);

                    }
                }
            }
            Cell::deleteById($ids_query);
        }
    }

}