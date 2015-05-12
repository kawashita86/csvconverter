<?php
class Template extends ObjectModel {
    public $id;
    public $name;
    public $separator;
    public $line_header;
    public $text_container;
    public $description;
    public $concatenation_char;
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
            'concatenation_char' => array('type' => self::TYPE_STRING)
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

    public function getCellsHeader(){
        if(empty($this->cells))
            $this->cells = Cell::getAll($this->id);
        $titles = array();
        foreach($this->cells as $c){
            $titles[] = stripslashes($c['name']);
        }

        return $titles;
    }

    public function getCellValidators(){
        if(empty($this->cells))
            $this->cells = Cell::getAll($this->id);
        $cell_type = CellType::getAllById();
        $validators = array();
        foreach($this->cells as $c){

            $validators[(int)$c['cell_position']] =
                array(
                    'type' => $c['id_type'] == 0 ? null : $cell_type[$c['id_type']]['function'],
                    'fixed_value' => $c['fixed_value'],
                    'extra_action' => ($c['id_type'] == 2 || $c['id_type'] == 7) ?$c['price_round'] : $c['id_type'] == 4 ? $c['quantity_round'] : 0,
                    'extra_action_1' => ($c['id_type'] == 2 || $c['id_type'] == 7) ?$c['strip_element'] : $c['id_type'] == 4 ? $c['no_negative'] : 0,
                    'concatenation_char' => $this->concatenation_char,
                    'position' => (int)$c['position']
                );
        }

        return $validators;
    }

    public function updateCell($request_data){
        //foreach data must check if exists, then create object and save it with referral
        if(isset($request_data['cell_position'])){
            $ids_query = array();

            for($i = 0; $i < count($request_data['cell_position']); $i++){
                if(isset($request_data['cell_formatting'][$i]) && $request_data['cell_formatting'][$i]  != ''){
                    if(!empty($request_data['cell_id'][$i]) && (int)$request_data['cell_id'][$i] != 0) {
                        $ids_query[] = (int)$request_data['cell_id'][$i];
                        $cell_id = (int)$request_data['cell_id'][$i];
                    } else {
                        $cell_id = 0;
                    }
                    $name = $request_data['cell_name'][$i];
                    $position = (int)$request_data['cell_position'][$i];
                    $id_type = (int)$request_data['cell_formatting'][$i];
                    $id_template = $this->id;
                    $fixed_value = $request_data['special_value'][$i];

                    $price_round = isset($request_data['price_round'][$i])? (int)$request_data['price_round'][$i] : 0;
                    $strip_element = isset($request_data['strip_element'][$i])? (int)$request_data['strip_element'][$i] : 0;
                    $quantity_round = isset($request_data['quantity_round'][$i])? (int)$request_data['quantity_round'][$i] : 0;
                    $no_negative = isset($request_data['no_negative'][$i])? (int)$request_data['no_negative'][$i] : 0;

                    $cell_position = $i;
                    if($cell_id == 0) {
                        Db::getInstance()->execute('INSERT INTO cells
    (id_template, name, position, id_type, fixed_value, cell_position, price_round, strip_element, quantity_round, no_negative)
VALUES (' . $id_template . ', "' . pSQL($name, true) . '", ' . $position . ', ' . $id_type . ', "' . pSQL($fixed_value, true) . '", '.$cell_position.', '.$price_round.', '.$strip_element.', '.$quantity_round.', '.$no_negative.')');
                        $ids_query[] = Db::getInstance()->Insert_ID();
                    } else {
                        Db::getInstance()->execute('UPDATE cells
SET name = "' . pSQL($name, true) . '",  position = ' . $position . ', id_type = ' . $id_type . ', fixed_value = "' . pSQL($fixed_value, true) . '", cell_position = '.$cell_position.', price_round = '.$price_round.', strip_element = '.$strip_element.', quantity_round = '.$quantity_round.', no_negative = '.$no_negative.'
WHERE id_cell = '.$cell_id);

                    }
                }
            }

            Cell::deleteById($ids_query, $id_template);
        }
    }

}