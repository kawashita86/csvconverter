<?php

class Link
{
	/** @var boolean Rewriting activation */
	protected $allow;
	protected $url;
	public static $cache = array('page' => array());

	public $protocol_link;
	public $protocol_content;

	protected $ssl_enable;

	/**
	  * Constructor (initialization only)
	  */
	public function __construct($protocol_link = null, $protocol_content = null)
	{
		$this->allow = (int)_REWRITING_SETTINGS_;
		$this->url = $_SERVER['SCRIPT_NAME'];
		$this->protocol_link = $protocol_link;
		$this->protocol_content = $protocol_content;

		if (!defined('_BASE_URL_'))
			define('_BASE_URL_', Tools::getShopDomain(true));
		if (!defined('_BASE_URL_SSL_'))
			define('_BASE_URL_SSL_', Tools::getShopDomainSsl(true));

		$this->ssl_enable = _SSL_ENABLED_;
	}


	/**
	 * Create a simple link
	 *
	 * @param string $controller
	 * @param bool $ssl
	 * @param int $id_lang
	 * @param string|array $request
	 * @param bool $request_url_encode Use URL encode
	 *
	 * @return string Page link
	 */
	public function getPageLink($controller, $ssl = false, $request = null, $request_url_encode = false)
	{
        $p = strpos($controller, '&');
        if ($p !== false) {
            $request = substr($controller, $p + 1);
            $request_url_encode = false;
            $controller = substr($controller, 0, $p);
        }

		$controller = Tools::strReplaceFirst('.php', '', $controller);


		if (!is_array($request))
		{
			// @FIXME html_entity_decode has been added due to '&amp;' => '%3B' ...
			$request = html_entity_decode($request);
			if ($request_url_encode)
				$request = urlencode($request);
			parse_str($request, $request);
		}
		unset($request['controller']);

		$uri_path = Dispatcher::getInstance()->createUrl($controller, $request);
		$url = ($ssl && $this->ssl_enable) ? Tools::getShopDomainSsl(false) : Tools::getShopDomain(false);
		$url .= '/'.$uri_path;

		return $url;
	}

	
	public function goPage($url, $p)
	{
		return $url.($p == 1 ? '' : (!strstr($url, '?') ? '?' : '&amp;').'p='.(int)$p);
	}

	/**
	 * Get pagination link
	 *
	 * @param string $type Controller name
	 * @param int $id_object
	 * @param boolean $nb Show nb element per page attribute
	 * @param boolean $sort Show sort attribute
	 * @param boolean $pagination Show page number attribute
	 * @param boolean $array If false return an url, if true return an array
	 */
	public function getPaginationLink($type, $id_object, $nb = false, $sort = false, $pagination = false, $array = false)
	{
		// If no parameter $type, try to get it by using the controller name
		if (!$type && !$id_object)
		{
			$method_name = 'get'.Dispatcher::getInstance()->getController().'Link';
			if (method_exists($this, $method_name) && isset($_GET['id_'.Dispatcher::getInstance()->getController()]))
			{
				$type = Dispatcher::getInstance()->getController();
				$id_object = $_GET['id_'.$type];
			}
		}

		if ($type && $id_object)
			$url = $this->{'get'.$type.'Link'}($id_object, null);
		else
		{
			if (isset(Context::getContext()->controller->php_self))
				$name = Context::getContext()->controller->php_self;
			else
				$name = Dispatcher::getInstance()->getController();
			$url = $this->getPageLink($name);
		}

		$vars = array();
		$vars_nb = array('n', 'search_query');
		$vars_sort = array('orderby', 'orderway');
		$vars_pagination = array('p');

		foreach ($_GET as $k => $value)
		{
			if ($k != 'id_'.$type && $k != 'controller')
			{
				if (_REWRITING_SETTINGS_ && ($k == 'isolang' || $k == 'id_lang'))
					continue;
				$if_nb = (!$nb || ($nb && !in_array($k, $vars_nb)));
				$if_sort = (!$sort || ($sort && !in_array($k, $vars_sort)));
				$if_pagination = (!$pagination || ($pagination && !in_array($k, $vars_pagination)));
				if ($if_nb && $if_sort && $if_pagination)
				{
					if (!is_array($value))
						$vars[urlencode($k)] = $value;
					else
					{
						foreach (explode('&', http_build_query(array($k => $value), '', '&')) as $key => $val)
						{
							$data = explode('=', $val);
							$vars[urldecode($data[0])] = $data[1];
						}
					}
				}
			}
		}

		if (!$array)
			return $url.(($this->allow == 1 || $url == $this->url) ? '?' : '&').http_build_query($vars, '', '&');
		$vars['requestUrl'] = $url;

		if ($type && $id_object)
			$vars['id_'.$type] = (is_object($id_object) ? (int)$id_object->id : (int)$id_object);
			
		if (!$this->allow == 1)
			$vars['controller'] = Dispatcher::getInstance()->getController();
		return $vars;
	}

	public function addSortDetails($url, $orderby, $orderway)
	{
		return $url.(!strstr($url, '?') ? '?' : '&').'orderby='.urlencode($orderby).'&orderway='.urlencode($orderway);
	}

}

