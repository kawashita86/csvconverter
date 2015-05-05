<?php

class Dispatcher
{
	/**
	 * List of available front controllers types
	 */
	const FC_FRONT = 1;

	/**
	 * @var Dispatcher
	 */
	public static $instance = null;

	/**
	 * @var array List of default routes
	 */
	public $default_routes = array(
		'layered_rule' => array(
			'controller' =>	'category',
			'rule' =>		'{id}-{rewrite}{/:selected_filters}',
			'keywords' => array(
				'id' =>				array('regexp' => '[0-9]+', 'param' => 'id_category'),
				/* Selected filters is used by the module blocklayered */
				'selected_filters' =>		array('regexp' => '.*', 'param' => 'selected_filters'),
				'rewrite' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				'meta_keywords' =>	array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				'meta_title' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			),
		),
		'category_rule' => array(
			'controller' =>	'category',
			'rule' =>		'{id}-{rewrite}',
			'keywords' => array(
				'id' =>				array('regexp' => '[0-9]+', 'param' => 'id_category'),
				'rewrite' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				'meta_keywords' =>	array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				'meta_title' =>		array('regexp' => '[_a-zA-Z0-9-\pL]*'),
			),
		),
	);

	/**
	 * @var bool If true, use routes to build URL (mod rewrite must be activated)
	 */
	protected $use_routes = false;
		
	/**
	 * @var array List of loaded routes
	 */
	protected $routes = array();

	/**
	 * @var string Current controller name
	 */
	protected $controller;

	/**
	 * @var string Current request uri
	 */
	protected $request_uri;

	/**
	 * @var array Store empty route (a route with an empty rule)
	 */
	protected $empty_route;

	/**
	 * @var string Set default controller, which will be used if http parameter 'controller' is empty
	 */
	protected $default_controller = 'index';

	/**
	 * @var string Controller to use if found controller doesn't exist
	 */
	protected $controller_not_found = 'pagenotfound';

	/**
	 * @var string Front controller to use
	 */
	protected $front_controller = self::FC_FRONT;

	/**
	 * Get current instance of dispatcher (singleton)
	 *
	 * @return Dispatcher
	 */
	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new Dispatcher();
		return self::$instance;
	}

	/**
	 * Need to be instancied from getInstance() method
	 */
	protected function __construct()
	{
		$this->use_routes = false;


		$this->front_controller = self::FC_FRONT;
		$this->controller_not_found = 'pagenotfound';
		$this->default_controller = 'index';
		
		$this->setRequestUri();

		$this->loadRoutes();
	}

	/**
	 * Find the controller and instantiate it
	 */
	public function dispatch()
	{
		$controller_class = '';

		// Get current controller
		$this->getController();
		if (!$this->controller)
			$this->controller = $this->default_controller;
		// Dispatch with right front controller
		switch ($this->front_controller)
		{
			// Dispatch front office controller
			case self::FC_FRONT :
				$controllers = Dispatcher::getControllers(array(_FRONT_CONTROLLER_DIR_));

				$controllers['index'] = 'IndexController';
				if (isset($controllers['auth']))
					$controllers['authentication'] = $controllers['auth'];

				if (!isset($controllers[strtolower($this->controller)]))
					$this->controller = $this->controller_not_found;
				$controller_class = $controllers[strtolower($this->controller)];
			break;
			default :
				throw new Exception('Bad front controller chosen');
		}

		// Instantiate controller
		try
		{
			// Loading controller
			$controller = Controller::getController($controller_class);

			// Running controller
			$controller->run();
		}
		catch (Exception $e)
		{
			echo $e->getTraceAsString();
		}
	}
	
	/**
	 * Set request uri and iso lang
	 */
	protected function setRequestUri()
	{
		// Get request uri (HTTP_X_REWRITE_URL is used by IIS)
		if (isset($_SERVER['REQUEST_URI']))
			$this->request_uri = $_SERVER['REQUEST_URI'];
		else if (isset($_SERVER['HTTP_X_REWRITE_URL']))
			$this->request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
		$this->request_uri = rawurldecode($this->request_uri);
		
		$this->request_uri = preg_replace('#^'.preg_quote('vps.gplugin.com', '#').'#i', '/', $this->request_uri);
	}

	/**
	 * Load default routes group by languages
	 */
	protected function loadRoutes()
	{

		// Load the custom routes prior the defaults to avoid infinite loops
		if ($this->use_routes)
		{

			// Set default empty route if no empty route (that's weird I know)
			if (!$this->empty_route)
				$this->empty_route = array(
					'routeID' =>	'index',
					'rule' =>		'',
					'controller' =>	'index',
				);

			// Load custom routes
			foreach ($this->default_routes as $route_id => $route_data)
				if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id))
						$this->addRoute(
							$route_id,
							$custom_route,
							$route_data['controller'],
							$route_data['keywords'],
							isset($route_data['params']) ? $route_data['params'] : array()
						);
		}

		// Set default routes
			foreach ($this->default_routes as $id => $route)
				$this->addRoute(
					$id,
					$route['rule'],
					$route['controller'],
					$route['keywords'],
					isset($route['params']) ? $route['params'] : array()
				);
	}

	/**
	 *
	 * @param string $route_id Name of the route (need to be uniq, a second route with same name will override the first)
	 * @param string $rule Url rule
	 * @param string $controller Controller to call if request uri match the rule
	 * @param int $id_lang
	 */
	public function addRoute($route_id, $rule, $controller, array $keywords = array(), array $params = array())
	{
		
		$regexp = preg_quote($rule, '#');
		if ($keywords)
		{
			$transform_keywords = array();
			preg_match_all('#\\\{(([^{}]+)\\\:)?('.implode('|', array_keys($keywords)).')(\\\:([^{}]+))?\\\}#', $regexp, $m);
			for ($i = 0, $total = count($m[0]); $i < $total; $i++)
			{
				$prepend = $m[2][$i];
				$keyword = $m[3][$i];
				$append = $m[5][$i];
				$transform_keywords[$keyword] = array(
					'required' =>	isset($keywords[$keyword]['param']),
					'prepend' =>	stripslashes($prepend),
					'append' =>		stripslashes($append),
				);

				$prepend_regexp = $append_regexp = '';
				if ($prepend || $append)
				{
					$prepend_regexp = '('.preg_quote($prepend);
					$append_regexp = preg_quote($append).')?';
				}

				if (isset($keywords[$keyword]['param']))
					$regexp = str_replace($m[0][$i], $prepend_regexp.'(?P<'.$keywords[$keyword]['param'].'>'.$keywords[$keyword]['regexp'].')'.$append_regexp, $regexp);
				else
					$regexp = str_replace($m[0][$i], $prepend_regexp.'('.$keywords[$keyword]['regexp'].')'.$append_regexp, $regexp);

			}
			$keywords = $transform_keywords;
		}

		$regexp = '#^/'.$regexp.'(\?.*)?$#u';
		
		$this->routes[$route_id] = array(
			'rule' =>		$rule,
			'regexp' =>		$regexp,
			'controller' =>	$controller,
			'keywords' =>	$keywords,
			'params' =>		$params,
		);
	}

	/**
	 * Check if a route exists
	 *
	 * @param string $route_id
	 * @param int $id_lang
	 * @return bool
	 */
	public function hasRoute($route_id)
	{
		
		return isset($this->routes) && isset($this->routes[$route_id]);
	}

	/**
	 * Check if a keyword is written in a route rule
	 *
	 * @param string $route_id
	 * @param int $id_lang
	 * @param string $keyword
	 * @return bool
	 */
	public function hasKeyword($route_id, $keyword)
	{
		return preg_match('#\{([^{}]+:)?'.preg_quote($keyword, '#').'(:[^{}])?\}#', $this->routes[$route_id]['rule']);
	}

	/**
	 * Check if a route rule contain all required keywords of default route definition
	 *
	 * @param string $route_id
	 * @param string $rule Rule to verify
	 * @param array $errors List of missing keywords
	 */
	public function validateRoute($route_id, $rule, &$errors = array())
	{
		$errors = array();
		if (!isset($this->default_routes[$route_id]))
			return false;

		foreach ($this->default_routes[$route_id]['keywords'] as $keyword => $data)
			if (isset($data['param']) && !preg_match('#\{([^{}]+:)?'.$keyword.'(:[^{}])?\}#', $rule))
				$errors[] = $keyword;

		return (count($errors)) ? false : true;
	}

	/**
	 * Create an url from
	 *
	 * @param string $route_id Name the route
	 * @param int $id_lang
	 * @param array $params
	 * @param bool $use_routes If false, don't use to create this url
	 * @param string $anchor Optional anchor to add at the end of this url
	 */
	public function createUrl($route_id,array $params = array(), $force_routes = false, $anchor = '')
	{

		if (!isset($this->routes[$route_id]))
		{
			$query = http_build_query($params, '', '&');
			$index_link = $this->use_routes ? '' : 'index.php';
			return ($route_id == 'index') ? $index_link.(($query) ? '?'.$query : '') : 'index.php?controller='.$route_id.(($query) ? '&'.$query : '').$anchor;
		}
		$route = $this->routes[$route_id];

		// Check required fields
		$query_params = isset($route['params']) ? $route['params'] : array();
		foreach ($route['keywords'] as $key => $data)
		{
			if (!$data['required'])
				continue;

			if (!array_key_exists($key, $params))
				die('Dispatcher::createUrl() miss required parameter "'.$key.'" for route "'.$route_id.'"');
			if (isset($this->default_routes[$route_id]))
				$query_params[$this->default_routes[$route_id]['keywords'][$key]['param']] = $params[$key];
		}

		// Build an url which match a route
		if ($this->use_routes || $force_routes)
		{
			$url = $route['rule'];
			$add_param = array();
			foreach ($params as $key => $value)
			{
				if (!isset($route['keywords'][$key]))
				{
					if (!isset($this->default_routes[$route_id]['keywords'][$key]))
						$add_param[$key] = $value;
				}
				else
				{
					if ($params[$key])
						$replace = $route['keywords'][$key]['prepend'].$params[$key].$route['keywords'][$key]['append'];
					else
						$replace = '';
					$url = preg_replace('#\{([^{}]+:)?'.$key.'(:[^{}])?\}#', $replace, $url);
				}
			}
			$url = preg_replace('#\{([^{}]+:)?[a-z0-9_]+?(:[^{}])?\}#', '', $url);
			if (count($add_param))
				$url .= '?'.http_build_query($add_param, '', '&');
		}
		// Build a classic url index.php?controller=foo&...
		else
		{
			$add_params = array();
			foreach ($params as $key => $value)
				if (!isset($route['keywords'][$key]) && !isset($this->default_routes[$route_id]['keywords'][$key]))
					$add_params[$key] = $value;

			if (!empty($route['controller']))
				$query_params['controller'] = $route['controller'];
			$query = http_build_query(array_merge($add_params, $query_params), '', '&');
			$url = 'index.php?'.$query;
			
		}

		return $url.$anchor;
	}

	/**
	 * Retrieve the controller from url or request uri if routes are activated
	 *
	 * @return string
	 */
	public function getController()
	{		
		if ($this->controller)
		{
			$_GET['controller'] = $this->controller;
			return $this->controller;
		}
	
		$controller = Tools::getValue('controller');
	
		if (isset($controller) && is_string($controller) && preg_match('/^([0-9a-z_-]+)\?(.*)=(.*)$/Ui', $controller, $m))
		{
			$controller = $m[1];
			if (isset($_GET['controller']))
				$_GET[$m[2]] = $m[3];
			else if (isset($_POST['controller']))
				$_POST[$m[2]] = $m[3];
		}
	
		// Use routes ? (for url rewriting)
		if ($this->use_routes && !$controller)
		{
			if (!$this->request_uri)
				return strtolower($this->controller_not_found);
			$controller = $this->controller_not_found;

			// Add empty route as last route to prevent this greedy regexp to match request uri before right time
			if ($this->empty_route)
				$this->addRoute($this->empty_route['routeID'], $this->empty_route['rule'], $this->empty_route['controller']);

			if (isset($this->routes))
				foreach ($this->routes as $route)
					if (preg_match($route['regexp'], $this->request_uri, $m))
					{
						// Route found ! Now fill $_GET with parameters of uri
						foreach ($m as $k => $v)
							if (!is_numeric($k))
								$_GET[$k] = $v;
	
						$controller = $route['controller'] ? $route['controller'] : $_GET['controller'];
						if (!empty($route['params']))
							foreach ($route['params'] as $k => $v)
								$_GET[$k] = $v;
						break;
					}

			if ($controller == 'index' || $this->request_uri == '/index.php')
				$controller = $this->default_controller;
			$this->controller = $controller;
		}
		// Default mode, take controller from url
		else
			$this->controller = $controller;

		$this->controller = str_replace('-', '', $this->controller);
		$_GET['controller'] = $this->controller;
		return $this->controller;
	}

	/**
	 * Get list of all available FO controllers
	 *
	 * @var mixed $dirs
	 * @return array
	 */
	public static function getControllers($dirs)
	{
		if (!is_array($dirs))
			$dirs = array($dirs);

		$controllers = array();
		foreach ($dirs as $dir)
			$controllers = array_merge($controllers, Dispatcher::getControllersInDirectory($dir));
		return $controllers;
	}

	/**
	 * Get list of available controllers from the specified dir
	 *
	 * @param string dir directory to scan (recursively)
	 * @return array
	 */
	public static function getControllersInDirectory($dir)
	{
		if (!is_dir($dir))
			return array();

		$controllers = array();
		$controller_files = scandir($dir);
		foreach ($controller_files as $controller_filename)
		{
			if ($controller_filename[0] != '.')
			{
				if (!strpos($controller_filename, '.php') && is_dir($dir.$controller_filename))
					$controllers += Dispatcher::getControllersInDirectory($dir.$controller_filename.DIRECTORY_SEPARATOR);
				elseif ($controller_filename != 'index.php')
				{
					$key = str_replace(array('controller.php', '.php'), '', strtolower($controller_filename));
					$controllers[$key] = basename($controller_filename, '.php');
				}
			}
		}

		return $controllers;
	}
}
