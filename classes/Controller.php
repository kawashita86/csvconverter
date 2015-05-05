<?php

class Controller {

    private $controller;
    protected $template;
    public $ajax = false;
    public $controller_type;
    public static $initialized = false;
    public $smarty;
    public $page_name;
    public $page_title;
    public $php_self;
    public $page_icon;
    public $profile;
    public $access_list;
    public $ssl = false;
    /**
     * @var array list of css files
     */
    public $css_files = array();

    /**
     * @var array list of javascript files
     */
    public $js_files = array();
    public $errors = array();

    public function __construct() {
        global $smarty;
        global $useSSL;
        $this->controller = $this;
        $this->smarty = $smarty;
        
        if (_SSL_ENABLED_ && _SSL_ENABLED_EVERYWHERE_)
			$this->ssl = true;
	if (isset($useSSL))
		$this->ssl = $useSSL;
	else
		$useSSL = $this->ssl;
    }

    public function run() {
        $this->init();
        $this->postProcess();


        if ($this->viewAccess())
            $this->initContent();
        else
            $this->errors[] = 'Access denied.';

        $this->display();
    }

    public function initHeader($user){
        global $link;
        $tabs = Tab::getTabs(0);
        $current_id = Tab::getCurrentParentId();
        foreach ($tabs as $index => $tab)
        {
            if (!Tab::checkTabRights($tab['id_tab'], $this->profile['id_profile']))
            {
                unset($tabs[$index]);
                continue;
            }


            // tab[class_name] does not contains the "Controller" suffix
            $tabs[$index]['current'] = ($tab['class_name'].'Controller' == get_class($this)) || ($current_id == $tab['id_tab']);
            $tabs[$index]['icon'] = $tab['icon'];
            $tabs[$index]['href'] = $link->getPageLink($tab['class_name']);

            $sub_tabs = Tab::getTabs($tab['id_tab']);
            foreach ($sub_tabs as $index2 => $sub_tab)
            {

                if (Tab::checkTabRights($sub_tab['id_tab'], $this->profile['id_profile']) === true && (bool)$sub_tab['active'])
                    // class_name is the name of the class controller
                {
                    $sub_tabs[$index2]['href'] = $link->getPageLink($sub_tab['class_name']);
                    $sub_tabs[$index2]['current'] = ($sub_tab['class_name'].'Controller' == get_class($this)) || ($sub_tab['class_name'] == Tools::getValue('controller'));
                }
                else
                    unset($sub_tabs[$index2]);
            }

            if($tabs[$index]['class_name'] == 'MyNetworks'){
                //get server list and add it to the system
                if($this->profile['id_profile'] == _ADMIN_PROFILE_  || (isset($user['permissions']) && !empty($user['permissions']))) {
                    $server_tabs = Tab::getServerTab();
                    foreach ($server_tabs as $sub_tab) {
                       if($this->profile['id_profile'] == _ADMIN_PROFILE_  || in_array($sub_tab['name'], $user['permissions'])) {
                           $sub_tab['id_parent'] = $index;
                           $sub_tab['id_tab'] = 15;
                           $sub_tab['href'] = $link->getPageLink($sub_tab['class_name'], false, 'id=' . $sub_tab['id']);
                           $sub_tab['current'] = ($sub_tab['class_name'] . 'Controller' == get_class($this)) && Tools::getValue('id') == $sub_tab['id'];
                           $sub_tabs[] = $sub_tab;
                       }
                    }
                }
            }

            $tabs[$index]['sub_tabs'] = $sub_tabs;
        }

        $this->smarty->assign(array(
            'current_parent_id' => (int)Tab::getCurrentParentId(),
            'tabs' => $tabs,
            'permissions' => Tab::getTabs()
        ));
    }

    public function init() {

        global $useSSL, $cookie, $smarty, $protocol_link, $protocol_content, $link, $css_files, $js_files;

        if (self::$initialized)
            return;
        self::$initialized = true;
        
        if (Tools::usingSecureMode())
            $useSSL = true;
        
        $css_files = $this->css_files;
	    $js_files = $this->js_files;
        
        ob_start();

        //$link = __BASE_URI__;

        if (!empty($this->page_name))
            $page_name = $this->page_name;
        elseif (!empty($this->php_self))
            $page_name = $this->php_self;
        else {
            $page_name = Dispatcher::getInstance()->getController();
            $page_name = (preg_match('/^[0-9]/', $page_name)) ? 'page_' . $page_name : $page_name;
        }
        
        $page_title = (!empty($this->page_title)? $this->page_title : ucwords(str_replace('-', ' ', $page_name)));
        

        $protocol_link = (_SSL_ENABLED_ || Tools::usingSecureMode()) ? 'https://' : 'http://';
        $useSSL = ((isset($this->ssl) && $this->ssl && _SSL_ENABLED_) || Tools::usingSecureMode()) ? true : false;
        $protocol_content = ($useSSL) ? 'https://' : 'http://';
        $link = new Link($protocol_link, $protocol_content);
                
        if(empty($this->page_icon))
            $this->page_icon = '';
        $this->smarty->assign('request_uri', Tools::safeOutput(urldecode($_SERVER['REQUEST_URI'])));

        if (isset($_COOKIE['apiset']))
            $this->smarty->assign('apiset', $_COOKIE['apiset']);
        
       if(isset($_COOKIE['token']) || isset($_COOKIE['user'])){
            $user = Tools::jsonDecode($_COOKIE['user'], true);
           //setup the profile id
            if(!empty($user['role']))
                $this->profile = Profile::getProfileFromLDAPRole($user['role'][0]);
            if($user['role'][0] == 'Super Admin' || $user['role'][0] == 'admin' || $user['role'][0] == 'superadmin')
                $this->smarty->assign('allowbuy', 'true');
            else
                $this->smarty->assign('allowbuy', 'false');
         //   $this->smarty->assign('menu_list', $this->access_list);
            $this->smarty->assign('token', $_COOKIE['token']);

           //lets try to call the funciont
          $this->initHeader($user);
       }

        if(isset($_COOKIE['navcollapse'])){
            $this->smarty->assign('navcollapse', $_COOKIE['navcollapse']);
        }

        $this->smarty->assign(array(
            'link' => $link,
            'page_name' => $page_name,
            'project_name' => _PROJECT_NAME_,
            'page_title' => $page_title,
            'page_icon' => $this->page_icon,
            'base_dir' => __BASE_URI__,
            'current_link' => $_SERVER['PHP_SELF'] . '?controller=' . $this->page_name,
            'page_meta' => $page_name,
            'current_page_meta' => $page_name
        ));

        $assign_array = array(
            'img_dir' => _IMG_DIR_,
            'css_dir' => _CSS_DIR_,
            'js_dir' => _JS_DIR_
        );


        foreach ($assign_array as $assign_key => $assign_value)
            if (substr($assign_value, 0, 1) == '/' || $protocol_content == 'https://')
                $this->smarty->assign($assign_key, $protocol_content . Tools::getMediaServer($assign_value) . $assign_value);
            else
                $this->smarty->assign($assign_key, $assign_value);

        $this->setMedia();

        $this->smarty = $smarty;
    }

    public function display() {
        // assign css_files and js_files at the very last time
        if ((CSS_THEME_CACHE || JS_THEME_CACHE) && is_writable(_ROOT_DIR_.'/cache'))
        {
            // CSS compressor management
            if (CSS_THEME_CACHE)
                $this->css_files = Media::cccCSS($this->css_files);
            //JS compressor management
            if (JS_THEME_CACHE)
                $this->js_files = Media::cccJs($this->js_files);
        }

        $this->smarty->assign('css_files', $this->css_files);
        $this->smarty->assign('js_files', array_unique($this->js_files));
        $this->smarty->assign(array(
            'errors' => $this->errors,
        ));

        $this->displayContent();


        return true;
    }

    public function setMedia() {

    }

    public function initContent() {
        $this->process();
    }

    public function process() {
        
    }

    public function postProcess() {
        
    }

    public function preProcess() {
        
    }

    public function checkAccess() {
        return true;
    }

    public function viewAccess() {
        global $link;
        if(isset($_COOKIE['user'])){
            $given_tab = Tab::getIdFromClassName(str_replace('Controller', '', get_class($this)));
            if (Tab::checkTabRights($given_tab, $this->profile['id_profile']))
                return true;

            $tabs = Tab::getTabs();
            foreach($tabs as $tab)
                if (!Tab::checkTabRights($tab['id_tab'], $this->profile['id_profile']))
                     Tools::redirect($link->getPageLink($tab['class_name']));
        } else {
            header('Location: /index.php');
        }
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

    public function displayContent() {
        $this->smarty->display($this->template);
    }

    public function isXmlHttpRequest() {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    public static function getController($class_name, $auth = false, $ssl = false) {
        return new $class_name($auth, $ssl);
    }
    
    public function addMedia($media_uri, $css_media_type = null, $offset = null, $remove = false)
	{
		if (!is_array($media_uri))
		{
			if ($css_media_type)
				$media_uri = array($media_uri => $css_media_type);
			else
				$media_uri = array($media_uri);
		}

		$list_uri = array();
		foreach ($media_uri as $file => $media)
		{
			if (!preg_match('/^http(s?):\/\//i', $media))
			{
			$different = 0;
                $different_css = 0;
				$type = 'css';
				if (!$css_media_type)
				{
					$type = 'js';
					$file = $media;
				}

				if ($css_media_type)
			$list_uri[$file] = $media;
				else
					$list_uri[] = $file;
		}
			else
				$list_uri[$file] = $media;
		}

		if ($remove)
		{
			if ($css_media_type)
				return Controller::removeCSS($list_uri, $css_media_type);
			return Controller::removeJS($list_uri);
	}

		if ($css_media_type)
			return Controller::addCSS($list_uri, $css_media_type, $offset);
		return Controller::addJS($list_uri);
	}
        
        public function removeMedia($media_uri, $css_media_type = null)
	{
		Controller::addMedia($media_uri, $css_media_type, null, true);
	}

 
    /**
     * Add a new stylesheet in page header.
     *
     * @param mixed $css_uri Path to css file, or list of css files like this : array(array(uri => media_type), ...)
     * @param string $css_media_type
     * @return true
     */
    public function addCSS($css_uri, $css_media_type = 'all',$offset = null) {
		if (!is_array($css_uri))
			$css_uri = array($css_uri);
                
		foreach ($css_uri as $css_file => $media)
		{
			if (is_string($css_file) && strlen($css_file) > 1)
				$css_path = Media::getCSSPath($css_file, $media);
			else
				$css_path = Media::getCSSPath($media, $css_media_type);
			$key = is_array($css_path) ? key($css_path) : $css_path;
			if ($css_path && (!isset($this->css_files[$key]) || ($this->css_files[$key] != reset($css_path))))
			{
				$size = count($this->css_files);
				if ($offset === null || $offset > $size || $offset < 0 || !is_numeric($offset))
					$offset = $size;

				$this->css_files = array_merge(array_slice($this->css_files, 0, $offset), $css_path, array_slice($this->css_files, $offset));
			}
		}
    }

    	public function removeCSS($css_uri, $css_media_type = 'all')
	{
		if (!is_array($css_uri))
			$css_uri = array($css_uri);

		foreach ($css_uri as $css_file => $media)
		{
			if (is_string($css_file) && strlen($css_file) > 1)
				$css_path = Media::getCSSPath($css_file, $media);
			else
				$css_path = Media::getCSSPath($media, $css_media_type);
			if ($css_path && isset($this->css_files[key($css_path)]) && ($this->css_files[key($css_path)] == reset($css_path)))
				unset($this->css_files[key($css_path)]);
		}
	}
    /**
     * Add a new javascript file in page header.
     *
     * @param mixed $js_uri
     * @return void
     */

        public function addJS($js_uri)
	{
		if (is_array($js_uri))
			foreach ($js_uri as $js_file)
			{
				$js_path = Media::getJSPath($js_file);
				$key = is_array($js_path) ? key($js_path) : $js_path;
				if ($js_path && (!isset($this->js_file[$key]) || ($this->js_file[$key] != reset($js_path))))
					$this->js_files[] = $js_path;
			}
		else
		{
			$js_path = Media::getJSPath($js_uri);
			if ($js_path)
				$this->js_files[] = $js_path;
		}
	}

	public function removeJS($js_uri)
	{
		if (is_array($js_uri))
			foreach ($js_uri as $js_file)
			{
				$js_path = Media::getJSPath($js_file);
				if ($js_path && in_array($js_path, $this->js_files))
					unset($this->js_files[array_search($js_path,$this->js_files)]);
			}
		else
		{
			$js_path = Media::getJSPath($js_uri);
			if ($js_path)
				unset($this->js_files[array_search($js_path,$this->js_files)]);
		}
	}

    /**
     * Add a new javascript file in page header.
     *
     * @param mixed $js_uri
     * @return void
     */
    public function addJquery($version = null, $folder = null, $minifier = true) {
        $this->addJS(Media::getJqueryPath($version, $folder, $minifier));
    }

    /**
     * Add a new javascript file in page header.
     *
     * @param mixed $js_uri
     * @return void
     */
    public function addJqueryUI($component, $theme = 'base', $check_dependencies = true) {
        $ui_path = array();
        if (!is_array($component))
            $component = array($component);

        foreach ($component as $ui) {
            $ui_path = Media::getJqueryUIPath($ui, $theme, $check_dependencies);
            $this->addCSS($ui_path['css']);
            $this->addJS($ui_path['js']);
        }
    }

    /**
     * Add a new javascript file in page header.
     *
     * @param mixed $js_uri
     * @return void
     */
    public function addJqueryPlugin($name, $folder = null) {
        $plugin_path = array();
        if (is_array($name)) {
            foreach ($name as $plugin) {
                $plugin_path = Media::getJqueryPluginPath($plugin, $folder);
                $this->addJS($plugin_path['js']);
                $this->addCSS($plugin_path['css']);
            }
        } else
            $plugin_path = Media::getJqueryPluginPath($name, $folder);

        $this->addCSS($plugin_path['css']);
        $this->addJS($plugin_path['js']);
    }

}

?>
