<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @author     Martin Burchert <eremit@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    System
 * @version    $Id$
 */

if(@php_sapi_name() != 'cli'
   && @php_sapi_name() != 'cgi'
   && @php_sapi_name() != 'cgi-fcgi')
{
	die('This script only works in the shell.');
}


class diroptionsApache
{
	private $db = false;
	private $logger = false;
	private $debugHandler = false;
	private $settings = array();

	function __construct( $db, $logger, $debugHandler, $settings )
	{
		$this->db = $db;
		$this->logger = $logger;
		$this->debugHandler = $debugHandler;
		$this->settings = $settings;
	}

	public function reload()
	{
		fwrite($this->debugHandler, '   diroptionsApache::reload: reloading apache' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'reloading apache');

		safe_exec($this->settings['system']['apachereload_command']);
	}

	public function createFileDirOptions()
	{
		fwrite($this->debugHandler, '  cron_tasks: Task3 started - create/change/del htaccess/htpasswd' . "\n");
		$this->logger->logAction(CRON_ACTION, LOG_INFO, 'Task3 started - create/change/del htaccess/htpasswd');
		$diroptions_file = '';

		if(isConfigDir($this->settings['system']['apacheconf_diroptions'])
		   && !file_exists($this->settings['system']['apacheconf_diroptions']))
		{
			$this->logger->logAction(CRON_ACTION, LOG_NOTICE, 'mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_diroptions'])));
			safe_exec('mkdir ' . escapeshellarg(makeCorrectDir($this->settings['system']['apacheconf_diroptions'])));
		}

		if(!file_exists($this->settings['system']['apacheconf_htpasswddir']))
		{
			$umask = umask();
			umask(0000);
			mkdir($this->settings['system']['apacheconf_htpasswddir'], 0751);
			umask($umask);
		}
		elseif(!is_dir($this->settings['system']['apacheconf_htpasswddir']))
		{
			fwrite($this->debugHandler, '  cron_tasks: WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!' . "\n");
			echo 'WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!';
			$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'WARNING!!! ' . $this->settings['system']['apacheconf_htpasswddir'] . ' is not a directory. htpasswd directory protection is disabled!!!');
		}

		$result = $this->db->query('SELECT `htac`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTACCESS . '` `htac` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htac`.`path`');
		$diroptions = array();

		while($row_diroptions = $this->db->fetch_array($result))
		{
			if($row_diroptions['customerid'] != 0
			   && isset($row_diroptions['customerroot'])
			   && $row_diroptions['customerroot'] != '')
			{
				$diroptions[$row_diroptions['path']] = $row_diroptions;
				$diroptions[$row_diroptions['path']]['htpasswds'] = array();
			}
		}

		$result = $this->db->query('SELECT `htpw`.*, `c`.`guid`, `c`.`documentroot` AS `customerroot` FROM `' . TABLE_PANEL_HTPASSWDS . '` `htpw` LEFT JOIN `' . TABLE_PANEL_CUSTOMERS . '` `c` USING (`customerid`) ORDER BY `htpw`.`path`, `htpw`.`username`');

		while($row_htpasswds = $this->db->fetch_array($result))
		{
			if($row_htpasswds['customerid'] != 0
			   && isset($row_htpasswds['customerroot'])
			   && $row_htpasswds['customerroot'] != '')
			{
				if(!is_array($diroptions[$row_htpasswds['path']]))
				{
					$diroptions[$row_htpasswds['path']] = array();
				}

				$diroptions[$row_htpasswds['path']]['path'] = $row_htpasswds['path'];
				$diroptions[$row_htpasswds['path']]['guid'] = $row_htpasswds['guid'];
				$diroptions[$row_htpasswds['path']]['customerroot'] = $row_htpasswds['customerroot'];
				$diroptions[$row_htpasswds['path']]['customerid'] = $row_htpasswds['customerid'];
				$diroptions[$row_htpasswds['path']]['htpasswds'][] = $row_htpasswds;
			}
		}

		$known_diroptions_files = array();
		$known_htpasswd_files = array();
		foreach($diroptions as $row_diroptions)
		{
			$row_diroptions['path'] = makeCorrectDir($row_diroptions['path']);
			mkDirWithCorrectOwnership($row_diroptions['customerroot'], $row_diroptions['path'], $row_diroptions['guid'], $row_diroptions['guid']);

			if(is_dir($row_diroptions['path']))
			{
				$diroptions_file.= '<Directory "' . $row_diroptions['path'] . '">' . "\n";

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '1')
				{
					$diroptions_file.= '  Options +Indexes' . "\n";
					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options +Indexes' . "\n");
				}

				if(isset($row_diroptions['options_indexes'])
				   && $row_diroptions['options_indexes'] == '0')
				{
					$diroptions_file.= '  Options -Indexes' . "\n";
					fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Options -Indexes' . "\n");
				}

				if(isset($row_diroptions['error404path'])
				   && $row_diroptions['error404path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 404 ' . $row_diroptions['error404path'] . "\n";
				}

				if(isset($row_diroptions['error403path'])
				   && $row_diroptions['error403path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 403 ' . $row_diroptions['error403path'] . "\n";
				}

				if(isset($row_diroptions['error500path'])
				   && $row_diroptions['error500path'] != '')
				{
					$diroptions_file.= '  ErrorDocument 500 ' . $row_diroptions['error500path'] . "\n";
				}

				if(count($row_diroptions['htpasswds']) > 0)
				{
					$htpasswd_file = '';
					$htpasswd_filename = '';
					foreach($row_diroptions['htpasswds'] as $row_htpasswd)
					{
						if($htpasswd_filename == '')
						{
							$htpasswd_filename = makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $row_diroptions['customerid'] . '-' . $row_htpasswd['id'] . '-' . md5($row_diroptions['path']) . '.htpasswd');
							$known_htpasswd_files[] = basename($htpasswd_filename);
						}

						$htpasswd_file.= $row_htpasswd['username'] . ':' . $row_htpasswd['password'] . "\n";
					}

					if(file_exists($this->settings['system']['apacheconf_htpasswddir'])
					   && is_dir($this->settings['system']['apacheconf_htpasswddir']))
					{
						fwrite($this->debugHandler, '  cron_tasks: Task3 - Setting Password' . "\n");
						$diroptions_file.= '  AuthType Basic' . "\n";
						$diroptions_file.= '  AuthName "Restricted Area"' . "\n";
						$diroptions_file.= '  AuthUserFile ' . $htpasswd_filename . "\n";
						$diroptions_file.= '  require valid-user' . "\n";
						$htpasswd_file_handler = fopen($htpasswd_filename, 'w');
						fwrite($htpasswd_file_handler, $htpasswd_file);
						fclose($htpasswd_file_handler);
					}
				}

				$diroptions_file.= '</Directory>' . "\n";

				if(isConfigDir($this->settings['system']['apacheconf_diroptions']))
				{
					$diroptions_filename = makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/40_syscp_diroption_' . md5($row_diroptions['path']) . '.conf');
					$known_diroptions_files[] = basename($diroptions_filename);

					// Apply header

					$diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $diroptions_file;

					// Save partial file

					$diroptions_file_handler = fopen($diroptions_filename, 'w');
					fwrite($diroptions_file_handler, $diroptions_file);
					fclose($diroptions_file_handler);

					// Reset diroptions_file

					$diroptions_file = '';
				}
				else
				{
					$diroptions_file.= "\n";
				}
			}
		}

		if(!isConfigDir($this->settings['system']['apacheconf_diroptions']))
		{
			$diroptions_filename = $this->settings['system']['apacheconf_diroptions'];

			// Apply header

			$diroptions_file = '# ' . $diroptions_filename . "\n" . '# Created ' . date('d.m.Y H:i') . "\n" . '# Do NOT manually edit this file, all changes will be deleted after the next dir options change at the panel.' . "\n" . "\n" . $diroptions_file;

			// Save big file

			$diroptions_file_handler = fopen($diroptions_filename, 'w');
			fwrite($diroptions_file_handler, $diroptions_file);
			fclose($diroptions_file_handler);
		}
		else
		{
			$diroptions_dir = makeCorrectDir($this->settings['system']['apacheconf_diroptions']);

			if(file_exists($diroptions_dir)
			   && is_dir($diroptions_dir))
			{
				$diroptions_file_dirhandle = opendir($diroptions_dir);

				while(false !== ($diroptions_filename = readdir($diroptions_file_dirhandle)))
				{
					if($diroptions_filename != '.'
					   && $diroptions_filename != '..'
					   && !in_array($diroptions_filename, $known_diroptions_files)
					   && preg_match('/^40_syscp_diroption_(.+)\.conf$/', $diroptions_filename)
					   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename)))
					{
						$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
						unlink(makeCorrectFile($this->settings['system']['apacheconf_diroptions'] . '/' . $diroptions_filename));
					}
				}
			}
		}

		$htpasswd_dir = makeCorrectDir($this->settings['system']['apacheconf_htpasswddir']);

		if(file_exists($htpasswd_dir)
		   && is_dir($htpasswd_dir))
		{
			$htpasswd_file_dirhandle = opendir($htpasswd_dir);

			while(false !== ($htpasswd_filename = readdir($htpasswd_file_dirhandle)))
			{
				if($htpasswd_filename != '.'
				   && $htpasswd_filename != '..'
				   && !in_array($htpasswd_filename, $known_htpasswd_files)
				   && file_exists(makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename)))
				{
					$this->logger->logAction(CRON_ACTION, LOG_WARNING, 'Deleting ' . makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
					unlink(makeCorrectFile($this->settings['system']['apacheconf_htpasswddir'] . '/' . $htpasswd_filename));
				}
			}
		}
	}
}


?>
