<?php

class Tools {

    protected static $file_exists_cache = array();
    protected static $_forceCompile;
    protected static $_caching;

    public static function getValue($key, $default_value = false) {
        if (!isset($key) || empty($key) || !is_string($key))
            return false;
        $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));

        if (is_string($ret) === true)
            $ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
        return !is_string($ret) ? $ret : stripslashes($ret);
    }

    public static function getIsset($key) {
        if (!isset($key) || empty($key) || !is_string($key))
            return false;
        return isset($_POST[$key]) ? true : (isset($_GET[$key]) ? true : false);
    }

    public static function isSubmit($submit) {
        return (
                isset($_POST[$submit]) || isset($_POST[$submit . '_x']) || isset($_POST[$submit . '_y']) || isset($_GET[$submit]) || isset($_GET[$submit . '_x']) || isset($_GET[$submit . '_y'])
                );
    }

    public static function isEmail($email) {
        return !empty($email) && preg_match('/^[a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]+[.a-z\p{L}0-9!#$%&\'*+\/=?^`{}|~_-]*@[a-z\p{L}0-9]+[._a-z\p{L}0-9-]*\.[a-z0-9]+$/ui', $email);
    }

    public static function isPasswd($passwd, $size = 5) {
        return (Tools::strlen($passwd) >= $size && Tools::strlen($passwd) < 255);
    }

    public static function strlen($str, $encoding = 'UTF-8') {
        if (is_array($str))
            return false;
        $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        if (function_exists('mb_strlen'))
            return mb_strlen($str, $encoding);
        return strlen($str);
    }

    /**
     * Display a var dump in firebug console
     *
     * @param object $object Object to display
     */
    public static function fd($object, $type = 'log')
    {
        $types = array('log', 'debug', 'info', 'warn', 'error', 'assert');

        if(!in_array($type, $types))
            $type = 'log';

        echo '
			<script type="text/javascript">
				console.'.$type.'('.Tools::jsonEncode($object).');
			</script>
		';
    }

    /**
     * ALIAS OF dieObject() - Display an error with detailed object but don't stop the execution
     *
     * @param object $object Object to display
     */
    public static function p($object)
    {
        return (Tools::dieObject($object, false));
    }

    /**
     * ALIAS OF dieObject() - Display an error with detailed object
     *
     * @param object $object Object to display
     */
    public static function d($object, $kill = true)
    {
        return (Tools::dieObject($object, $kill));
    }

    /**
     * Display an error with detailed object
     *
     * @param mixed $object
     * @param boolean $kill
     * @return $object if $kill = false;
     */
    public static function dieObject($object, $kill = true)
    {
        echo '<xmp style="text-align: left;">';
        print_r($object);
        echo '</xmp><br />';

        if ($kill)
            die('END');

        return $object;
    }





    /**
     * Encrypt password
     *
     * @param string $passwd String to encrypt
     */
    public static function encrypt($passwd) {
        return md5(_COOKIE_KEY_ . $passwd);
    }

    /**
     * Encrypt data string
     *
     * @param string $data String to encrypt
     */
    public static function encryptIV($data)
    {
        return md5(_COOKIE_IV_.$data);
    }

    public static function str_replace_once($needle, $replace, $haystack) {
        $pos = strpos($haystack, $needle);
        if ($pos === false)
            return $haystack;
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    public static function usingSecureMode() {
                if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == _SSL_PORT_)
                    return true;
		if (isset($_SERVER['HTTPS']))
			return in_array(Tools::strtolower($_SERVER['HTTPS']), array(1, 'on'));
		// $_SERVER['SSL'] exists only in some specific configuration
		if (isset($_SERVER['SSL']))
			return in_array(Tools::strtolower($_SERVER['SSL']), array(1, 'on'));
		// $_SERVER['REDIRECT_HTTPS'] exists only in some specific configuration
		if (isset($_SERVER['REDIRECT_HTTPS']))
			return in_array(Tools::strtolower($_SERVER['REDIRECT_HTTPS']), array(1, 'on'));
		if (isset($_SERVER['HTTP_SSL']))
			return in_array(Tools::strtolower($_SERVER['HTTP_SSL']), array(1, 'on'));

		return false;
    }

    /**
     * Get the current url prefix protocol (https/http)
     *
     * @return string protocol
     */
    public static function getCurrentUrlProtocolPrefix() {
        if (Tools::usingSecureMode()){

            return 'https://';
        }
        else
            return 'http://';
    }

	protected static $_cache_nb_media_servers = null;

	public static function getMediaServer($filename)
	{
		if (self::$_cache_nb_media_servers === null)
		{
			if (_MEDIA_SERVER_1_ == '')
				self::$_cache_nb_media_servers = 0;
			elseif (_MEDIA_SERVER_2_ == '')
				self::$_cache_nb_media_servers = 1;
			elseif (_MEDIA_SERVER_3_ == '')
				self::$_cache_nb_media_servers = 2;
			else
				self::$_cache_nb_media_servers = 3;
		}

		if (self::$_cache_nb_media_servers && ($id_media_server = (abs(crc32($filename)) % self::$_cache_nb_media_servers + 1)))
			return constant('_MEDIA_SERVER_'.$id_media_server.'_');
		return Tools::getHttpHost();
	}

    /**
     * getHttpHost return the <b>current</b> host used, with the protocol (http or https) if $http is true
     * This function should not be used to choose http or https domain name.
     * Use Tools::getShopDomain() or Tools::getShopDomainSsl instead
     *
     * @param boolean $http
     * @param boolean $entities
     * @return string host
     */
    public static function getHttpHost($http = false, $entities = false, $ignore_port = false) {
		$host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);
		if ($ignore_port && $pos = strpos($host, ':'))
			$host = substr($host, 0, $pos);
		if ($entities)
			$host = htmlspecialchars($host, ENT_COMPAT, 'UTF-8');
		if ($http)
			$host = (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$host;
		return $host;
    }

    /**
     * Sanitize a string
     *
     * @param string $string String to sanitize
     * @param boolean $full String contains HTML or not (optional)
     * @return string Sanitized string
     */
    public static function safeOutput($string, $html = false) {
        if (!$html)
            $string = strip_tags($string);
        return @Tools::htmlentitiesUTF8($string, ENT_QUOTES);
    }

    public static function htmlentitiesUTF8($string, $type = ENT_QUOTES) {
        if (is_array($string))
            return array_map(array('Tools', 'htmlentitiesUTF8'), $string);
        return htmlentities($string, $type, 'utf-8');
    }
    
    	public static function strtolower($str)
	{
		if (is_array($str))
			return false;
		if (function_exists('mb_strtolower'))
			return mb_strtolower($str, 'utf-8');
		return strtolower($str);
	}

    	/**
	 * jsonDecode convert json string to php array / object
	 *
	 * @param string $json
	 * @param boolean $assoc  if true, convert to associativ array
	 * @return array
	 */
	public static function jsonDecode($json, $assoc = false)
	{
		if (function_exists('json_decode'))
			return json_decode($json, $assoc);
		else
		{
			include_once(_TOOL_DIR_.'json/json.php');
			$pear_json = new Services_JSON(($assoc) ? SERVICES_JSON_LOOSE_TYPE : 0);
			return $pear_json->decode($json);
		}
	}

	/**
	 * Convert an array to json string
	 *
	 * @param array $data
	 * @return string json
	 */
	public static function jsonEncode($data)
	{
		if (function_exists('json_encode'))
			return json_encode($data);
		else
		{
			include_once(_TOOL_DIR_.'json/json.php');
			$pear_json = new Services_JSON();
			return $pear_json->encode($data);
		}
	}
        
        public static function getShopDomain($http = false, $entities = false)
	{		
		if (!$domain = __BASE_URI__)
			$domain = Tools::getHttpHost();
		if ($entities)
			$domain = htmlspecialchars($domain, ENT_COMPAT, 'UTF-8');
		if ($http)
			$domain = 'http://'.$domain;
		return $domain;
	}

	/**
	 * getShopDomainSsl returns domain name according to configuration and depending on ssl activation
	 *
	 * @param boolean $http if true, return domain name with protocol
	 * @param boolean $entities if true,
	 * @return string domain
	 */
	public static function getShopDomainSsl($http = false, $entities = false)
	{
		if (!$domain = __BASE_URI__)
			$domain = Tools::getHttpHost();
		if ($entities)
			$domain = htmlspecialchars($domain, ENT_COMPAT, 'UTF-8');
		if ($http)
			$domain = (_SSL_ENABLED_ ? 'https://' : 'http://').$domain;
		return $domain;
	}
        
        	public static function strReplaceFirst($search, $replace, $subject, $cur = 0)
	{
		return (strpos($subject, $search, $cur))?substr_replace($subject, $replace, (int)strpos($subject, $search, $cur), strlen($search)):$subject;
	}

	public static function bytesToSize($bytes){
		if($bytes == 0) return '0 B';
		$k = 1000;
		$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$i = floor(log($bytes) / log($k));
		return ((int)round($bytes / pow($k, $i))). ' ' .$sizes[$i];
	}

    public static function getUserIp(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) &&$_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    /**
     * Display an error according to an error code
     *
     * @param string $string Error message
     * @param boolean $htmlentities By default at true for parsing error message with htmlentities
     */
    public static function displayError($string = 'Fatal error', $htmlentities = true)
    {
        global $_ERRORS;

        if (defined('_PS_MODE_DEV_') && _PS_MODE_DEV_ && $string == 'Fatal error')
            return ('<pre>'.print_r(debug_backtrace(), true).'</pre>');
        if (!is_array($_ERRORS))
            return str_replace('"', '&quot;', $string);
        $key = md5(str_replace('\'', '\\\'', $string));
        $str = (isset($_ERRORS) && is_array($_ERRORS) && array_key_exists($key, $_ERRORS)) ? ($htmlentities ? htmlentities($_ERRORS[$key], ENT_COMPAT, 'UTF-8') : $_ERRORS[$key]) : $string;
        return str_replace('"', '&quot;', stripslashes($str));
    }

    public static function replaceByAbsoluteURL($matches)
    {
        global $current_css_file;
        $protocol_link = Tools::getCurrentUrlProtocolPrefix();
        if (array_key_exists(1, $matches) && array_key_exists(2, $matches))
        {
            if (!preg_match('/^(?:https?:)?\/\//iUs', $matches[2]))
            {
                $tmp = dirname($current_css_file).'/'.$matches[2];
                return $matches[1].$protocol_link.Tools::getMediaServer($tmp).$tmp;
            }
            else
                return $matches[0];
        }
        return false;
    }

    /**
     * Redirect user to another page
     *
     * @param string $url Desired URL
     * @param string $baseUri Base URI (optional)
     * @param Link $link
     * @param string|array $headers A list of headers to send before redirection
     */
    public static function redirect($url, $base_uri = __BASE_URI__, Link $link = null, $headers = null)
    {
        if (!$link)
            global $link;

        if (strpos($url, 'http://') === false && strpos($url, 'https://') === false && $link)
        {
            if (strpos($url, $base_uri) === 0)
                $url = substr($url, strlen($base_uri));
            if (strpos($url, 'index.php?controller=') !== false && strpos($url, 'index.php/') == 0)
            {
                $url = substr($url, strlen('index.php?controller='));
            }

            $explode = explode('?', $url);
            // don't use ssl if url is home page
            // used when logout for example
            $use_ssl = !empty($url);
            $url = $link->getPageLink($explode[0], $use_ssl);
            if (isset($explode[1]))
                $url .= '?'.$explode[1];
        }

        // Send additional headers
        if ($headers)
        {
            if (!is_array($headers))
                $headers = array($headers);

            foreach ($headers as $header)
                header($header);
        }

        header('Location: '.$url);
        exit;
    }

    /**
     * Convert \n and \r\n and \r to <br />
     *
     * @param string $string String to transform
     * @return string New string
     */
    public static function nl2br($str)
    {
        return str_replace(array("\r\n", "\r", "\n"), '<br />', $str);
    }

    /**
     * Delete unicode class from regular expression patterns
     * @param string $pattern
     * @return pattern
     */
    public static function cleanNonUnicodeSupport($pattern)
    {
        if (!defined('PREG_BAD_UTF8_OFFSET'))
            return $pattern;
        return preg_replace('/\\\[px]\{[a-z]\}{1,2}|(\/[a-z]*)u([a-z]*)$/i', "$1$2", $pattern);
    }

}

?>
