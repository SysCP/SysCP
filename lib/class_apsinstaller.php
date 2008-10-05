<?php

/**
 * Implementation of the Application Packaging Standard from SwSoft/Parallels
 * http://apsstandard.com
 *
 * Copyright (c) 2003-2008 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright	(c) the authors
 * @author		Sven Skrabal <info@nexpa.de>
 * @license		GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package		Cron
 * @version		$Id$
 * @todo		errorlogging
 *				check if directory is within customer directory
 *				permissions ok!?
 *				set right file owner
 *				run with user uid/gid
 *				folder truncation/package remove/tar all files
 */

class ApsInstaller extends ApsParser
{
	private $settings = array();
	private $db = false;
	private $db_root = false;
	private $DomainPath = '';
	private $Domain = '';
	private $RealPath = '';
	private $RootDir = '';
	private $Hosts = '';

	/**
	input:
	settings		array with the global settings from syscp
	db				instance of the database class from syscp

	return:
	none
	*/
	public function __construct($settings, $db, $db_root)
	{
		$this->settings = $settings;
		$this->db = $db;
		$this->db_root = $db_root;
		$this->RootDir = dirname(dirname(__FILE__)) . '/';
		$this->Hosts = $this->settings['system']['mysql_access_host'];
	}

	/**
	input:
	none

	return:
	none
	*/
	public function InstallHandler()
	{
		chdir($this->RootDir);

		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_TASKS . '` AS `t` INNER JOIN `' . TABLE_APS_INSTANCES . '` AS `i` ON `t`.`InstanceID` = `i`.`ID` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID`');

		while($Row = $this->db->fetch_array($result))
		{
			//check for existing aps xml file
			if(!file_exists($this->RootDir . 'packages/' . $Row['Path'] . '/APP-META.xml'))
			{
				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_ERROR . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));
				continue;
			}

			//get contents and parse them
			$XmlContent = file_get_contents($this->RootDir . 'packages/' . $Row['Path'] . '/APP-META.xml');
			$Xml = new SimpleXMLElement($XmlContent);

			//check for unparseable xml data
			if($Xml == false)
			{
				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_ERROR . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));
				continue;
			}

			$Task = $Row['Task'];
			$this->DomainPath = '';
			$this->Domain = '';
			$this->RealPath = '';

			//lock instance so install cannot be canceled from the panel
			$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_TASK_ACTIVE . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));

			self::PrepareBasics($Row);
			self::PrepareDatabase($Xml, $Row, $Task);
			if(self::PrepareFiles($Xml, $Row, $Task))
			{
				self::PrepareWizardData($Xml, $Row, $Task);
				self::RunInstaller($Xml, $Row, $Task);
			}
			self::CleanupData($Xml, $Row, $Task);

			unset($Xml);
		}
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file
	row			current entry from the database for app to handle
	task		numeric code to specify what to do

	return:
	success		true
	error		false
	*/
	private function RunInstaller($Xml, $Row, $Task)
	{
		//installation
		if($Task == TASK_INSTALL)
		{
			chdir($this->RealPath . $this->DomainPath . '/install_scripts/');
			$Return = array();
			$ReturnStatus = 0;

			exec('php5 ' . escapeshellcmd($this->RealPath . $this->DomainPath . '/install_scripts/configure install'), $Return, $ReturnStatus);

			if($ReturnStatus != 0)
			{
				//write output of script on error into database for admin
				$Buffer = '';
				$Count = 0;
				foreach($Return as $Line)
				{
					$Count += 1;
					$Buffer .= $Line;
					if($Count != count($Return))$Buffer .= '<br/>';
				}

				//FIXME error logging
				echo("error : installer\n" . $Buffer . "\n");

				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_ERROR . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));
				return false;
			}
			else
			{
				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_SUCCESS . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));
				return true;
			}
		}
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file
	row			current entry from the database for app to handle
	task		numeric code to specify what to do

	return:
	none
	*/
	private function CleanupData($Xml, $Row, $Task)
	{
		chdir($this->RootDir);

		if($Task == TASK_INSTALL)
		{
			//cleanup installation
			system('rm -rf ' . escapeshellarg($this->RealPath . $this->DomainPath . '/install_scripts/'));

			//remove task
			$this->db->query('DELETE FROM `' . TABLE_APS_TASKS . '` WHERE `Task` = ' . TASK_INSTALL . ' AND `InstanceID` = ' . $this->db->escape($Row['InstanceID']));
		}
		else if($Task == TASK_REMOVE)
		{
			//FIXME cleanup installation
			//system('rm -rfI ' . escapeshellarg($this->RealPath . $this->DomainPath . '/'));

			//remove permissions
			//drop database
			$XmlDb = $Xml->requirements->children('http://apstandard.com/ns/1/db');

			if($XmlDb->db->id)
			{
				//database management
				$Database = 'web' . $Row['CustomerID'] . 'aps' . $Row['InstanceID'];

				foreach(array_map('trim', explode(',', $this->Hosts)) as $DatabaseHost)
				{
					$this->db_root->query('REVOKE ALL PRIVILEGES ON * . * FROM `' . $this->db->escape($Database) . '`@`' . $this->db->escape($DatabaseHost) . '`');
					$this->db_root->query('REVOKE ALL PRIVILEGES ON `' . $this->db->escape($Database) . '` . * FROM `' . $this->db->escape($Database) . '`@`' . $this->db->escape($DatabaseHost) . '`');
					$this->db_root->query('DELETE FROM `mysql`.`user` WHERE `User` = "' . $this->db->escape($Database) . '" AND `Host` = "' . $this->db->escape($DatabaseHost) . '"');
				}

				$this->db_root->query('DROP DATABASE IF EXISTS `' . $this->db->escape($Database) . '`');
				$this->db_root->query('FLUSH PRIVILEGES');
			}

			//remove task & delete package instance + settings
			$this->db->query('DELETE FROM `' . TABLE_APS_TASKS . '` WHERE `Task` = ' . TASK_REMOVE . ' AND `InstanceID` = ' . $this->db->escape($Row['InstanceID']));
			$this->db->query('DELETE FROM `' . TABLE_APS_INSTANCES . '` WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));
			$this->db->query('DELETE FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row['InstanceID']));
		}
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file
	row			current entry from the database for app to handle
	task		numeric code to specify what to do

	return:
	none
	*/
	private function PrepareWizardData($Xml, $Row, $Task)
	{
		//data collected by wizard
		//FIXME install_only parameter/reconfigure
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row['InstanceID']));

		while($Row2 = $this->db->fetch_array($result))
		{
			//skip APS internal data
			if($Row2['Name'] == 'main_location' || $Row2['Name'] == 'main_domain' || $Row2['Name'] == 'main_database_password' || $Row2['Name'] == 'license')continue;

			putenv('SETTINGS_' . $Row2['Name'] . '=' . $Row2['Value']);
		}
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file
	row			current entry from the database for app to handle
	task		numeric code to specify what to do

	return:
	success		true
	error		false
	*/
	private function PrepareFiles($Xml, $Row, $Task)
	{
		if($Task == TASK_INSTALL)
		{
			//FIXME truncate customer directory
			//system('rm -rfI ' . escapeshellarg($this->RealPath . $this->DomainPath . '/*'));

			if(!file_exists($this->RealPath . $this->DomainPath . '/'))mkdir($this->RealPath . $this->DomainPath . '/', 0777, true);

			if(	self::ExtractZip($this->RootDir . 'packages/' . $Row['Path'] . '/' . $Row['Path'], $Xml->mapping['path'], $this->RealPath . $this->DomainPath . '/') == false
				||
				self::ExtractZip($this->RootDir . 'packages/' . $Row['Path'] . '/' . $Row['Path'], 'scripts', $this->RealPath . $this->DomainPath . '/install_scripts/') == false )
			{
				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_ERROR . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));

				//FIXME clean up already installed data
				//system('rm -rfI ' . escapeshellarg($this->RealPath . $this->DomainPath . '/*'));

				return false;
			}
		}
		else
		{
			if(self::ExtractZip($this->RootDir . 'packages/' . $Row['Path'] . '/' . $Row['Path'], 'scripts', $this->RealPath . $this->DomainPath . '/install_scripts/') == false )
			{
				$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_ERROR . ' WHERE `ID` = ' . $this->db->escape($Row['InstanceID']));

				//clean up already installed data
				system('rm -rf ' . escapeshellarg($this->RealPath . $this->DomainPath . '/install_scripts/'));

				return false;
			}
		}

		//recursive mappings
		self::PrepareMappings($Xml->mapping, $Xml->mapping['url'], $this->RealPath . $this->DomainPath . '/');

		return true;
	}

	/**
	input:
	parentmapping	instance of parsed xml file, current mapping position
	url				relative path for application specifying the current path within the mapping tree
	path			absolute path for application specifying the current path within the mapping tree

	return:
	none
	*/
	private function PrepareMappings($ParentMapping, $Url, $Path)
	{
		//check for special PHP permissions
		//must be done with xpath otherwise check not possible (XML parser problem with attributes)
		$ParentMapping->registerXPathNamespace('p', 'http://apstandard.com/ns/1/php');
		$Result = $ParentMapping->xpath('p:permissions');

		if($Result[0]['writable'] == 'true')
		{
			//fixing file permissions to writeable
			if(is_dir($Path))
			{
				chmod($Path, 0775);
			}
			else
			{
				chmod($Path, 0664);
			}
		}

		if($Result[0]['readable'] == 'false')
		{
			//fixing file permissions to non readable
			if(is_dir($Path))
			{
				chmod($Path, 0333);
			}
			else
			{
				chmod($Path, 0222);
			}
		}

		//set environment variables
		$EnvVariable = str_replace("/", "_", $Url);
		putenv('WEB_' . $EnvVariable . '_DIR=' . $Path);

		//resolve deeper mappings
		foreach($ParentMapping->mapping as $Mapping)
		{
			//recursive check of other mappings
			if($Url == '/')
			{
				self::PrepareMappings($Mapping, $Url . $Mapping['url'], $Path . $Mapping['url']);
			}
			else
			{
				self::PrepareMappings($Mapping, $Url . '/' . $Mapping['url'], $Path . '/' . $Mapping['url']);
			}
		}
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file

	return:
	always		true
	*/
	private function PrepareBasics($Row)
	{
		//domain
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row['InstanceID']) . ' AND `Name` = "main_domain"');
		$Row3 = $this->db->fetch_array($result);
		$result2 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `id` = ' . $this->db->escape($Row3['Value']));
		$Row3 = $this->db->fetch_array($result2);
		$this->Domain = $Row3['domain'];
		$this->RealPath = $Row3['documentroot'];

		//location
		$result3 = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row['InstanceID']) . ' AND `Name` = "main_location"');
		$Row3 = $this->db->fetch_array($result3);
		$this->DomainPath = $Row3['Value'];

		//if application is directly installed on domain remove / at the end
		if($this->DomainPath == '')$this->RealPath = substr($this->RealPath, 0, strlen($this->RealPath)-1);

		//url environment variables
		putenv('BASE_URL_HOST=' . $this->Domain);
		putenv('BASE_URL_PATH=' . $this->DomainPath . '/');
		putenv('BASE_URL_SCHEME=http');

		return true;
	}

	/**
	input:
	xml			instance of a valid xml object with a parsed APP-META.xml file
	row			current entry from the database for app to handle
	task		numeric code to specify what to do

	return:
	always true
	*/
	private function PrepareDatabase($Xml, $Row, $Task)
	{
		global $db_root;

		$XmlDb = $Xml->requirements->children('http://apstandard.com/ns/1/db');

		if($XmlDb->db->id)
		{
			//database management
			$NewDatabase = 'web' . $Row['CustomerID'] . 'aps' . $Row['InstanceID'];

			$result = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row['InstanceID']) . ' AND `Name` = "main_database_password"');
			$Row3 = $this->db->fetch_array($result);
			$DbPassword = $Row3['Value'];

			if($Task == TASK_INSTALL)
			{
				$this->db_root->query('DROP DATABASE IF EXISTS `' . $this->db->escape($NewDatabase) . '`');
				$this->db_root->query('CREATE DATABASE IF NOT EXISTS `' . $this->db->escape($NewDatabase) . '`');

				foreach(array_map('trim', explode(',', $this->Hosts)) as $DatabaseHost)
				{
					$this->db_root->query('GRANT ALL PRIVILEGES ON `' . $this->db->escape($NewDatabase) . '`.* TO `' . $this->db->escape($NewDatabase) . '`@`' . $this->db->escape($DatabaseHost) . '` IDENTIFIED BY \'password\'');
					$this->db_root->query('SET PASSWORD FOR `' . $this->db->escape($NewDatabase) . '`@`' . $this->db->escape($DatabaseHost) . '` = PASSWORD(\'' . $DbPassword . '\')');
				}

				$this->db_root->query('FLUSH PRIVILEGES');
			}

			//get first mysql access host
			$AccessHosts = array_map('trim', explode(',', $this->Hosts));

			//environment variables
			putenv('DB_' . $XmlDb->db->id . '_TYPE=mysql');
			putenv('DB_' . $XmlDb->db->id . '_NAME=' . $NewDatabase);
			putenv('DB_' . $XmlDb->db->id . '_LOGIN=' . $NewDatabase);
			putenv('DB_' . $XmlDb->db->id . '_PASSWORD=' . $DbPassword);
			putenv('DB_' . $XmlDb->db->id . '_HOST=' . $AccessHosts[0]);
			putenv('DB_' . $XmlDb->db->id . '_PORT=3306');
			putenv('DB_' . $XmlDb->db->id . '_VERSION=' . mysql_get_server_info());
		}

		return true;
	}

	/**
	input:
	filename		path to zipfile to extract
	directory		which directory in zipfile to extract
	destination		destination directory for files to extract

	return:
	success			true
	error			false
	*/
	private function ExtractZip($Filename, $Directory, $Destination)
	{
		if(!file_exists($Filename))return false;

		//fix slash notation for correct paths
		if(substr($Directory, -1, 1) == '/')$Directory = substr($Directory, 0, strlen($Directory)-1);
		if(substr($Destination, -1, 1) != '/')$Destination .= '/';

		$ZipHandle = zip_open($Filename);

		if($ZipHandle)
		{
			while($ZipEntry = zip_read($ZipHandle))
			{
				if(substr(zip_entry_name($ZipEntry), 0, strlen($Directory)) == $Directory)
				{
					//fix relative path from zipfile
					$NewPath = zip_entry_name($ZipEntry);
					$NewPath = substr($NewPath, strlen($Directory));

					//directory
					if(substr($NewPath, -1, 1) == '/')
					{
						if(!file_exists($Destination . $NewPath))mkdir($Destination . $NewPath);
					}
					else
					{
						//files
						if(zip_entry_open($ZipHandle, $ZipEntry))
						{
							$File = fopen($Destination . $NewPath, "wb");

							if($File)
							{
								while($Line = zip_entry_read($ZipEntry))
								{
									fwrite($File, $Line);
								}

								fclose($File);
							}
							else
							{
								return false;
							}
						}
					}
				}
			}

			zip_close($ZipHandle);

			return true;
		}

		return false;
	}
}

?>