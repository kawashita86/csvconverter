<?php
class Configuration extends ObjectModel
{
    public $id;
    /** @var string Key */
    public $name;
    /** @var string Value */
    public $value;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'configuration',
        'primary' => 'id_configuration',
        'fields' => array(
            'name' => 			array('type' => self::TYPE_STRING),
            'value' => 			array('type' => self::TYPE_STRING)
        ),
    );

    /** @var array Configuration cache */
    protected static $_CONF;

    /** @var array Vars types */
    protected static $types = array();

    /**
     * Load all configuration data
     */
    public static function loadConfiguration()
    {
        self::$_CONF = array();
        $sql = 'SELECT * FROM `configuration` c';
        if (!$results = Db::getInstance()->executeS($sql))
            return;

        foreach ($results as $row) {
            self::$types[$row['name']] = 'normal';
            if (empty(self::$_CONF))
                self::$_CONF = array(
                    'global' => array(),
                    'group' => array(),
                    'shop' => array(),
                );


            self::$_CONF['global'][$row['name']] = $row['value'];
        }
    }
    /**
     * Get a single configuration value
     *
     * @param string $key Key wanted
     * @return string Value
     */
    public static function get($key)
    {
        // If conf if not initialized, try manual query
        if (!self::$_CONF)
        {
            Configuration::loadConfiguration();
            if (!self::$_CONF)
                return Db::getInstance()->getValue('SELECT `value` FROM `'._DB_PREFIX_.'configuration` WHERE `name` = "'.pSQL($key).'"');
        }

        elseif (Configuration::hasKey($key))
            return self::$_CONF['global'][$key];
        return false;
    }


    /**
     * Set TEMPORARY a single configuration value (in one language only)
     *
     * @param string $key Key wanted
     * @param mixed $values $values is an array if the configuration is multilingual, a single string else.
     * @param int $id_shop_group
     * @param int $id_shop
     */
    public static function set($key, $values)
    {
        if (!Validate::isConfigName($key))
            die(Tools::displayError());

        if (!is_array($values))
            $values = array($values);

        foreach ($values as $lang => $value)
        {
                self::$_CONF['global'][$key] = $value;
        }
    }

    public static function getGlobalValue($key)
    {
        return Configuration::get($key);
    }

    /**
     * Get several configuration values (in one language only)
     *
     * @param array $keys Keys wanted
     * @param integer $id_lang Language ID
     * @return array Values
     */
    public static function getMultiple($keys)
    {
        if (!is_array($keys))
            throw new Exception('keys var is not an array');


        $results = array();
        foreach ($keys as $key)
            $results[$key] = Configuration::get($key);
        return $results;
    }

    /**
     * Check if key exists in configuration
     *
     * @param string $key
     * @param int $id_lang
     * @param int $id_shop_group
     * @param int $id_shop
     * @return bool
     */
    public static function hasKey($key)
    {

        return isset(self::$_CONF['global']) && array_key_exists($key, self::$_CONF['global']);
    }

    /**
     * Update configuration key and value into database (automatically insert if key does not exist)
     *
     * @param string $key Key
     * @param mixed $values $values is an array if the configuration is multilingual, a single string else.
     * @param boolean $html Specify if html is authorized in value
     * @param int $id_shop_group
     * @param int $id_shop
     * @return boolean Update result
     */
    public static function updateValue($key, $values, $html = false)
    {
        if (!Validate::isConfigName($key))
            die(Tools::displayError());

        if (!is_array($values))
        {
            $is_i18n = false;
            $values = array($values);
        }
        else
            $is_i18n = true;

        $result = true;
        foreach ($values as $lang => $value)
        {
            if ($value === Configuration::get($key))
                continue;

            // If key already exists, update value
            if (Configuration::hasKey($key))
            {
                    // Update config not linked to lang
                    $result &= Db::getInstance()->execute("UPDATE `configuration` SET `value` = '".$value."' WHERE `name` = '".$key."'");
                  /*  $result &= Db::getInstance()->update('configuration', array(
                        'value' => pSQL($value, $html),
                    ), '`name` = \''.pSQL($key).'\' ', 1, true);*/
            }
            // If key does not exists, create it
            else
            {
                if (!$configID = Configuration::getIdByName($key))
                {
                    $newConfig = new Configuration();
                    $newConfig->name = $key;
                    $newConfig->value = $value;
                    $result &= $newConfig->add(true, true);
                    $configID = $newConfig->id;
                }

            }
        }

        Configuration::set($key, $values);

        return $result;
    }

    /**
     * Delete a configuration key in database (with or without language management)
     *
     * @param string $key Key to delete
     * @return boolean Deletion result
     */
    public static function deleteByName($key)
    {
        if (!Validate::isConfigName($key))
            return false;

        $result = Db::getInstance()->execute('
		DELETE FROM `'._DB_PREFIX_.'configuration`
		WHERE `name` = "'.pSQL($key).'"');

        self::$_CONF = null;

        return ($result);
    }


}