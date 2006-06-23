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
 * This class represents the FrontController implementation regarding cli requests.
 *
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.FrontController
 */
class Syscp_FrontController_Cli extends Syscp_FrontController implements Syscp_FrontController_Cli_Interface
{
	const DEFAULT_MODULE   = 'cron';
	const DEFAULT_ACTION   = 'backend';
	const DEFAULT_VENDOR   = 'SysCP';
	const DEFAULT_AREA     = 'cron';

	const DEFAULT_LANGUAGE = 'English';
	const DEFAULT_THEME    = 'default';

	const ERROR_MODULE_MISSING = 'The module at %s can not be found!';

	private $theme    = self::DEFAULT_THEME;
	private $language = self::DEFAULT_LANGUAGE;

	private $LogHandler      = null;
	private $DatabaseHandler = null;
	private $ConfigHandler   = null;
	private $SessionHandler  = null;
	private $HookHandler     = null;
	private $IdnaHandler     = null;
	private $L10nHandler     = null;
	private $TemplateHandler = null;

	private $User = null;

	private $module = self::DEFAULT_MODULE;
	private $action = self::DEFAULT_ACTION;
	private $vendor = self::DEFAULT_VENDOR;
	private $area   = self::DEFAULT_AREA;

	public function __construct()
	{
		// Setup the generic exception handler for this controller.
		Syscp::uses('Syscp.FrontController.Cli.ExceptionHandler');
		Syscp_FrontController_Cli_ExceptionHandler::initialize();

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
		$this->LogHandler->initialize(array('logdir'=>SYSCP_PATH_BASE.'logs/cron/'));
	}

	public function initialize($params = array())
	{
		if(isset($params['vendor']))
		{
			$this->vendor = $params['vendor'];
		}
		if(isset($params['module']))
		{
			$this->module = $params['module'];
		}
		if(isset($params['action']))
		{
			$this->action = $params['action'];
		}

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
			$this->TemplateHandler->showError('You\'ll have to upgrade your SysCP installation. <br/>Please call "syscp upgrade" in your bash!<br/>Installed Version: '.$this->ConfigHandler->get('env.version').'<br/>Database Version: '.$this->ConfigHandler->get('panel.version').'<br/>');
		}
		//$this->TemplateHandler->useTheme(self::DEFAULT_THEME);
		$this->L10nHandler->setLanguage(self::DEFAULT_LANGUAGE);
	}

	public function dispatch()
	{
		// Define the file we want to use
		$file = '%s/modules/%s/%s/%s/%s.php';
		$file = sprintf($file, SYSCP_PATH_LIB,
		                       $this->vendor,
		                       $this->module,
		                       $this->area,
		                       $this->action);

		// Lets do the routing
		if(Syscp::isReadableFile($file))
		{
			// This small line is the actual line, it loads the module implementation
			// into this function and executes it. This part has been added to a new function,
			// to enable module to end their execution by calling 'return'.
			$this->executeModule($file);
		}
		else
		{
			$error = sprintf(self::ERROR_MODULE_MISSING, $file);
			throw new Syscp_FrontController_Cli_Exception($error);
		}
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

		// The IDNA Converter
		Syscp::uses('Syscp.Handler.Idna');
		$this->IdnaHandler = new Syscp_Handler_Idna();
		$this->IdnaHandler->initialize();
	}

	private function executeModule($module)
	{
		require_once $module;
	}
}
