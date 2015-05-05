<?php

define('_MODE_DEV_', false);
$currentDir = dirname(__FILE__);
define('_ROOT_DIR_', realpath($currentDir.'/..'));
define('_CLASS_DIR_',  _ROOT_DIR_.'/classes/');
define('_CONTROLLER_DIR_', _ROOT_DIR_.'/controllers/');
define('_FRONT_CONTROLLER_DIR_', _ROOT_DIR_.'/controllers/');
define('_STORAGE_DIR_', _ROOT_DIR_.'/storage/');
define('_TOOL_DIR_', _ROOT_DIR_.'/tools/');
define('_SWIFT_DIR_', _TOOL_DIR_.'swift/');
define('_MEDIA_SERVER_1_', '');
define('_SSL_ENABLED_', false);
define('_SSL_ENABLED_EVERYWHERE_', false);
define('_REWRITING_SETTINGS_', false);
define('_CACHEFS_DIRECTORY_', _ROOT_DIR_.'/cache/cachefs/');
/* SQL Replication management */
define('_PS_USE_SQL_SLAVE_', 0);
define('_ADMIN_PROFILE_', 1);


if (!defined('PHP_VERSION_ID'))
{
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if (!defined('_PS_MAGIC_QUOTES_GPC_'))
	define('_PS_MAGIC_QUOTES_GPC_', get_magic_quotes_gpc());
define('_CAN_LOAD_FILES_', 1);

define('_LOG_PERSIST_ON_DB_', false);
define('_LOG_FILE_MIN_SEVERITY_', 7);
define('_LOG_FILE_PATH_TEST_', _ROOT_DIR_."/log/");

define('_LOGS_BY_EMAIL_', 1);

define('_DB_PATH_', _STORAGE_DIR_.'/csvdb.db');
define('_DB_SERVER_', _STORAGE_DIR_.'/csvdb.db');
define('_DB_NAME_', _STORAGE_DIR_.'/csvdb.db');