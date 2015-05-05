<?php

abstract class ObjectModelCore
{
    /**
     * List of field types
     */
    const TYPE_INT = 1;
    const TYPE_BOOL = 2;
    const TYPE_STRING = 3;
    const TYPE_FLOAT = 4;
    const TYPE_DATE = 5;
    const TYPE_HTML = 6;
    const TYPE_NOTHING = 7;


    /**
     * List of association types
     */
    const HAS_ONE = 1;
    const HAS_MANY = 2;

    /** @var integer Object id */
    public $id;

    protected static $fieldsRequiredDatabase = null;

    /** @var array tables */
    protected $webserviceParameters = array();

    /** @var  string path to image directory. Used for image deletion. */
    protected $image_dir = null;

    /** @var string file type of image files. Used for image deletion. */
    protected $image_format = 'jpg';

    /**
     * @var array Contain object definition
     * @since 1.5.0
     */
    public static $definition = array();

    /**
     * @var array Contain current object definition
     */
    protected $def;

    /**
     * @var array List of specific fields to update (all fields if null)
     */
    protected $update_fields = null;

    /**
     * @var Db An instance of the db in order to avoid calling Db::getInstance() thousands of time
     */
    protected static $db = false;

    /**
     * Returns object validation rules (fields validity)
     *
     * @param string $class Child class name for static use (optional)
     * @return array Validation rules (fields validity)
     */
    public static function getValidationRules($class = __CLASS__)
    {
        $object = new $class();
        return array(
            'required' => $object->fieldsRequired,
            'size' => $object->fieldsSize,
            'validate' => $object->fieldsValidate,
            'requiredLang' => $object->fieldsRequiredLang,
            'sizeLang' => $object->fieldsSizeLang,
            'validateLang' => $object->fieldsValidateLang,
        );
    }


    /**
     * Build object
     *
     * @param int $id Existing object id in order to load object (optional)
     * @param int $id_lang Required if object is multilingual (optional)
     * @param int $id_shop ID shop for objects with multishop on langs
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (!ObjectModel::$db)
            ObjectModel::$db = Db::getInstance();

        $this->def = ObjectModel::getDefinition($this);

        if (!Validate::isTableOrIdentifier($this->def['primary']) || !Validate::isTableOrIdentifier($this->def['table']))
            throw new Exception('Identifier or table format not valid for class '.get_class($this));

        if ($id)
        {
            // Load object from database if object id is present
            $cache_id = 'objectmodel_'.$this->def['classname'].'_'.(int)$id;
            if (!Cache::isStored($cache_id))
            {
                $sql = new DbQuery();
                $sql->from($this->def['table'], 'a');
                $sql->where('a.'.$this->def['primary'].' = '.(int)$id);

                if ($object_datas = ObjectModel::$db->getRow($sql))
                {

                    Cache::store($cache_id, $object_datas);
                }
            }
            else
                $object_datas = Cache::retrieve($cache_id);

            if ($object_datas)
            {
                $this->id = (int)$id;
                foreach ($object_datas as $key => $value)
                    if (array_key_exists($key, $this))
                        $this->{$key} = $value;
            }
        }
    }

    /**
     * Get object definition
     *
     * @param string $class Name of object
     * @param string $field Name of field if we want the definition of one field only
     * @return array
     */
    public static function getDefinition($class, $field = null)
    {
        if (is_object($class))
            $class = get_class($class);

        if ($field === null)
            $cache_id = 'objectmodel_def_'.$class;

        if ($field !== null || !Cache::isStored($cache_id))
        {
            $reflection = new ReflectionClass($class);
            $definition = $reflection->getStaticPropertyValue('definition');

            $definition['classname'] = $class;

            if ($field)
                return isset($definition['fields'][$field]) ? $definition['fields'][$field] : null;

            Cache::store($cache_id, $definition);
            return $definition;
        }

        return Cache::retrieve($cache_id);
    }


    /**
     * Specify if an ObjectModel is already in database
     *
     * @param int $id_entity
     * @param string $table
     * @return boolean
     */
    public static function existsInDatabase($id_entity, $table)
    {
        $row = Db::getInstance()->getRow('
			SELECT `id_'.$table.'` as id
			FROM `'._DB_PREFIX_.$table.'` e
			WHERE e.`id_'.$table.'` = '.(int)$id_entity
        );

        return isset($row['id']);
    }


    public function getFieldsRequiredDatabase($all = false)
    {
        return Db::getInstance()->executeS('
		SELECT id_required_field, object_name, field_name
		FROM '._DB_PREFIX_.'required_field
		'.(!$all ? 'WHERE object_name = \''.pSQL(get_class($this)).'\'' : ''));
    }

    public function cacheFieldsRequiredDatabase()
    {
        if (!is_array(self::$fieldsRequiredDatabase))
        {
            $fields = $this->getfieldsRequiredDatabase(true);
            if ($fields)
                foreach ($fields as $row)
                    self::$fieldsRequiredDatabase[$row['object_name']][(int)$row['id_required_field']] = pSQL($row['field_name']);
            else
                self::$fieldsRequiredDatabase = array();
        }
    }

    public function addFieldsRequiredDatabase($fields)
    {
        if (!is_array($fields))
            return false;

        if (!Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'required_field WHERE object_name = \''.get_class($this).'\''))
            return false;

        foreach ($fields as $field)
            if (!Db::getInstance()->insert('required_field', array('object_name' => get_class($this), 'field_name' => pSQL($field))))
                return false;
        return true;
    }

    public function clearCache($all = false)
    {
        if ($all)
            Cache::clean('objectmodel_'.$this->def['classname'].'_*');
        elseif ($this->id)
            Cache::clean('objectmodel_'.$this->def['classname'].'_'.(int)$this->id.'_*');
    }

    /**
     * Delete current object from database
     *
     * @return boolean Deletion result
     */
    public function delete()
    {
        if (!ObjectModel::$db)
            ObjectModel::$db = Db::getInstance();


        $this->clearCache();
        $result = true;

        // Database deletion
        if ($result)
            $result &= ObjectModel::$db->delete($this->def['table'], '`'.pSQL($this->def['primary']).'` = '.(int)$this->id);

        if (!$result)
            return false;

        return $result;
    }


    /**
     * Save current object to database (add or update)
     *
     * @param bool $null_values
     * @param bool $autodate
     * @return boolean Insertion result
     */
    public function save($null_values = false, $autodate = true)
    {
        return (int)$this->id > 0 ? $this->update($null_values) : $this->add($autodate, $null_values);
    }

    /**
     * Add current object to database
     *
     * @param bool $null_values
     * @param bool $autodate
     * @return boolean Insertion result
     */
    public function add($autodate = true, $null_values = false)
    {
        if (!ObjectModel::$db)
            ObjectModel::$db = Db::getInstance();


        // Automatically fill dates
        if ($autodate && property_exists($this, 'date_add'))
            $this->date_add = date('Y-m-d H:i:s');
        if ($autodate && property_exists($this, 'date_upd'))
            $this->date_upd = date('Y-m-d H:i:s');


        // Database insertion
        if (isset($this->id) && !Tools::getValue('forceIDs'))
            unset($this->id);

        if (!$result = ObjectModel::$db->insert($this->def['table'], $this->getFields(), $null_values))
            return false;

        // Get object id in database
        $this->id = ObjectModel::$db->Insert_ID();


        if (!$result)
            return false;

        return $result;
    }

    /**
     * Update current object to database
     *
     * @param bool $null_values
     * @return boolean Update result
     */
    public function update($null_values = false)
    {
        if (!ObjectModel::$db)
            ObjectModel::$db = Db::getInstance();

        $this->clearCache();

        // Automatically fill dates
        if (array_key_exists('date_upd', $this))
            $this->date_upd = date('Y-m-d H:i:s');

        // Database update
        if (!$result = ObjectModel::$db->update($this->def['table'], $this->getFields(), '`'.pSQL($this->def['primary']).'` = '.(int)$this->id, 0, $null_values))
            return false;

        return $result;
    }

    /**
     * Prepare fields for ObjectModel class (add, update)
     * All fields are verified (pSQL, intval...)
     *
     * @return array All object fields
     */
    public function getFields()
    {
        //$this->validateFields();
        $fields = $this->formatFields();

        // Ensure that we get something to insert
        if (!$fields && isset($this->id) && $this->id)
            $fields[$this->def['primary']] = $this->id;
        return $fields;
    }

    /**
     * @since 1.5.0
     * @param int $type FORMAT_COMMON or FORMAT_LANG or FORMAT_SHOP
     * @param int $id_lang If this parameter is given, only take lang fields
     * @return array
     */
    protected function formatFields()
    {
        $fields = array();

        // Set primary key in fields
        if (isset($this->id))
            $fields[$this->def['primary']] = $this->id;

        foreach ($this->def['fields'] as $field => $data)
        {

            // Get field value, if value is multilang and field is empty, use value from default lang
            $value = $this->$field;

            // Format field value
            $fields[$field] = ObjectModel::formatValue($value, $data['type']);
        }

        return $fields;
    }

    /**
     * Format a data
     *
     * @param mixed $value
     * @param int $type
     */
    public static function formatValue($value, $type, $with_quotes = false)
    {
        switch ($type)
        {
            case self::TYPE_INT :
                return (int)$value;

            case self::TYPE_BOOL :
                return (int)$value;

            case self::TYPE_FLOAT :
                return (float)str_replace(',', '.', $value);

            case self::TYPE_DATE :
                if (!$value)
                    return '0000-00-00';

                if ($with_quotes)
                    return '\''.pSQL($value).'\'';
                return pSQL($value);

            case self::TYPE_HTML :
                if ($with_quotes)
                    return '\''.pSQL($value, true).'\'';
                return pSQL($value, true);

            case self::TYPE_NOTHING :
                return $value;

            case self::TYPE_STRING :
            default :
                if ($with_quotes)
                    return '\''.pSQL($value).'\'';
                return pSQL($value);
        }
    }

    /**
     * Toggle object status in database
     *
     * @return boolean Update result
     */
    public function toggleStatus()
    {
        // Object must have a variable called 'active'
        if (!array_key_exists('active', $this))
            throw new Exception('property "active" is missing in object '.get_class($this));

        // Update only active field
        $this->setFieldsToUpdate(array('active' => true));

        // Update active status on object
        $this->active = !(int)$this->active;

        // Change status to active/inactive
        return $this->update(false);
    }

    /**
     * Set a list of specific fields to update
     * array(field1 => true, field2 => false, langfield1 => array(1 => true, 2 => false))
     *
     * @since 1.5.0
     * @param array $fields
     */
    public function setFieldsToUpdate(array $fields)
    {
        $this->update_fields = $fields;
    }


    /**
     * This method is allow to know if a entity is currently used
     * @since 1.5.0.1
     * @param string $table name of table linked to entity
     * @param bool $has_active_column true if the table has an active column
     * @return bool
     */
    public static function isCurrentlyUsed($table = null, $has_active_column = false)
    {
        if ($table === null)
            $table = self::$definition['table'];

        $query = new DbQuery();
        $query->select('`id_'.pSQL($table).'`');
        $query->from($table);
        if ($has_active_column)
            $query->where('`active` = 1');
        return (bool)Db::getInstance()->getValue($query);
    }



}