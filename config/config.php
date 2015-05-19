<?php

@ini_set('display_errors', 'on');

define('_SSL_PORT_', 443);
/* Improve PHP configuration to prevent issues */
ini_set('upload_max_filesize', '100M');
ini_set('default_charset', 'utf-8');
ini_set('magic_quotes_runtime', 0);

/* correct Apache charset (except if it's too late */
if (!headers_sent())
	header('Content-Type: text/html; charset=utf-8');

/* Redefine REQUEST_URI if empty (on some webservers...) */
if (!isset($_SERVER['REQUEST_URI']) || empty($_SERVER['REQUEST_URI']))
{
	if (!isset($_SERVER['SCRIPT_NAME']) && isset($_SERVER['SCRIPT_FILENAME']))
		$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_FILENAME'];
	if (isset($_SERVER['SCRIPT_NAME']))
	{
		if (basename($_SERVER['SCRIPT_NAME']) == 'index.php' && empty($_SERVER['QUERY_STRING']))
			$_SERVER['REQUEST_URI'] = dirname($_SERVER['SCRIPT_NAME']).'/';
		else
		{
			$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
			if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING']))
				$_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
		}
	}
}

/* Trying to redefine HTTP_HOST if empty (on some webservers...) */
if (!isset($_SERVER['HTTP_HOST']) || empty($_SERVER['HTTP_HOST']))
	$_SERVER['HTTP_HOST'] = @getenv('HTTP_HOST');


/* It is not safe to rely on the system's timezone settings, and this would generate a PHP Strict Standards notice. */
@date_default_timezone_set('UTC');

/*** auto load model classes ***/
require_once(dirname(__FILE__).'/settings.inc.php');
require_once(dirname(__FILE__).'/defines.inc.php');
require_once(dirname(__FILE__).'/autoload.php');


$server_host = 'http://'.$_SERVER['HTTP_HOST'];
$base_uri = str_replace('/index.php', '','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$project_name = explode('.', $_SERVER['HTTP_HOST']);

define('_SERVER_HOST_', $server_host);
define('_PROJECT_NAME_', $project_name[0]);
define('__BASE_URI__', $base_uri);
define('_BASE_URL_', $base_uri);
define('_JS_DIR_', _ROOT_DIR_.'/js/');
define('_CSS_DIR_', _ROOT_DIR_.'/css/');
define('_IMG_DIR_', _ROOT_DIR_.'/img/');

$https_link = (Tools::usingSecureMode() && _SSL_ENABLED_) ? 'https://' : 'http://';
$protocol_link = (_SSL_ENABLED_) ? 'https://' : 'http://';
$protocol_content = (isset($useSSL) AND $useSSL AND _SSL_ENABLED_) ? 'https://' : 'http://';
        
require_once(_ROOT_DIR_.'/libs/Smarty.class.php');

$smarty = new Smarty;

$smarty->force_compile = false;
if(Tools::getValue('SMARTY_DEBUG'))
    $smarty->debugging = true;
else
    $smarty->debugging = false;
$smarty->caching = false;
$smarty->cache_lifetime = 120;


?>
