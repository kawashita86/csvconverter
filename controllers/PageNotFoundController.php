<?php


class PageNotFoundController extends Controller
{
	public $php_self = '404';
	public $page_name = 'pagenotfound';

        
        public function viewAccess() {
            parent::viewAccess();
            return true;
         }

	/**
	 * Assign template vars related to page content
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		header('HTTP/1.1 404 Not Found');
		header('Status: 404 Not Found');
                $this->smarty->assign(array(
                    'page_title' => '404',
                    'page_meta' => '404',
                    'main_page' => $this->php_self
                    ));
		$this->setTemplate('404.tpl');
        parent::initContent();

    }
}

