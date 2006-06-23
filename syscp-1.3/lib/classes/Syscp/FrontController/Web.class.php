<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

Syscp::uses('Syscp.FrontController');

/**
 * This class represents the FrontController implementation regarding web requests.
 *
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController
 */
class Syscp_FrontController_Web extends Syscp_FrontController implements Syscp_FrontController_Web_Interface
{
	const DEFAULT_THEME    = 'default';
	const DEFAULT_LANGUAGE = 'English';

	const ERROR_DEFAULT_MODULE_MISSING = 'The default module %s%s can not be found!';
	const ERROR_LOGIN_MODULE_MISSING   = 'The login module %s can not be found!';

	const LOGIN_VENDOR     = 'SysCP';
	const LOGIN_AREA       = 'login';
	const LOGIN_MODULE     = 'login';
	const LOGIN_ACTION     = 'login';

	const ADMIN_AREA = 'admin';
	const CUSTOMER_AREA = 'customer';

	const DEFAULT_MODULE   = 'index';
	const DEFAULT_ACTION   = 'index';
	const DEFAULT_VENDOR   = 'SysCP';

	private $theme    = self::DEFAULT_THEME;
	private $language = self::DEFAULT_LANGUAGE;

	private $LogHandler;
	private $DatabaseHandler;
	private $ConfigHandler;
	private $SessionHandler;
	private $HookHandler;
	private $IdnaHandler;
	private $L10nHandler;
	private $TemplateHandler;

	private $User = null;

	public function __construct()
	{
		// Setup the generic exception handler for this controller.
		Syscp::uses('Syscp.FrontController.Web.ExceptionHandler');
		Syscp_FrontController_Web_ExceptionHandler::initialize();

		// Bootstrap the controller, settings all needed defines and
		// load dependency classes, instanciate logger etc, load core
		// configs of SysCP
		/**@#+
		 * Define SysCP Pathes
		 * This works only for calls made directly to the main directory of SysCP
		 */
		define('SYSCP_PATH_BASE',  dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/'   );
		define('SYSCP_PATH_CLASS', sprintf('%slib/classes/', SYSCP_PATH_BASE) );
		define('SYSCP_PATH_PEAR',  sprintf('%slib/classes/PEAR', SYSCP_PATH_BASE) );
		define('SYSCP_PATH_LIB',   sprintf('%slib/', SYSCP_PATH_BASE ) );
		/**@#-*/

		/**
		 * Smarty Directory
		 */
		define('SMARTY_DIR', sprintf('%sSmarty/libs/', SYSCP_PATH_CLASS) );

		/**@#+
		 * Change the INCLUDE_PATH this is needed to have PEAR work properly.
		 */
		ini_set('include_path', SYSCP_PATH_PEAR . PATH_SEPARATOR . ini_get('include_path'));
		/**@#-*/


		/**@#+
		 * Require SysCP Dependency Classes
		 */
		require_once SYSCP_PATH_BASE.'etc/tables.inc.php';
		require_once SYSCP_PATH_BASE.'lib/functions.php';
		/**@#-*/

		Syscp::uses('Syscp.BaseHook');
		Syscp::uses('Syscp.Handler.Interface');
		// Init the LogHandler
		Syscp::uses('Syscp.Handler.Log');
		$this->LogHandler = new Syscp_Handler_Log();
		$this->LogHandler->initialize(array('logdir'=>SYSCP_PATH_BASE.'logs/'));
	}

	public function initialize($params = array())
	{
		$this->initHandler();
		$this->initModules();

		// The L10n Handler
		Syscp::uses('Syscp.Handler.L10n');
		$params = array('DatabaseHandler' => $this->DatabaseHandler,
		                'defaultLanguage' => self::DEFAULT_LANGUAGE,
		                'languageFilelist' => $this->languageConfig);
		$this->L10nHandler = new Syscp_Handler_L10n();
		$this->L10nHandler->initialize($params);

		// The Template Handler
		Syscp::uses('Syscp.Handler.Template');
		$this->TemplateHandler = new Syscp_Handler_Template();
		$params = array('Controller'   => $this,
		                'L10nHandler'  => $this->L10nHandler,
		                'defaultTheme' => self::DEFAULT_THEME,
		                'themeDir'     => SYSCP_PATH_LIB.'themes/',
		                'compileDir'   => SYSCP_PATH_BASE.'cache/');
		$this->TemplateHandler->initialize($params);

		// The HookHandler
		Syscp::uses('Syscp.Handler.Hook');
		$this->HookHandler = new Syscp_Handler_Hook();
		$params = array('hookConfig'      => $this->hookConfig,
		                'DatabaseHandler' => $this->DatabaseHandler,
		                'ConfigHandler'   => $this->ConfigHandler,
		                'TemplateHandler' => $this->TemplateHandler,
		                'LogHandler'      => $this->LogHandler );
		$this->HookHandler->initialize($params);

		// Propagade some Handlers into the Template Handler
		$this->TemplateHandler->set('Config', $this->ConfigHandler);

		// We start with some version checks here, the ConfigHandler has
		// been loaded, and knows everything we need.
		if( $this->ConfigHandler->get('panel.version') != $this->ConfigHandler->get('env.version') )
		{
			$error = 'You\'ll have to upgrade your SysCP installation. <br/>'
			       . 'Please call "syscp upgrade" in your bash!<br/>'
			       . 'Installed Version: '.$this->ConfigHandler->get('env.version').'<br/>'
			       . 'Database Version: '.$this->ConfigHandler->get('panel.version').'<br/>';
			$this->TemplateHandler->showError($error);
		}

		/**
		 * @todo Session handling is still itchy, we depend here on ConfigHandler, normally
		 *       PHP should resolve all Session handling things by itself using SessionHandler
		 */
		// Check if we have a valid session, if so, we try to load the
		// User from the database, otherwise we do nothing
		if($this->SessionHandler->isValid($this->ConfigHandler->get('env.s')))
		{
			// start the session
			session_start();

			// Check if we have all needed session things set
			if(isset($_SESSION['language']) && isset($_SESSION['theme']) &&
			   isset($_SESSION['userid'])   && isset($_SESSION['adminsession']))
			{
				// set some user preferences
				$this->language = $_SESSION['language'];
				$this->theme    = $_SESSION['theme'];
				// and now try to load the user, to load the user we need
				// to build a basic query and decide which values to put
				// in our query string
				$query = 'SELECT * FROM `%s` WHERE `%s`=\'%s\'';
				if((int)$_SESSION['adminsession'] == 1)
				{
					$query   = sprintf($query,    TABLE_PANEL_ADMINS,
					                   'adminid', (int)$_SESSION['userid']);
					$isAdmin = true;
				}
				else
				{
					$query = sprintf($query,       TABLE_PANEL_CUSTOMERS,
					                 'customerid', (int)$_SESSION['userid']);
					$isAdmin = false;
				}
				// load the user
				$this->User = $this->DatabaseHandler->queryFirst($query);
				// and set the admin tag
				$this->User['isAdmin'] = $isAdmin;
				// We now have the name of the user and need to tell it the
				// LogHandler
				$this->LogHandler->setUsername($this->User['loginname']);
			}
			else
			{
				// The session is corrupted, delete it.
				session_destroy();
			}
		}
		$this->TemplateHandler->useTheme($this->theme);
		$this->L10nHandler->setLanguage($this->language);


	}

	/**
	 * @todo we currently set the default vendor all the time!
	 */
	protected function route()
	{
		$result = array();
		// vendor - module - area - action
		// vendor + module from $_REQUEST['module']
		// area from $User
		// action from $_REQUEST['action']

		// we start with the assumption the user is not logged in
		$result['vendor'] = self::LOGIN_VENDOR;
		$result['module'] = self::LOGIN_MODULE;
		$result['area']   = self::LOGIN_AREA;
		$result['action'] = self::LOGIN_ACTION;

		// lets check if the user is logged in and an admin
		if (is_array($this->User) && isset($this->User['isAdmin']))
		{
			// the user is logged in, lets check if the user is an admin
			if ($this->User['isAdmin'])
			{
				$result['area'] = self::ADMIN_AREA;
			}
			else
			{
				$result['area'] = self::CUSTOMER_AREA;
			}
			// now we assume the default vendor and the default module
			$result['vendor'] = self::DEFAULT_VENDOR;
			$result['module'] = self::DEFAULT_MODULE;
			$result['action'] = self::DEFAULT_ACTION;

			// lets check if a module has been given
			if(isset($_REQUEST['module']))
			{
				$result['module'] = (string)$_REQUEST['module'];
				// now we need to check if the vendor has been given in the module
				if(preg_match('/\//', $result['module']))
				{
					list($vendor, $module) = split('/', $result['module']);
					$result['module'] = (string)$module;
					$result['vendor'] = (string)$vendor;
				}
			}
			// lets check if an action has been given
			if(isset($_REQUEST['action']))
			{
				$result['action'] = (string)$_REQUEST['action'];
			}
		}
		return $result;
	}

	public function dispatch()
	{
		$routing = $this->route();

		/**
		 * @todo We need to assign the navigation here, because some module have
		 *       the bad behauviour to change $this->User, which is not allowed
		 *       to be changed, if we want to display the navigation correctly.
		 */
		$this->TemplateHandler->set('navigation', $this->generateNavigation($routing['area']));

		// Lets define the file we want to load
		$file = '%s/modules/%s/%s/%s/%s.php';
		$file = sprintf($file, SYSCP_PATH_LIB,
		                       $routing['vendor'],
		                       $routing['module'],
		                       $routing['area'],
		                       $routing['action']);
		// we need to check if the file exists, if not, we try the old
		// fashioned way
		if (!Syscp::isReadableFile($file))
		{
			$file = '%s/modules/%s_%s.php';
			$file = sprintf($file, SYSCP_PATH_LIB,
			                       $routing['area'],
			                       $routing['module']);
		}

		// This small line is the actual line, it loads the module implementation
		// into this function and executes it. This part has been added to a new function,
		// to enable module to end their execution by calling 'return'.
		$this->executeModule($file);

		// Lets check if we need some pre rendering actions on the theme.
		if(file_exists(SYSCP_PATH_LIB.'themes/'.$this->theme.'/onPreRender.php'))
		{
			require_once SYSCP_PATH_LIB.'themes/'.$this->theme.'/onPreRender.php';
		}

		// Assign the last thinggies to the TemplateHandler, remember the ConfigHandler
		// has already been set
		$this->TemplateHandler->set('User', $this->User);

		// And render the whole page
		$this->TemplateHandler->render();


		// We start with some routing, if the user is not loaded (User == null)
		// we go to the LOGIN_MODULE, otherwise we get the admin flag from the
		// user and try to go to the requested module, should this fail, we go
		// to the DEFAULT_MODULE.
//		if($this->User !== null)
//		{
//			// determine the context of the module
//			if($this->User['isAdmin'])
//			{
//				$this->area = 'admin';
//			}
//			else
//			{
//				$this->area = 'customer';
//			}
//			// retrieve the module name
//			if( isset($_GET['module']))
//			{
//				$module = $_GET['module'];
//			}
//			elseif(isset($_POST['module']))
//			{
//				$module = $_POST['module'];
//			}
//			else
//			{
//				$module = '';
//			}
//			/**
//			 * @todo For compatibility reasons, we need to split out if the module starts with
//			 *       either admin_ or customer_ this should be removed as soon as no module
//			 *       prefixes admin_ / customer_ anymore.
//			 */
//			if(preg_match('/(admin|customer)_/', $module))
//			{
//				$this->LogHandler->info( Syscp_Handler_Log_Interface::FACILITY_DEBUG,
//				                         sprintf('The module %s was called prefixed!',
//				                                 $module));
//				$module = preg_replace('/(admin|customer)_/', '', $module);
//			}
//
//			// lets take a look if the module exists
//			if(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$this->area.'_'.$module.'.php'))
//			{
//				$this->module = $this->area.'_'.$module;
//			}
//			elseif(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.$this->area.'_'.self::DEFAULT_MODULE.'.php'))
//			{
//				$this->module = $this->area.'_'.self::DEFAULT_MODULE;
//			}
//			else
//			{
//				$error = sprintf(self::ERROR_DEFAULT_MODULE_MISSING, $this->area, self::DEFAULT_MODULE);
//				throw new Syscp_FrontController_Web_Exception($error);
//			}
//		}
//		elseif(Syscp::isReadableFile(SYSCP_PATH_LIB.'modules/'.self::LOGIN_MODULE.'.php'))
//		{
//			$this->module = self::LOGIN_MODULE;
//		}
//		else
//		{
//			$error = sprintf(self::ERROR_LOGIN_MODULE_MISSING, self::LOGIN_MODULE);
//			throw new Syscp_FrontController_Web_Exception($error);
//		}
//
//		/**
//		 * @todo We need to assign the navigation here, because some module have
//		 *       the bad behauviour to change $this->User, which is not allowed
//		 *       to be changed, if we want to display the navigation correctly.
//		 */
//		$this->TemplateHandler->set('navigation', $this->generateNavigation($this->area));
//
//		// This small line is the actual line, it loads the module implementation
//		// into this function and executes it. This part has been added to a new function,
//		// to enable module to end their execution by calling 'return'.
//		$this->executeModule($this->module);
//
//		// Lets check if we need some pre rendering actions on the theme.
//		if(file_exists(SYSCP_PATH_LIB.'themes/'.$this->theme.'/onPreRender.php'))
//		{
//			require_once SYSCP_PATH_LIB.'themes/'.$this->theme.'/onPreRender.php';
//		}
//
//		// Assign the last thinggies to the TemplateHandler, remember the ConfigHandler
//		// has already been set
//		$this->TemplateHandler->set('User', $this->User);
//
//		// And render the whole page
//		$this->TemplateHandler->render();
	}

	/**
	 * @todo Should be moved to templatehandler
	 */
	public function createLink($params, $host = null)
	{
		$link = 'index.php';
		$linkParams = array();
		$linkParams[session_name()] = session_id();
		foreach($params as $key => $value)
		{
			$linkParams[$key] = $value;
		}
		// now lets generate the link
		$tmp = array();
		foreach($linkParams as $key => $value)
		{
			$tmp[] = $key.'='.$value;
		}
		$linkParams = $tmp;
		$link = sprintf('%s?%s', $link, implode('&', $linkParams));

		if(is_null($host))
		{
			if ( isset( $_SERVER['HTTPS'] ) && strtolower($_SERVER['HTTPS']) == 'on' )
			{
				$protocol = 'https://';
			}
			else
			{
				$protocol = 'http://';
			}
			$host = $_SERVER['HTTP_HOST'];
			if ( dirname( $_SERVER['PHP_SELF'] ) == '/' )
			{
				$path     = '/';
			}
			else
			{
				$path     = dirname($_SERVER['PHP_SELF']) . '/';
			}
			$host = $protocol.$host.$path;
		}

		return $host.$link;
	}

	public function redirectTo($params, $host = null)
	{
		$link = $this->createLink($params, $host);
		header('Location: '.$link);
	}

	private function initHandler()
	{
		/**
		 * @todo This is bloody, we parse parts of the config only to get the
		 *       connection values to the database. And afterwards we need to
		 *       create the ConfigHandler, which needs DatabaseHandler as
		 *       param. Looks like the snake bites it's tail.
		 */
		$syscp_conf   = Syscp::parseConfig(SYSCP_PATH_BASE.'etc/syscp.conf');
		$syscp_conf   = $syscp_conf['conf'];
		$version_conf = Syscp::parseConfig(SYSCP_PATH_BASE.'etc/version.conf');
		// lets init the database
		// we start by parsing the syscp.conf file, which holds the database connection
		// parameters
		$dsn = sprintf('mysql://%s:%s@%s/%s',
		               $syscp_conf['mysql']['username'],
		               $syscp_conf['mysql']['password'],
		               $syscp_conf['mysql']['hostname'],
		               $syscp_conf['mysql']['database']);
		Syscp::uses('Syscp.Handler.Database');
		$this->DatabaseHandler = new Syscp_Handler_Database();
		$this->DatabaseHandler->initialize(array('dsn'=>$dsn));

		// Lets init the ConfigHandler.
		Syscp::uses('Syscp.Handler.Config');
		$this->ConfigHandler = new Syscp_Handler_Config();
		$this->ConfigHandler->initialize(
			array('files' => array(SYSCP_PATH_BASE.'etc/syscp.conf',
			                       SYSCP_PATH_BASE.'etc/version.conf')));
		$this->ConfigHandler->loadFromDB($this->DatabaseHandler);

		// create a new session object
		Syscp::uses('Syscp.Handler.Session');
		$this->SessionHandler = new Syscp_Handler_Session();
		$params = array('DatabaseHandler' => $this->DatabaseHandler,
		                'remoteAddr'      => $this->ConfigHandler->get('env.remote_addr'),
		                'httpUserAgent'   => $this->ConfigHandler->get('env.http_user_agent'),
		                'sessionTimeout'  => $this->ConfigHandler->get('session.sessiontimeout'));
		$this->SessionHandler->initialize($params);

		// The IDNA Converter
		Syscp::uses('Syscp.Handler.Idna');
		$this->IdnaHandler = new Syscp_Handler_Idna();
		$this->IdnaHandler->initialize();
	}

	/**
	 * generates the navigation array for the panel
	 *
	 * Please note, this function is currently only here, because there is no
	 * better place at the moment. It will be removed, as soon as we support
	 * slots and blocks in the TemplateHandler
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 *
	 * @param   string  area  The area of the navigation, can be
	 *                        login    => the login screen
	 *                        admin    => the navigation of an administrator
	 *                        customer => the navigation of a customer
	 *
	 * @return  array   the navigation array in the form of
	 *                  array( $order =>
	 *                         array( 'url' => $url,
	 *                                'lang' => $lang,
	 *                                'target' => $target,
	 *                                'childs' =>
	 *                                array( 'url' => $url,
	 *                                       'lang' => $lang,
	 *                                       'target' => $target
	 *                                     )
	 *                              )
	 */
	private function generateNavigation($area)
	{
		// init vars
		$return = '';
		// convenience shortcut
		$navig = $this->navigationConfig;
		// only proceed if there is an entry regarding the selected area
		if (isset($navig[$area]))
		{
			// shorten the navigation shortcut again
			$navig = $navig[$area];
			// iterate the navigation
			foreach($navig as $entry)
			{
				// check resources
				if($entry['req_resources'] == '' ||
				   (int)$this->User[$entry['req_resources']] > 0 ||
				   (int)$this->User[$entry['req_resources']] == -1)
				{
					// extract order
					$order = $entry['order'];
					// find next free order place
					while (isset($return[$order]))
					{
						$order++;
					}
					// create the navigation element
					$return[$order] = $this->createNavigationElement($entry['url'],
					                                                 $entry['new_window'],
					                                                 $entry['lang']);
					// now lets iterate the children
					if (isset($entry['childs']))
					{
						$childs = array();
						foreach($entry['childs'] as $child)
						{
							if($child['req_resources'] == '' ||
							   (int)$this->User[$child['req_resources']] > 0 ||
							   (int)$this->User[$child['req_resources']] == -1)
							{
								$childOrder = $child['order'];
								// find next free order place
								while (isset($childs[$childOrder]))
								{
									$childOrder++;
								}
								$childs[$childOrder] = $this->createNavigationElement($child['url'],
								                                                      $child['new_window'],
								                                                      $child['lang']);
							}

						}
						// put the child to it's parent
						$return[$order]['childs'] = $childs;
					}
				}
			}
		}
		// sort the result according to order
		ksort($return);
		foreach($return as $key => $value)
		{
			if(isset($return[$key]['childs']))
			{
				ksort($return[$key]['childs']);
			}
		}
		return $return;
	}

	private function createNavigationElement($url, $target, $lang)
	{
		$return = array();

		// create language element
		$lang = str_replace(';', '.', $lang );
		$return['id']   = str_replace('.','_',$lang);
		$return['lang'] = $this->L10nHandler->get($lang);

		// create link
		if(!preg_match('/nourl/i', $url) && !trim($url) == '')
		{
			// it's a link
			$return['isLink'] = true;

			// now lets check if it's a local link
			if(!preg_match('/^https?\:\/\//i', $url))
			{
				if(preg_match('/(\.php)/i', $url))
				{
					// it's local, we need to process the url
					// COMPATIBILITY, we need heavy reparsing here, because we need to change old
					// style urls to the new style urls

					// lets start to split out the filename
					$url = split('\?',$url);
					// url[0] now contains the filename and url[1] the params
					$module = str_replace('.php', '', $url[0]);
					$module = preg_replace('/(admin|customer)_/', '', $module);
					if (isset($url[1]))
					{
						$params = $url[1];
						// we need to split up the params by now using & as first split option
						$params = split('&',$params);
						// now we need to iterate the params and split again at the =
						$tmp = array();
						foreach($params as $param)
						{
							$param = split('=', $param);
							$tmp[$param[0]] = $param[1];
						}
						$params = $tmp;
					}
					else
					{
						$params = array();
					}
					// set the module in the params
					$params['module'] = $module;
					$return['url'] = $this->createLink($params);
				}
				else
				{
					// we have already new style url data
					$params = split('&', $url);
					$tmp    = array();
					foreach($params as $param)
					{
						$param = split('=', $param);
						$tmp[$param[0]] = $param[1];
					}
					$params = $tmp;
					$return['url'] = $this->createLink($params);
				}
			}
			else
			{
				// it's not local, we don't change the url
				$return['url'] = $url;
			}
		}
		else
		{
			// is not a link
			$return['isLink'] = false;
		}

		// check for target
		if ( $target == 1 )
		{
			$target = '_blank';
		}
		else
		{
			$target = '_self';
		}
		$return['target'] = $target;

		return $return;
	}

	private function executeModule($module)
	{
		require_once $module;
	}
}
