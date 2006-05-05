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
 * @author     Michael Dürgner <michael@duergner.com>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: class_idna_convert_wrapper.php 425 2006-03-18 09:48:20Z martin $
 */
 
/**
 * The apache hook implementation. 
 * 
 * This hook implements the apache specific functions regarding the 
 * rewriting of the vhosts.conf file.
 *
 * @package    Org.Syscp.Core
 * @subpackage Hooks
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */
class Syscp_Hooks_Apache extends Syscp_BaseHook 
{
	/**
	 * Filename of the file this hook is implemented in. 
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 * @access private
	 */
	var $FILE;  // CONST later

	/**
	 * Classname of this class
	 * Consider this variable to be class specific constant.
	 *
	 * @var    string
	 * @access private
	 */
	var $CLASS; // CONST later
	
	/**
	 * Class Constructor
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @return  Org_Syscp_Core_Hooks_Apache
	 */
	function __construct()
	{
		$this->FILE  = 'lib/classes/Syscp/Hooks/Apache.class.php';
		$this->CLASS = __CLASS__;
	}
	
	/**
	 * core.deleteCustomer Hook
	 * 
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deleteCustomer( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}
	
	/**
	 * core.deactivateCustomer Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deactivateCustomer( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}
	
	/**
	 * core.createDomain Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function createDomain( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}
	
	/**
	 * core.deleteDomain Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deleteDomain( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}
	
	/**
	 * core.updateDomain Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 * 
	 * @todo Implement this function
	 */
	function updateDomain( $params = array() )
	{
		// check if we need to update the vhosts
	}

	/**
	 * core.createIPPort Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function createIPPort( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}

	/**
	 * core.updateIPPort Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function updateIPPort( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}

	/**
	 * core.createHTPasswd Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function createHTPasswd( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}
	
	/**
	 * core.updateHTPasswd Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function updateHTPasswd( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}
	
	/**
	 * core.deleteHTPasswd Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deleteHTPasswd( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}
	
	/**
	 * core.createHTAccess Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function createHTAccess( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}
	
	/**
	 * core.updateHTAccess Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function updateHTAccess( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}

	/**
	 * core.deleteHTAccess Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deleteHTAccess( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildDiroptions', $params );
	}
	
	/**
	 * core.deleteIPPort Hook
	 *
	 * This hook basically only schedules the cronRebuildVhosts() function 
	 * call for the backend. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 */
	function deleteIPPort( $params = array() )
	{
		$this->_hooks->schedule( $this->FILE, $this->CLASS, 'cronRebuildVhosts', $params );
	}
	
	/**
	 * This method should _ONLY_ be called from the backend cronscript. 
	 * 
	 * This method creates a new vhosts.conf file and stores it at the 
	 * places configured in $config.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 * 
	 * @todo Reimplement this function to use templates later on. 
	 */
	function cronRebuildVhosts( $params = array() )
	{
		// load the config and db vars from our attributes
		$config = $this->_config;
		$db     = $this->_db;
		$log    = $this->_log;
		
		$log->info( '-- cronRebuildVhosts: Creating new vhosts.conf' );
		$vhosts_file = '# '.$config->get('system.apacheconf_directory') . 
		               $config->get('system.apacheconf_filename') . "\n" . 
		               '# Created ' . date('d.m.Y H:i') . "\n" . 
		               '# Do NOT manually edit this file, all changes will be ' .
		               ' deleted after the next domain change at the panel.' . "\n" .
		               "\n";

		if (file_exists($config->get('system.apacheconf_directory').'diroptions.conf'))
		{
			$log->info( '-- cronRebuildVhosts: Writing diroptions include' );
			$vhosts_file .= 'Include ' . $config->get('system.apacheconf_directory') . 
			                'diroptions.conf' . "\n\n";
		}

		$result_ipsandports=$db->query("SELECT CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) ORDER BY `ip`.`ip` ASC");
		$ipandportarray = array();
		while($ipandportresult=$db->fetch_array($result_ipsandports))
		{
			$ipandportarray[$ipandportresult['ipandport']] = $ipandportresult['ipandport'];
		}
		foreach($ipandportarray as $ipandport)
		{
			$vhosts_file.='NameVirtualHost '.$ipandport."\n";
		}
		$vhosts_file.="\n";

		$log->info( '-- cronRebuildVhosts: DummyHost written' );
		$vhosts_file.='# DummyHost for DefaultSite'."\n";
		$vhosts_file.='<VirtualHost '.$config->get('system.ipaddress').':80>'."\n";
		$vhosts_file.='ServerName '.$config->get('system.hostname')."\n";
		$vhosts_file.='</VirtualHost>'."\n"."\n";

		$result_domains=$db->query("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, CONCAT(`ip`.`ip`,':',`ip`.`port`) AS `ipandport`, `d`.`parentdomainid`, `d`.`isemaildomain`, `d`.`iswildcarddomain`, `d`.`openbasedir`, `d`.`safemode`, `d`.`speciallogfile`, `d`.`specialsettings`, `pd`.`domain` AS `parentdomain`, `c`.`loginname`, `c`.`guid`, `c`.`email`, `c`.`documentroot` AS `customerroot` FROM `".TABLE_PANEL_DOMAINS."` `d` LEFT JOIN `".TABLE_PANEL_CUSTOMERS."` `c` USING(`customerid`) LEFT JOIN `".TABLE_PANEL_DOMAINS."` `pd` ON (`pd`.`id` = `d`.`parentdomainid`) LEFT JOIN `".TABLE_PANEL_IPSANDPORTS."` `ip` ON (`d`.`ipandport` = `ip`.`id`) WHERE `d`.`deactivated` <> '1' AND `d`.`aliasdomain` IS NULL ORDER BY `d`.`iswildcarddomain`, `d`.`domain` ASC");
		while($domain=$db->fetch_array($result_domains))
		{
			$vhosts_file.='# Domain ID: '.$domain['id'].' - CustomerID: '.$domain['customerid'].' - CustomerLogin: '.$domain['loginname']."\n";
			$vhosts_file.='<VirtualHost '.$domain['ipandport'].'>'."\n";
			$vhosts_file.='  ServerName '.$domain['domain']."\n";

			$server_alias = '';
			$alias_domains = $db->query('SELECT `domain`, `iswildcarddomain` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$domain['id'].'\'');
			while(($alias_domain=$db->fetch_array($alias_domains)) !== false)
			{
				$server_alias .= ' '.$alias_domain['domain'].' '.(($alias_domain['iswildcarddomain']==1) ? '*' : 'www').'.'.$alias_domain['domain'];
			}
			if($domain['iswildcarddomain'] == '1')
			{
				$alias = '*';
			}
			else
			{
				$alias = 'www';
			}
			$vhosts_file.='  ServerAlias '.$alias.'.'.$domain['domain'].$server_alias."\n";
			$vhosts_file.='  ServerAdmin '.$domain['email']."\n";

			if(preg_match('/^https?\:\/\//', $domain['documentroot']))
			{
				$vhosts_file.='  Redirect / '.$domain['documentroot']."\n";
			}
			else
			{
				$domain['documentroot'] = makeCorrectDir ($domain['documentroot']);
				$vhosts_file.='  DocumentRoot "'.$domain['documentroot']."\"\n";
 				if($domain['openbasedir'] == '1')
 				{
					$vhosts_file.='  php_admin_value open_basedir "'.$domain['documentroot']."\"\n";
 				}
 				if($domain['safemode'] == '1')
 				{
 					$vhosts_file.='  php_admin_flag safe_mode On '."\n";
 				}
				if($domain['safemode'] == '0')
				{
					$vhosts_file.='  php_admin_flag safe_mode Off '."\n";
				}

				if(!is_dir($domain['documentroot']))
 				{					
 					safe_exec('mkdir -p "'.$domain['documentroot'].'"');
					safe_exec('chown -R '.$domain['guid'].':'.$domain['guid'].' "'.$domain['documentroot'].'"');
				}
				if($domain['speciallogfile'] == '1')
				{
					if($domain['parentdomainid'] == '0')
					{
						$speciallogfile = '-'.$domain['domain'];
						$vhosts_file .= '   Alias /webalizer "'.$domain['customerroot'].'/webalizer/'.$domain['domain']."\"\n";
					}
					else
					{
						$speciallogfile = '-'.$domain['parentdomain'];
						$vhosts_file .= '   Alias /webalizer "'.$domain['customerroot'].'/webalizer/'.$domain['parentdomain']."\"\n";
					}
				}
				else
				{
					$speciallogfile = '';
				}
				$vhosts_file.='  ErrorLog "'.$config->get('system.logfiles_directory').$domain['loginname'].$speciallogfile.'-error.log'."\"\n";
				$vhosts_file.='  CustomLog "'.$config->get('system.logfiles_directory').$domain['loginname'].$speciallogfile.'-access.log" combined'."\n";
			}
			$vhosts_file.=stripslashes($domain['specialsettings'])."\n";
			$vhosts_file.='</VirtualHost>'."\n";
			$vhosts_file.="\n";
			$log->info( sprintf('-- cronRebuildVhosts: Domain %s written', $domain['domain']) );
		}
		$vhosts_file_handler = fopen( $config->get('system.apacheconf_directory') . 
		                              $config->get('system.apacheconf_filename'), 'w');
		fwrite($vhosts_file_handler, $vhosts_file);
		fclose($vhosts_file_handler);
		$log->info( '-- cronRebuildVhosts: Restarting Apache...' );
		safe_exec($config->get('system.apachereload_command'));
	}
	
	/**
	 * This method should _ONLY_ be called from the backend cronscript. 
	 * 
	 * This method creates a new diroptions.conf file and stores it at the 
	 * places configured in $config.
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   array  $params  Parameters to be used in this hook call
	 * 
	 * @return  void
	 * 
	 * @todo Reimplement this function to use templates later on. 
	 */
	function cronRebuildDiroptions( $params = array() )
	{
		$config = $this->_config;
		$db     = $this->_db;
		$log    = $this->_log;

		if ( isset( $params['path'] ) )
		{
			$path = $params['path'];
			$log->info( sprintf( '-- cronRebuildDiroptions: Creating diroption for %s', 
			                     $path ) );
//				fwrite( $debugHandler, '  cron_tasks: Task3 - Path: '.$path);
				
			if (!is_dir($path))
			{
				$db->query(
					'DELETE FROM `'.TABLE_PANEL_HTACCESS.'` ' .
					'WHERE `path` = "'.$path.'"'
				);
				$db->query(
					'DELETE FROM `'.TABLE_PANEL_HTPASSWDS.'` ' .
					'WHERE `path` = "'.$path.'"'
				);
			}
				
			$diroptions_file = '';
			$diroptions_file = '# '.$config->get('system.apacheconf_directory').'diroptions.conf'."\n".'# Created '.date('d.m.Y H:i')."\n".'# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.'."\n"."\n";
			$result = $db->query(
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_HTACCESS.'` ' .
				'ORDER BY `path`'
			);
			$diroptions = array();
			while($row_diroptions=$db->fetch_array($result))
			{
				$diroptions[$row_diroptions['path']] = $row_diroptions;
				$diroptions[$row_diroptions['path']]['htpasswds'] = array();
			}
			$result = $db->query(
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_HTPASSWDS.'` ' .
				'ORDER BY `path`, `username`'
			);
			while($row_htpasswds=$db->fetch_array($result))
			{
				$diroptions[$row_htpasswds['path']]['path'] = $row_htpasswds['path'];
				$diroptions[$row_htpasswds['path']]['customerid'] = $row_htpasswds['customerid'];
				$diroptions[$row_htpasswds['path']]['htpasswds'][] = $row_htpasswds;
			}
				
			$htpasswd_files = array();
			foreach($diroptions as $row_diroptions)
			{
 				$diroptions_file .= '<Directory "'.$row_diroptions['path'].'">'."\n";
 				if ( isset ( $row_diroptions['options_indexes'] ) && $row_diroptions['options_indexes'] == '1' )
 				{
					$diroptions_file .= '  Options +Indexes'."\n";
//					fwrite( $debugHandler, '  cron_tasks: Task3 - Setting Options +Indexes');
				}
				if ( isset ( $row_diroptions['options_indexes'] ) && $row_diroptions['options_indexes'] == '0' )
				{
					$diroptions_file .= '  Options -Indexes'."\n";
//					fwrite( $debugHandler, '  cron_tasks: Task3 - Setting Options -Indexes');
 				}
 				if ( isset ( $row_diroptions['error404path'] ) && $row_diroptions['error404path'] != '')
 				{
					$diroptions_file .= '  ErrorDocument 404 "'.$row_diroptions['error404path']."\"\n";
				}
				if ( isset ( $row_diroptions['error403path'] ) && $row_diroptions['error403path'] != '')
				{
					$diroptions_file .= '  ErrorDocument 403 "'.$row_diroptions['error403path']."\"\n";
				}
//				if ( isset ( $row_diroptions['error401path'] ) && $row_diroptions['error401path'] != '')
//				{
//					$diroptions_file .= '  ErrorDocument 401 '.$row_diroptions['error401path']."\n";
//				}
				if ( isset ( $row_diroptions['error500path'] ) && $row_diroptions['error500path'] != '')
				{
					$diroptions_file .= '  ErrorDocument 500 "'.$row_diroptions['error500path']."\"\n";
				}
					
				if(count($row_diroptions['htpasswds']) > 0)
				{
					$htpasswd_file = '';
					$htpasswd_filename = '';
					foreach($row_diroptions['htpasswds'] as $row_htpasswd)
					{
						if($htpasswd_filename == '')
						{
							$htpasswd_filename = $config->get('system.apacheconf_directory').'htpasswd/'.$row_diroptions['customerid'].'-'.$row_htpasswd['id'].'-'.md5($row_diroptions['path']).'.htpasswd';
							$htpasswd_files[] = basename($htpasswd_filename);
						}
						$htpasswd_file .= $row_htpasswd['username'].':'.$row_htpasswd['password']."\n";
					}
//					fwrite( $debugHandler, '  cron_tasks: Task3 - Setting Password');
					$diroptions_file .= '  AuthType Basic'."\n";
					$diroptions_file .= '  AuthName "Restricted Area"'."\n";
					$diroptions_file .= '  AuthUserFile '.$htpasswd_filename."\n";
					$diroptions_file .= '  require valid-user'."\n";
					
					if(!file_exists($config->get('system.apacheconf_directory').'htpasswd/')) 
					{
						$umask = umask();
						umask( 0000 );
						mkdir($config->get('system.apacheconf_directory').'htpasswd/',0751);
						umask( $umask );
					}
					elseif(!is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
					{
						$log->err( sprintf( '%shtpasswd/ is not a directory, directory protection disabled!', 
						                    $config->get('system.aacheconf_directory') ) );
						echo 'WARNING!!! ' . $config->get('system.apacheconf_directory').'htpasswd/ is not a directory. htpasswd directory protection is disabled!!!' ;
					}
					if(file_exists($config->get('system.apacheconf_directory').'htpasswd/') && is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
					{
						$htpasswd_file_handler = fopen($htpasswd_filename, 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
					}
				}
				$diroptions_file .= '</Directory>'."\n\n";
			}
			$diroptions_file_handler = fopen($config->get('system.apacheconf_directory').'diroptions.conf', 'w');
			fwrite($diroptions_file_handler, $diroptions_file);
			fclose($diroptions_file_handler);
			$log->info( '-- cronRebuildDiroptions: restarting apache...' );
			safe_exec($config->get('system.apachereload_command'));
				
			if(file_exists($config->get('system.apacheconf_directory').'htpasswd/') && is_dir($config->get('system.apacheconf_directory').'htpasswd/'))
			{
				$htpasswd_file_dirhandle = opendir($config->get('system.apacheconf_directory').'htpasswd/');
				while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle))) 
				{
					if($htpasswd_filename != '.' && $htpasswd_filename != '..' && !in_array($htpasswd_filename,$htpasswd_files) && file_exists($config->get('system.apacheconf_directory').'htpasswd/'.$htpasswd_filename)) 
					{
						unlink($config->get('system.apacheconf_directory').'htpasswd/'.$htpasswd_filename);
					}
				}
			}
		}		
	}
}

?>