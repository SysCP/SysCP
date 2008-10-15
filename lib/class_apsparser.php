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
 * @package		Panel
 * @version		$Id$
 * @todo		implement charset validation
 *				reconfigure
 *				module settings
 *				patch und versionmanagement
 *				permissioncontrol admin/customer
 *				automated updates/package search/package installation
 *				directory truncation!?
 *				add https support
 *				multi language support (package localization)
 *				package clearing through admin
 *				zip stuff in own class
 *				admin/customer id as a class variable
 *				enable newest packages
 */

class ApsParser
{
	private $userinfo = array();
	private $settings = array();
	private $db = false;

	/**
	 input:
	 userinfo		global array with the current userinfos
	 settings		global array with the current system settings
	 db				valid instance of the database class
	 return:
	 none
	 */

	public function __construct($userinfo, $settings, $db)
	{
		$this->settings = $settings;
		$this->userinfo = $userinfo;
		$this->db = $db;
	}

	/**
	 input:
	 none
	 return:
	 none
	 */

	private function ManageInstances()
	{
		global $lng, $filename, $s, $page, $action;

		//INSTALL

		$InstancesInstall = '';
		$Result = $this->db->query('SELECT `p`.`Name`, `p`.`Version`, `p`.`Release`, `i`.`PackageID` FROM `' . TABLE_APS_INSTANCES . '` AS `i` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID` WHERE `i`.`Status` = ' . INSTANCE_INSTALL . ' GROUP BY `Version`, `Release`');

		while($Row = $this->db->fetch_array($Result))
		{
			eval("\$InstancesInstall.=\"" . getTemplate("aps/manage_instances_package") . "\";");

			//get instances

			$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `Status` = ' . INSTANCE_INSTALL . ' AND `PackageID` = ' . $Row['PackageID']);

			while($Row2 = $this->db->fetch_array($Result2))
			{
				//get customer name

				$Result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` = ' . $Row2['CustomerID']);
				$Row3 = $this->db->fetch_array($Result3);
				eval("\$InstancesInstall.=\"" . getTemplate("aps/manage_instances_install") . "\";");
			}
		}

		//TASK ACTIVE

		$InstancesTaskActive = '';
		$Result = $this->db->query('SELECT `p`.`Name`, `p`.`Version`, `p`.`Release`, `i`.`PackageID` FROM `' . TABLE_APS_INSTANCES . '` AS `i` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID` WHERE `i`.`Status` = ' . INSTANCE_TASK_ACTIVE . ' GROUP BY `Version`, `Release`');

		while($Row = $this->db->fetch_array($Result))
		{
			eval("\$InstancesTaskActive.=\"" . getTemplate("aps/manage_instances_package") . "\";");

			//get instances

			$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `Status` = ' . INSTANCE_TASK_ACTIVE . ' AND `PackageID` = ' . $Row['PackageID']);

			while($Row2 = $this->db->fetch_array($Result2))
			{
				//get customer name

				$Result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` = ' . $Row2['CustomerID']);
				$Row3 = $this->db->fetch_array($Result3);
				eval("\$InstancesTaskActive.=\"" . getTemplate("aps/manage_instances_taskactive") . "\";");
			}
		}

		//SUCCESS

		$InstancesSuccess = '';
		$Result = $this->db->query('SELECT `p`.`Name`, `p`.`Version`, `p`.`Release`, `i`.`PackageID` FROM `' . TABLE_APS_INSTANCES . '` AS `i` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID` WHERE `i`.`Status` = ' . INSTANCE_SUCCESS . ' GROUP BY `Version`, `Release`');

		while($Row = $this->db->fetch_array($Result))
		{
			eval("\$InstancesSuccess.=\"" . getTemplate("aps/manage_instances_package") . "\";");

			//get instances

			$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `Status` = ' . INSTANCE_SUCCESS . ' AND `PackageID` = ' . $Row['PackageID']);

			while($Row2 = $this->db->fetch_array($Result2))
			{
				//get customer name

				$Result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` = ' . $Row2['CustomerID']);
				$Row3 = $this->db->fetch_array($Result3);
				eval("\$InstancesSuccess.=\"" . getTemplate("aps/manage_instances_success") . "\";");
			}
		}

		//ERROR

		$InstancesError = '';
		$Result = $this->db->query('SELECT `p`.`Name`, `p`.`Version`, `p`.`Release`, `i`.`PackageID` FROM `' . TABLE_APS_INSTANCES . '` AS `i` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID` WHERE `i`.`Status` = ' . INSTANCE_ERROR . ' GROUP BY `Version`, `Release`');

		while($Row = $this->db->fetch_array($Result))
		{
			eval("\$InstancesError.=\"" . getTemplate("aps/manage_instances_package") . "\";");

			//get instances

			$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `Status` = ' . INSTANCE_ERROR . ' AND `PackageID` = ' . $Row['PackageID']);

			while($Row2 = $this->db->fetch_array($Result2))
			{
				//get customer name

				$Result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` = ' . $Row2['CustomerID']);
				$Row3 = $this->db->fetch_array($Result3);
				eval("\$InstancesError.=\"" . getTemplate("aps/manage_instances_error") . "\";");
			}
		}

		//UNINSTALL

		$InstancesUninstall = '';
		$Result = $this->db->query('SELECT `p`.`Name`, `p`.`Version`, `p`.`Release`, `i`.`PackageID` FROM `' . TABLE_APS_INSTANCES . '` AS `i` INNER JOIN `' . TABLE_APS_PACKAGES . '` AS `p` ON `i`.`PackageID` = `p`.`ID` WHERE `i`.`Status` = ' . INSTANCE_UNINSTALL . ' GROUP BY `Version`, `Release`');

		while($Row = $this->db->fetch_array($Result))
		{
			eval("\$InstancesUninstall.=\"" . getTemplate("aps/manage_instances_package") . "\";");

			//get instances

			$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `Status` = ' . INSTANCE_UNINSTALL . ' AND `PackageID` = ' . $Row['PackageID']);

			while($Row2 = $this->db->fetch_array($Result2))
			{
				//get customer name

				$Result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_CUSTOMERS . '` WHERE `customerid` = ' . $Row2['CustomerID']);
				$Row3 = $this->db->fetch_array($Result3);
				eval("\$InstancesUninstall.=\"" . getTemplate("aps/manage_instances_uninstall") . "\";");
			}
		}

		eval("echo \"" . getTemplate("aps/manage_instances") . "\";");
	}

	/**
	 input:
	 dir			directory to delete recursive
	 return:
	 none
	 */

	private function UnlinkRecursive($Dir)
	{
		if(!$DirHandle = @opendir($Dir))return;

		while(false !== ($Object = readdir($DirHandle)))
		{
			if($Object == '.'
			   || $Object == '..')continue;

			if(!@unlink($Dir . '/' . $Object))
			{
				UnlinkRecursive($Dir . '/' . $Object);
			}
		}

		closedir($DirHandle);
		@rmdir($Dir);
	}

	/**
	 input:
	 none
	 return:
	 none
	 */

	private function ManagePackages()
	{
		global $lng, $filename, $s, $page, $action;
		$Question = false;

		if(isset($_POST['save']))
		{
			if(isset($_POST['all'])
			   && $_POST['all'] == 'lock')
			{
				$this->db->query('UPDATE `' . TABLE_APS_PACKAGES . '` SET `Status` = ' . PACKAGE_LOCKED . ' WHERE 1');
			}
			else

			if(isset($_POST['all'])
			   && $_POST['all'] == 'unlock')
			{
				$this->db->query('UPDATE `' . TABLE_APS_PACKAGES . '` SET `Status` = ' . PACKAGE_ENABLED . ' WHERE 1');
			}
			else

			if(isset($_POST['all'])
			   && $_POST['all'] == 'remove')
			{
				if(isset($_POST['answer'])
				   && $_POST['answer'] == $lng['panel']['yes'])
				{
					$Result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '`');

					while($Row = $this->db->fetch_array($Result))
					{
						self::UnlinkRecursive('./packages/' . $Row['Path']);
					}

					$this->db->query('DELETE FROM `' . TABLE_APS_PACKAGES . '` WHERE 1');
				}
				else
				{
					//show yes/no question

					$Output = $lng['question']['reallyremovepackages'];
					$Output.= '<form name="continue" action="' . $filename . '" method="post"><input type="submit" name="answer" value="' . $lng['panel']['yes'] . '" /><input type="hidden" name="save" value="1"/><input type="hidden" name="s" value="' . $s . '"/><input type="hidden" name="action" value="' . $action . '"/><input type="hidden" name="all" value="remove"/>';
					$Output.= '</form><br/><form name="back" action="' . $filename . '" method="post"><input type="submit" name="submit" value="' . $lng['panel']['no'] . '" /><input type="hidden" name="action" value="' . $action . '"/><input type="hidden" name="s" value="' . $s . '"/></form><br/>';
					self::InfoBox($Output);
					$Question = true;
				}
			}
			else
			{
				$Result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '`');
				$RemoveIds = '';

				while($Row = $this->db->fetch_array($Result))
				{
					//set new status of package

					if($Row['Status'] == PACKAGE_ENABLED
					   && isset($_POST['lock' . $Row['ID']]))
					{
						$this->db->query('UPDATE `' . TABLE_APS_PACKAGES . '` SET `Status` = ' . PACKAGE_LOCKED . ' WHERE `ID` = ' . $this->db->escape($Row['ID']));
					}

					//set new status of package

					if($Row['Status'] == PACKAGE_LOCKED
					   && isset($_POST['unlock' . $Row['ID']]))
					{
						$this->db->query('UPDATE `' . TABLE_APS_PACKAGES . '` SET `Status` = ' . PACKAGE_ENABLED . ' WHERE `ID` = ' . $this->db->escape($Row['ID']));
					}

					//save id of package to remove

					if(isset($_POST['remove' . $Row['ID']]))
					{
						$RemoveIds.= '<input type="hidden" name="remove' . $Row['ID'] . '" value="1"/>';

						//remove package if answer is yes

						if(isset($_POST['answer'])
						   && $_POST['answer'] == $lng['panel']['yes'])
						{
							self::UnlinkRecursive('./packages/' . $Row['Path']);
							$this->db->query('DELETE FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($Row['ID']));
						}
					}
				}

				//if there are some ids to remove, show yes/no box

				if($RemoveIds != ''
				   && !isset($_POST['answer']))
				{
					//show yes/no question

					$Output = $lng['question']['reallyremovepackages'];
					$Output.= '<form name="continue" action="' . $filename . '" method="post"><input type="submit" name="answer" value="' . $lng['panel']['yes'] . '" /><input type="hidden" name="save" value="1"/><input type="hidden" name="s" value="' . $s . '"/><input type="hidden" name="action" value="' . $action . '"/>';
					$Output.= $RemoveIds;
					$Output.= '</form><br/><form name="back" action="' . $filename . '" method="post"><input type="submit" name="submit" value="' . $lng['panel']['no'] . '" /><input type="hidden" name="action" value="' . $action . '"/><input type="hidden" name="s" value="' . $s . '"/></form><br/>';
					self::InfoBox($Output);
					$Question = true;
				}
			}
		}

		//show package overview with options

		if(!isset($_POST['save'])
		   || $Question == false)
		{
			$Result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` GROUP BY `Name` ORDER BY `Name` ASC');
			$Packages = '';

			while($Row = $this->db->fetch_array($Result))
			{
				eval("\$Packages.=\"" . getTemplate("aps/manage_packages_row") . "\";");
				$Result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Name` = "' . $this->db->escape($Row['Name']) . '" ORDER BY `Version` DESC, `Release` DESC');

				while($Row2 = $this->db->fetch_array($Result2))
				{
					$Lock = '';

					if($Row2['Status'] == PACKAGE_ENABLED)
					{
						$Lock = makecheckbox('lock' . $Row2['ID'], '', '1');
					}

					$Unlock = '';

					if($Row2['Status'] == PACKAGE_LOCKED)
					{
						$Unlock = makecheckbox('unlock' . $Row2['ID'], '', '1');
					}

					$Remove = makecheckbox('remove' . $Row2['ID'], '', '1');
					eval("\$Packages.=\"" . getTemplate("aps/manage_packages_detail") . "\";");
				}
			}

			if($this->db->num_rows($Result) == 0)
			{
				self::InfoBox($lng['aps']['nopackagesinsystem']);
			}
			else
			{
				eval("echo \"" . getTemplate("aps/manage_packages") . "\";");
			}
		}
	}

	/**
	 input:
	 none
	 return:
	 none
	 */

	private function UploadNewPackages()
	{
		global $lng, $filename, $s, $page, $action;

		//define how many files can be uploaded at once

		$Files = array();

		//define how many upload fields will be shown

		for ($i = 1;$i <= (int)$this->settings['aps']['upload_fields'];$i++)
		{
			$Files[] = 'file' . $i;
		}

		//check whether one file has been uploaded

		$FilesSet = false;
		foreach($Files as $File)
		{
			if(isset($_FILES[$File]))$FilesSet = true;
		}

		if($FilesSet == true)
		{
			//any file has been uploaded, now check for errors ans parse the files

			foreach($Files as $File)
			{
				if(isset($_FILES[$File]))
				{
					$Errors = array();

					//check uploaded files against some things

					if(substr($_FILES[$File]['name'], -3) != 'zip'
					   && $_FILES[$File]['error'] == 0)
					{
						$Errors[] = $lng['aps']['notazipfile'];
					}

					if(($_FILES[$File]['size'] > self::PhpMemorySizeToBytes(ini_get('upload_max_filesize')) && $_FILES[$File]['error'] == 0)
					   || $_FILES[$File]['error'] == 1)
					{
						$Errors[] = $lng['aps']['filetoobig'];
					}

					if($_FILES[$File]['error'] == 3)
					{
						$Errors[] = $lng['aps']['filenotcomplete'];
					}

					if($_FILES[$File]['error'] >= 6)
					{
						$Errors[] = $lng['aps']['phperror'] . (int)$_FILES[$File]['error'];
					}

					//all checks are ok, try to install the package

					if(count($Errors) == 0
					   && $_FILES[$File]['error'] == 0)
					{
						if(move_uploaded_file($_FILES[$File]['tmp_name'], './temp/' . basename($_FILES[$File]['name'])) == true)
						{
							self::InstallNewPackage('./temp/' . basename($_FILES[$File]['name']));
						}
						else
						{
							$Errors[] = $lng['aps']['moveproblem'];
						}
					}

					if(count($Errors) > 0)
					{
						$ErrorMessage = '';
						foreach($Errors as $Error)
						{
							$ErrorMessage.= '<li>' . $Error . '</li>';
						}

						self::InfoBox(sprintf($lng['aps']['uploaderrors'], htmlspecialchars($_FILES[$File]['name']), $ErrorMessage));
					}
				}
			}
		}

		$Output = '';
		foreach($Files as $File)
		{
			$Output.= '<input size="60" name="' . $File . '" type="file" /><br/><br/>';
		}

		eval("echo \"" . getTemplate("aps/upload") . "\";");
	}

	/**
	 input:
	 none
	 return:
	 none
	 */

	private function SearchPackages()
	{
		global $lng, $filename, $s, $page, $action;
		$Error = 0;
		$Ids = array();
		$ShowAll = 0;

		if(isset($_GET['keyword'])
		   && preg_match('/^[- _0-9a-z\.,הצü:;]+$/i', $_GET['keyword']) != false)
		{
			//split all keywords

			$Elements = split('[ ,;]', trim($_GET['keyword']));

			if(count($Elements) == 1
			   && strlen($Elements[0]) == 0)
			{
				//no keyword given - show all packages

				$ShowAll = 1;
			}
			else
			{
				foreach($Elements as $Key)
				{
					//skip empty values - prevents that whitespaces lead to the result that all packages will be found

					if($Key == '')continue;
					$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED . ' AND (`Name` LIKE "%' . $this->db->escape($Key) . '%" OR `Path` LIKE "%' . $this->db->escape($Key) . '%" OR `Version` LIKE "%' . $this->db->escape($Key) . '%") ');

					//check if keyword got a result

					if($this->db->num_rows($result) > 0)
					{
						//add all package ids which match to result array

						while($Temp = $this->db->fetch_array($result))
						{
							if(!in_array($Temp['ID'], $Ids))$Ids[] = $Temp['ID'];
						}
					}
				}

				//no matches found to given keywords

				if(count($Ids) == 0)
				{
					$Error = 2;
				}
			}
		}
		else

		if(isset($_GET['keyword'])
		   && strlen($_GET['keyword']) != 0)
		{
			//input contains illegal characters

			$Error = 1;
		}
		else

		if(isset($_GET['keyword'])
		   && strlen($_GET['keyword']) == 0)
		{
			//nothing has been entered - show all packages

			$ShowAll = 1;
		}

		//show errors

		if($Error == 1)
		{
			self::InfoBox($lng['aps']['nospecialchars']);
		}
		else

		if($Error == 2)
		{
			self::InfoBox($lng['aps']['noitemsfound']);
		}

		$Keyword = '';

		if(isset($_GET['keyword'])
		   && $Error == 0)$Keyword = htmlspecialchars($_GET['keyword']);
		eval("echo \"" . getTemplate("aps/search") . "\";");

		//show results

		if(($Error == 0 && count($Ids) > 0)
		   || $ShowAll == 1)
		{
			//run query based on search results

			if($ShowAll != 1)
			{
				$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` IN (' . $this->db->escape(implode(',', $Ids)) . ')');
			}
			else
			{
				$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED);
			}

			if($this->db->num_rows($result) > 0)
			{
				if($this->db->num_rows($result) == 1)
				{
					self::InfoBox(sprintf($lng['aps']['searchoneresult'], $this->db->num_rows($result)));
				}
				else
				{
					self::InfoBox(sprintf($lng['aps']['searchmultiresult'], $this->db->num_rows($result)));
				}

				while($Row = $this->db->fetch_array($result))
				{
					self::ShowPackageInfo($Row['ID']);
				}
			}
		}
	}

	/**
	 input:
	 customerid	id of customer from database
	 return:
	 none
	 */

	private function CustomerStatus($CustomerId)
	{
		global $lng, $filename, $s, $page, $action;
		$Data = '';
		$Fieldname = '';
		$Fieldvalue = '';
		$Groupname = '';
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId));

		if($this->db->num_rows($result) == 0)
		{
			self::InfoBox($lng['aps']['nopackagesinstalled']);
			return;
		}

		while($Row = $this->db->fetch_array($result))
		{
			$Data = '';
			$result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($Row['PackageID']));
			$Row2 = $this->db->fetch_array($result2);
			$Xml = self::GetXmlFromFile('./packages/' . $Row2['Path'] . '/APP-META.xml');

			//skip if parsing of xml has failed

			if($Xml == false)continue;
			$Icon = './images/default.png';

			if($Xml->icon['path'])
			{
				$Icon = './packages/' . $Row2['Path'] . '/' . basename($Xml->icon['path']);
			}

			$Summary = $Xml->summary;
			$Fieldname = $lng['aps']['version'];
			$Fieldvalue = $Xml->version . ' (Release ' . $Xml->release . ')';
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			$Temp = '';

			switch($Row['Status'])
			{
			case INSTANCE_INSTALL:
				$Temp.= $lng['aps']['instance_install'];
				break;
			case INSTANCE_TASK_ACTIVE:
				$Temp.= $lng['aps']['instance_task_active'];
				break;
			case INSTANCE_SUCCESS:
				$Temp.= $lng['aps']['instance_success'];
				break;
			case INSTANCE_ERROR:
				$Temp.= $lng['aps']['instance_error'];
				break;
			case INSTANCE_UNINSTALL:
				$Temp.= $lng['aps']['instance_uninstall'];
				break;
			default:
				$Temp.= $lng['aps']['unknown_status'];
				break;
			}

			$Fieldname = $lng['aps']['currentstatus'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			$result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_TASKS . '` WHERE InstanceID = ' . $this->db->escape($Row['ID']));
			$Temp = '';

			if($this->db->num_rows($result2) > 0)
			{
				while($Row2 = $this->db->fetch_array($result2))
				{
					switch($Row2['Task'])
					{
					case TASK_INSTALL:
						$Temp.= $lng['aps']['task_install'] . '<br/>';
						break;
					case TASK_REMOVE:
						$Temp.= $lng['aps']['task_remove'] . '<br/>';
						break;
					case TASK_RECONFIGURE:
						$Temp.= $lng['aps']['task_reconfigure'] . '<br/>';
						break;
					case TASK_UPGRADE:
						$Temp.= $lng['aps']['task_upgrade'] . '<br/>';
					default:
						$Temp.= $lng['aps']['unknown_status'] . '<br/>';
						break;
					}
				}
			}
			else
			{
				$Temp.= $lng['aps']['no_task'];
			}

			$Fieldname = $lng['aps']['activetasks'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");

			//show entrypoints

			if($Row['Status'] == INSTANCE_SUCCESS)
			{
				$Temp = '';

				//get domain to domain id

				$result3 = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `Name` = "main_domain" AND `InstanceID` = ' . $this->db->escape($Row['ID']));
				$Row3 = $this->db->fetch_array($result3);
				$result4 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = ' . $this->db->escape($CustomerId) . ' AND `id` = ' . $this->db->escape($Row3['Value']));
				$Row3 = $this->db->fetch_array($result4);
				$Domain = $Row3['domain'];

				//get sub location for domain

				$result5 = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `Name` = "main_location" AND `InstanceID` = ' . $this->db->escape($Row['ID']));
				$Row3 = $this->db->fetch_array($result5);
				$Location = $Row3['Value'];

				//show main site link

				if($Location == '')
				{
					$Temp.= '<a href="http://' . $Domain . '/" target="_blank">' . $lng['aps']['mainsite'] . '</a><br/>';
				}
				else
				{
					$Temp.= '<a href="http://' . $Domain . '/' . $Location . '/" target="_blank">' . $lng['aps']['mainsite'] . '</a><br/>';
				}

				//show other links from meta data

				if($Xml->{'entry-points'})
				{
					foreach($Xml->{'entry-points'}->entry as $Entry)
					{
						if($Location == '')
						{
							$Temp.= '<a href="http://' . $Domain . $Entry->path . '" target="_blank">' . $Entry->label . '</a><br/>';
						}
						else
						{
							$Temp.= '<a href="http://' . $Domain . '/' . $Location . $Entry->path . '" target="_blank">' . $Entry->label . '</a><br/>';
						}
					}
				}

				$Fieldname = $lng['aps']['applicationlinks'];
				$Fieldvalue = $Temp;
				eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			}

			eval("echo \"" . getTemplate("aps/package_status") . "\";");
			unset($Xml);
		}
	}

	/**
	 input:
	 packageid	id of package from database
	 customerid	id of customer from database
	 return:
	 error		false
	 success		none
	 */

	private function CreatePackageInstance($PackageId, $CustomerId)
	{
		global $lng;

		if(!self::IsValidPackageId($PackageId, true))return false;

		//has user pressed F5/reload?

		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId));

		if($this->db->num_rows($result) == 0)
		{
			self::InfoBox($lng['aps']['erroronnewinstance']);
			return false;
		}

		//get path to package xml file

		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($PackageId));
		$Row = $this->db->fetch_array($result);
		$Xml = self::GetXmlFromFile('./packages/' . $Row['Path'] . '/APP-META.xml');

		if($Xml == false)return false;

		//add new instance

		$this->db->query('INSERT INTO `' . TABLE_APS_INSTANCES . '` (`CustomerID`, `PackageID`, `Status`) VALUES (' . $this->db->escape($CustomerId) . ', ' . $this->db->escape($PackageId) . ', ' . INSTANCE_INSTALL . ')');
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `PackageID` = ' . $this->db->escape($PackageId) . ' AND `Status` = ' . INSTANCE_INSTALL . ' ORDER BY ID DESC');
		$Row = $this->db->fetch_array($result);

		//copy & delete temp data

		$this->db->query('INSERT INTO `' . TABLE_APS_SETTINGS . '` (`InstanceID`, `Name`, `Value`) SELECT ' . $this->db->escape($Row['ID']) . ' AS `InstanceID`, `Name`, `Value` FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `PackageID` = ' . $this->db->escape($PackageId));
		$this->db->query('DELETE FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `PackageID` = ' . $this->db->escape($PackageId));

		//add task for installation

		$this->db->query('INSERT INTO `' . TABLE_APS_TASKS . '` (`InstanceID`, `Task`) VALUES(' . $this->db->escape($Row['ID']) . ', ' . TASK_INSTALL . ')');
		self::InfoBox(sprintf($lng['aps']['successonnewinstance'], $Xml->name));
		unset($Xml);
	}

	/**
	 input:
	 value		ini_get() formated memory size
	 return:
	 always		memory size in bytes
	 */

	private function PhpMemorySizeToBytes($Value)
	{
		//based on code from php.net

		$Value = trim($Value);
		$Last = strtolower($Value{strlen($Value) - 1});

		switch($Last)
		{
		case 'g':
			$Value*= 1024;
		case 'm':
			$Value*= 1024;
		case 'k':
			$Value*= 1024;
		}

		return $Value;
	}

	/**
	 input:
	 value		value to read from php.ini (format: safe_mode or safe-mode)
	 return:
	 always		(true|false) as string
	 */

	private function TrueFalseIniGet($Value)
	{
		$Value = ini_get(str_replace(array(
			'-'
		), array(
			'_'
		), $Value));

		if($Value == 0
		   || $Value == false
		   || $Value == 'off')
		{
			return 'false';
		}
		else
		{
			return 'true';
		}
	}

	/**
	 input:
	 category		category as string to check
	 item			item within category to check
	 return:
	 success			true (value has exception)
	 error			false (value has no exception)
	 */

	private function CheckException($Category, $Item, $Value)
	{
		global $settings;
		$Elements = explode(',', $settings['aps'][$Category . '-' . $Item]);
		foreach($Elements as $Element)
		{
			if(strtolower($Element) == strtolower($Value))return true;
		}

		return false;
	}

	/**
	 input:
	 parentmapping	instance of parsed xml file, current mapping position
	 url				relative path for application specifying the current path within the mapping tree
	 return:
	 always			array with errors found, optional empty when no errors were found
	 */

	private function CheckSubmappings($ParentMapping, $Url)
	{
		global $lng;
		$Error = array();

		//check for special PHP handler extensions

		$XmlPhpMapping = $ParentMapping->children('http://apstandard.com/ns/1/php');
		foreach($XmlPhpMapping->handler as $Handler)
		{
			if($Handler->extension != 'php')
			{
				$Error[] = $lng['aps']['php_misc_handler'];
			}

			if(isset($Handler->disabled))
			{
				$Error[] = $lng['aps']['php_misc_directoryhandler'];
			}
		}

		//check for special ASP.NET url handler within mappings

		$XmlAspMapping = $ParentMapping->children('http://apstandard.com/ns/1/aspnet');

		if($XmlAspMapping->handler)
		{
			$Error[] = $lng['aps']['asp_net'];
		}

		//check for special CGI url handlers within mappings

		$XmlCgiMapping = $ParentMapping->children('http://apstandard.com/ns/1/cgi');

		if($XmlCgiMapping->handler)
		{
			$Error[] = $lng['aps']['cgi'];
		}

		//resolve deeper mappings

		foreach($ParentMapping->mapping as $Mapping)
		{
			$Return = array();

			//recursive check of other mappings

			if($Url == '/')
			{
				$Return = self::CheckSubmappings($Mapping, $Url . $Mapping['url']);
			}
			else
			{
				$Return = self::CheckSubmappings($Mapping, $Url . '/' . $Mapping['url']);
			}

			//if recursive checks found errors attach them

			if(count($Return) != 0)
			{
				foreach($Return as $Value)
				{
					if(!in_array($Value, $Error))$Error[] = $Value;
				}
			}
		}

		return $Error;
	}

	/**
	 input:
	 filename		path to zipfile to install
	 return:
	 none
	 */

	private function InstallNewPackage($Filename)
	{
		global $lng;

		if(file_exists($Filename)
		   && $Xml = self::GetXmlFromZip($Filename))
		{
			$Error = array();

			//check alot of stuff if package is supported
			//php modules

			$XmlPhp = $Xml->requirements->children('http://apstandard.com/ns/1/php');

			if($XmlPhp->extension)
			{
				$ExtensionsLoaded = get_loaded_extensions();
				foreach($XmlPhp->extension as $Extension)
				{
					if(!in_array($Extension, $ExtensionsLoaded)
					   && !self::CheckException('php', 'extension', $Extension))
					{
						$Error[] = sprintf($lng['aps']['php_extension'], $Extension);
					}
				}
			}

			//php functions

			if($XmlPhp->function)
			{
				foreach($XmlPhp->function as $Function)
				{
					if(!function_exists($Function)
					   && !self::CheckException('php', 'function', $Function))
					{
						$Error[] = sprintf($lng['aps']['php_function'], $Function);
					}
				}
			}

			//php values

			$PhpValues = array(
				'short-open-tag',
				'file-uploads',
				'magic-quotes-gpc',
				'register-globals',
				'allow-url-fopen',
				'safe-mode'
			);
			foreach($PhpValues as $Value)
			{
				if($XmlPhp->{$Value})
				{
					if(self::TrueFalseIniGet($Value) != $XmlPhp->{$Value}
					   && !self::CheckException('php', 'configuration', str_replace(array(
						'-'
					), array(
						'_'
					), $Value)))
					{
						$Error[] = sprintf($lng['aps']['php_configuration'], str_replace(array(
							'-'
						), array(
							'_'
						), $Value));
					}
				}
			}

			if($XmlPhp->{'post-max-size'})
			{
				if(self::PhpMemorySizeToBytes(ini_get('post_max_size')) < intval($XmlPhp->{'post-max-size'})
				   && !self::CheckException('php', 'configuration', 'post_max_size'))
				{
					$Error[] = $lng['aps']['php_configuration_post_max_size'];
				}
			}

			if($XmlPhp->{'memory-limit'})
			{
				if(self::PhpMemorySizeToBytes(ini_get('memory_limit')) < intval($XmlPhp->{'memory-limit'})
				   && !self::CheckException('php', 'configuration', 'memory_limit'))
				{
					$Error[] = $lng['aps']['php_configuration_memory_limit'];
				}
			}

			if($XmlPhp->{'max-execution-time'})
			{
				if(ini_get('max_execution_time') < intval($XmlPhp->{'max-execution-time'})
				   && !self::CheckException('php', 'configuration', 'max_execution_time'))
				{
					$Error[] = $lng['aps']['php_configuration_max_execution_time'];
				}
			}

			//php version
			//must be done with xpath otherwise check not possible (XML parser problem with attributes)

			$Xml->registerXPathNamespace('phpversion', 'http://apstandard.com/ns/1/php');
			$Result = $Xml->xpath('//phpversion:version');

			if(isset($Result[0]['min']))
			{
				if(version_compare($Result[0]['min'], PHP_VERSION) == 1)
				{
					$Error[] = $lng['aps']['php_general_old'];
				}
			}

			if(isset($Result[0]['max-not-including']))
			{
				if(version_compare($Result[0]['max-not-including'], PHP_VERSION) == - 1)
				{
					$Error[] = $lng['aps']['php_general_new'];
				}
			}

			//database

			$XmlDb = $Xml->requirements->children('http://apstandard.com/ns/1/db');

			if($XmlDb->db->id)
			{
				if($XmlDb->db->{'server-type'} != 'mysql')
				{
					$Error[] = $lng['aps']['db_mysql_support'];
				}

				if(version_compare($XmlDb->db->{'server-min-version'}, mysql_get_server_info()) == 1)
				{
					$Error[] = $lng['aps']['db_mysql_version'];
				}
			}

			//ASP.NET

			$XmlAsp = $Xml->requirements->children('http://apstandard.com/ns/1/aspnet');

			if($XmlAsp->handler
			   || $XmlAsp->permissions
			   || $XmlAsp->version)
			{
				$Error[] = $lng['aps']['asp_net'];
			}

			//CGI

			$XmlCgi = $Xml->requirements->children('http://apstandard.com/ns/1/cgi');

			if($XmlCgi->handler)
			{
				$Error[] = $lng['aps']['cgi'];
			}

			//webserver modules

			$XmlWebserver = $Xml->requirements->children('http://apstandard.com/ns/1/apache');

			if($XmlWebserver->{'required-module'})
			{
				if(function_exists('apache_get_modules'))
				{
					$ModulesLoaded = apache_get_modules();
					foreach($XmlWebserver->{'required-module'} as $Module)
					{
						if(!in_array($Module, $ModulesLoaded)
						   && !self::CheckException('webserver', 'module', $Module))
						{
							$Error[] = sprintf($lng['aps']['webserver_module'], $Module);
						}
					}
				}
				else
				{
					if(!self::CheckException('webserver', 'module', 'fcgid-any'))$Error[] = $lng['aps']['webserver_fcgid'];
				}
			}

			//webserver .htaccess

			if($XmlWebserver->htaccess
			   && !self::CheckException('webserver', 'htaccess', 'htaccess'))
			{
				$Error[] = $lng['aps']['webserver_htaccess'];
			}

			//configuration script check

			if($Xml->{'configuration-script-language'}
			   && $Xml->{'configuration-script-language'} != 'php')
			{
				$Error[] = $lng['aps']['misc_configscript'];
			}

			//validation against a charset not possible in current version

			foreach($Xml->settings->group as $Group)
			{
				foreach($Group->setting as $Setting)
				{
					if($Setting['type'] == 'string'
					   || $Setting['type'] == 'password')
					{
						if(isset($Setting['charset']))
						{
							if(!in_array($lng['aps']['misc_charset'], $Error))$Error[] = $lng['aps']['misc_charset'];
						}
					}
				}
			}

			//check different errors/features in submappings

			$Return = self::CheckSubmappings($Xml->mapping, $Xml->mapping['url']);

			if(count($Return) != 0)
			{
				foreach($Return as $Value)
				{
					if(!in_array($Value, $Error))$Error[] = $Value;
				}
			}

			//check already installed versions

			$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Name` = "' . $this->db->escape($Xml->name) . '"');
			$Newer = 0;

			if($this->db->num_rows($result) > 0)
			{
				while($Row = $this->db->fetch_array($result))
				{
					if(version_compare($Row['Version'] . '-' . $Row['Release'], $Xml->version . '-' . $Xml->release) == - 1)
					{
						$Newer = 1;
					}

					if(version_compare($Row['Version'] . '-' . $Row['Release'], $Xml->version . '-' . $Xml->release) == 0)
					{
						$Error[] = $lng['aps']['misc_version_already_installed'];
						break;
					}

					if(version_compare($Row['Version'] . '-' . $Row['Release'], $Xml->version . '-' . $Xml->release) == 1)
					{
						$Error[] = $lng['aps']['misc_only_newer_versions'];
						break;
					}
				}
			}

			if(count($Error) > 0)
			{
				$Output = '';
				foreach($Error as $Entry)
				{
					$Output.= '<li>' . $Entry . '</li>';
				}

				self::InfoBox(sprintf($lng['aps']['erroronscan'], $Xml->name, $Output));
				unlink($Filename);
				return false;
			}
			else
			{
				$Destination = './packages/' . basename($Filename) . '/';

				//create package directory

				if(!file_exists($Destination))mkdir($Destination, 0777, true);

				//copy xml meta data

				self::GetContentFromZip($Filename, 'APP-META.xml', $Destination . 'APP-META.xml');

				//copy screenshots

				if($Xml->screenshot)
				{
					foreach($Xml->screenshot as $Screenshot)
					{
						self::GetContentFromZip($Filename, $Screenshot['path'], $Destination . basename($Screenshot['path']));
					}
				}

				//copy icon

				if($Xml->icon['path'])
				{
					self::GetContentFromZip($Filename, $Xml->icon['path'], $Destination . basename($Xml->icon['path']));
				}

				//copy license

				if($Xml->license
				   && $Xml->license->text->file)
				{
					self::GetContentFromZip($Filename, $Xml->license->text->file, $Destination . 'license.txt');
				}

				//insert package to database

				$this->db->query('INSERT INTO `' . TABLE_APS_PACKAGES . '` (`Path`, `Name`, `Version`, `Release`, `Status`) VALUES ("' . $this->db->escape(basename($Filename)) . '", "' . $this->db->escape($Xml->name) . '", "' . $this->db->escape($Xml->version) . '", ' . $this->db->escape($Xml->release) . ', ' . PACKAGE_LOCKED . ')');

				//copy zipfile do destination

				copy($Filename, $Destination . basename($Filename));
				unlink($Filename);

				if($Newer == 1)
				{
					self::InfoBox(sprintf($lng['aps']['successpackageupdate'], $Xml->name));
				}
				else
				{
					self::InfoBox(sprintf($lng['aps']['successpackageinstall'], $Xml->name));
				}

				unset($Xml);
				return true;
			}
		}
		else
		{
			self::InfoBox(sprintf($lng['aps']['invalidzipfile'], basename($Filename)));
			unlink($Filename);
			return false;
		}
	}

	/**
	 input:
	 none
	 return:
	 none
	 */

	public function MainHandler($Action)
	{
		global $lng, $filename, $s, $page, $action, $Id;

		//check for basic functions, classes and permissions

		$Error = '';

		if(!class_exists('SimpleXMLElement')
		   || !function_exists('zip_open'))
		{
			$Error.= '<li>' . $lng['aps']['class_zip_missing'] . '</li>';
		}

		if(!is_writable('./temp/')
		   || !is_writable('./packages/'))
		{
			$Error.= '<li>' . $lng['aps']['dir_permissions'] . '</li>';
		}

		if($Error != '')
		{
			self::InfoBox(sprintf($lng['aps']['initerror'], $Error));
			return;
		}

		$CustomerId = $this->userinfo['customerid'];
		$AdminId = $this->userinfo['adminid'];
		$PackagesPerSite = $this->settings['aps']['items_per_page'];

		if($Action == 'install')
		{
			if(self::IsValidPackageId($Id, true))
			{
				if(isset($_POST['withinput']))
				{
					$Errors = self::ValidatePackageData($Id, $CustomerId);

					if(count($Errors) == 0)
					{
						self::CreatePackageInstance($Id, $CustomerId);
					}
					else
					{
						self::ShowPackageInstaller($Id, $Errors, $CustomerId);
					}
				}
				else
				{
					//empty array -> no errors

					$Errors = array();
					self::ShowPackageInstaller($Id, $Errors, $CustomerId);
				}
			}
			else
			{
				self::InfoBox($lng['aps']['iderror']);
			}
		}
		else

		if($Action == 'remove')
		{
			if(self::IsValidInstanceId($Id, $CustomerId))
			{
				if(isset($_GET['answer'])
				   && $_GET['answer'] == $lng['panel']['yes'])
				{
					//check if there is already an task

					$result = $this->db->query('SELECT * FROM `' . TABLE_APS_TASKS . '` WHERE `InstanceID` = ' . $this->db->escape($Id) . ' AND `Task` = ' . TASK_REMOVE);

					if($this->db->num_rows($result) > 0)
					{
						self::InfoBox($lng['aps']['removetaskexisting']);
					}
					else
					{
						$this->db->query('INSERT INTO `' . TABLE_APS_TASKS . '` (`InstanceID`, `Task`) VALUES (' . $this->db->escape($Id) . ', ' . TASK_REMOVE . ')');
						$this->db->query('UPDATE `' . TABLE_APS_INSTANCES . '` SET `Status` = ' . INSTANCE_UNINSTALL . ' WHERE `ID` = ' . $this->db->escape($Id));
						self::InfoBox($lng['aps']['packagewillberemoved']);
					}
				}
				else
				{
					$Output = $lng['question']['reallywanttoremove'];
					$Output.= '<form name="continue" action="' . $filename . '" method="get"><input type="submit" name="answer" value="' . $lng['panel']['yes'] . '" /><input type="hidden" name="id" value="' . htmlspecialchars($Id) . '"/><input type="hidden" name="s" value="' . $s . '"/><input type="hidden" name="action" value="remove"/></form><br/>';
					$Output.= '<form name="back" action="' . $filename . '" method="get"><input type="submit" name="submit" value="' . $lng['panel']['no'] . '" /><input type="hidden" name="action" value="customerstatus"/><input type="hidden" name="s" value="' . $s . '"/></form>';
					self::InfoBox($Output);
				}
			}
			else
			{
				self::InfoBox($lng['aps']['iderror']);
			}
		}
		else

		if($Action == 'stopinstall')
		{
			if(self::IsValidInstanceId($Id, $CustomerId))
			{
				$Result = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `ID` = ' . $this->db->escape($Id));
				$Row = $this->db->fetch_array($Result);

				if($Row['Status'] == INSTANCE_TASK_ACTIVE)
				{
					self::InfoBox($lng['aps']['installstoperror']);
				}
				else
				{
					if(isset($_GET['answer'])
					   && $_GET['answer'] == $lng['panel']['yes'])
					{
						//remove task

						$this->db->query('DELETE FROM `' . TABLE_APS_TASKS . '` WHERE `InstanceID` = ' . $this->db->escape($Id));

						//remove settings

						$this->db->query('DELETE FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Id));

						//remove instance

						$this->db->query('DELETE FROM `' . TABLE_APS_INSTANCES . '` WHERE `ID` = ' . $this->db->escape($Id));
						self::InfoBox($lng['aps']['installstopped']);
					}
					else
					{
						$Output = $lng['question']['reallywanttostop'];
						$Output.= '<form name="continue" action="' . $filename . '" method="get"><input type="submit" name="answer" value="' . $lng['panel']['yes'] . '" /><input type="hidden" name="id" value="' . htmlspecialchars($Id) . '"/><input type="hidden" name="s" value="' . $s . '"/><input type="hidden" name="action" value="stopinstall"/></form><br/>';
						$Output.= '<form name="back" action="' . $filename . '" method="get"><input type="submit" name="submit" value="' . $lng['panel']['no'] . '" /><input type="hidden" name="action" value="customerstatus"/><input type="hidden" name="s" value="' . $s . '"/></form>';
						self::InfoBox($Output);
					}
				}
			}
			else
			{
				self::InfoBox($lng['aps']['iderror']);
			}
		}
		else

		if($Action == 'reconfigure')
		{
			if(self::IsValidInstanceId($Id, $CustomerId))
			{
				self::InfoBox('FIXME reconfigure');
			}
			else
			{
				self::InfoBox($lng['aps']['iderror']);
			}
		}
		else

		if($Action == 'details')
		{
			if(self::IsValidPackageId($Id, true))
			{
				self::ShowPackageInfo($Id, true);
			}
			else
			{
				self::InfoBox($lng['aps']['iderror']);
			}
		}
		else

		if($Action == 'scan')
		{
			$Files = scandir('./temp/');
			$Counter = 0;
			foreach($Files as $File)
			{
				if($File == '.'
				   || $File == '..'
				   || $File == '.svn')continue;
				self::InstallNewPackage('./temp/' . $File);
				$Counter+= 1;
			}

			if($Counter == 0)
			{
				self::InfoBox($lng['aps']['nopacketsforinstallation']);
			}
		}
		else

		if($Action == 'manageinstances')
		{
			self::ManageInstances();
		}
		else

		if($Action == 'managepackages')
		{
			self::ManagePackages();
		}
		else

		if($Action == 'upload')
		{
			self::UploadNewPackages();
		}
		else

		if($Action == 'customerstatus')
		{
			self::CustomerStatus($CustomerId);
		}
		else

		if($Action == 'search')
		{
			self::SearchPackages();
		}
		else

		if($Action == 'overview')
		{
			if(isset($_GET['page'])
			   && preg_match('/^[0-9]+$/', $_GET['page']) != - 1)
			{
				$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED);
				$Pages = intval($this->db->num_rows($result) / $PackagesPerSite);

				if(($this->db->num_rows($result) / $PackagesPerSite) > $Pages)$Pages+= 1;

				if($_GET['page'] >= 1
				   && $_GET['page'] <= $Pages)
				{
					$result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED . ' LIMIT ' . $this->db->escape((intval($_GET['page']) - 1) * $PackagesPerSite) . ', ' . $this->db->escape($PackagesPerSite));

					while($Row3 = $this->db->fetch_array($result2))
					{
						self::ShowPackageInfo($Row3['ID']);
					}

					if($Pages > 1)
					{
						echo ('<div style="width: 90%; text-align: center;"><br/>');
						for ($i = 1;$i < $Pages + 1;$i++)
						{
							if($i == $_GET['page'])
							{
								echo ('<span class="pageitem">' . $i . '</span>');
							}
							else
							{
								echo ('<span class="pageitem"><a href="' . $filename . '?s=' . $s . '&amp;action=overview&amp;page=' . $i . '">' . $i . '</a></span>');
							}
						}

						echo ('</div><br/><br/>');
					}
				}
				else
				{
					$result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED . ' LIMIT 0, ' . $this->db->escape($PackagesPerSite));

					while($Row3 = $this->db->fetch_array($result2))
					{
						self::ShowPackageInfo($Row3['ID']);
					}

					$result3 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED);
					$Pages = intval($this->db->num_rows($result3) / $PackagesPerSite);

					if(($this->db->num_rows($result3) / $PackagesPerSite) > $Pages)$Pages+= 1;

					if($Pages > 1)
					{
						echo ('<div style="width: 90%; text-align: center;"><br/>');
						for ($i = 1;$i < $Pages + 1;$i++)
						{
							echo ('<span class="pageitem"><a href="' . $filename . '?s=' . $s . '&amp;action=overview&amp;page=' . $i . '">' . $i . '</a></span>');
						}

						echo ('</div>');
					}
				}
			}
			else
			{
				$result2 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED . ' LIMIT 0, ' . $this->db->escape($PackagesPerSite));

				if($this->db->num_rows($result2) == 0)
				{
					self::InfoBox($lng['aps']['nopackagestoinstall']);
					return;
				}

				while($Row3 = $this->db->fetch_array($result2))
				{
					self::ShowPackageInfo($Row3['ID']);
				}

				$result3 = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED);
				$Pages = intval($this->db->num_rows($result3) / $PackagesPerSite);

				if(($this->db->num_rows($result3) / $PackagesPerSite) > $Pages)$Pages+= 1;

				if($Pages > 1)
				{
					echo ('<div style="width: 90%; text-align: center;"><br/>');
					for ($i = 1;$i < $Pages + 1;$i++)
					{
						if($i == 1)
						{
							echo ('<span class="pageitem">' . $i . '</span>');
						}
						else
						{
							echo ('<span class="pageitem"><a href="' . $filename . '?s=' . $s . '&amp;action=overview&amp;page=' . $i . '">' . $i . '</a></span>');
						}
					}

					echo ('</div><br/><br/>');
				}
			}
		}
	}

	/**
	 input:
	 filename		zipfile to read data from
	 file			file within zip archive to read
	 destination		optional parameter where to save file from within the zip file
	 return:
	 success			content of file from zip archive
	 error			false
	 */

	private function GetContentFromZip($Filename, $File, $Destination = '')
	{
		if(!file_exists($Filename))return false;
		$ZipHandle = zip_open($Filename);
		$Content = '';

		if($ZipHandle)
		{
			while($ZipEntry = zip_read($ZipHandle))
			{
				if(zip_entry_name($ZipEntry) == $File)
				{
					if(zip_entry_open($ZipHandle, $ZipEntry))
					{
						while($Line = zip_entry_read($ZipEntry))
						{
							$Content.= $Line;
						}
					}

					break;
				}
			}

			zip_close($ZipHandle);
		}
		else
		{
			return false;
		}

		if($Content == '')
		{
			return false;
		}
		else
		{
			if($Destination == '')
			{
				return $Content;
			}
			else
			{
				$File = fopen($Destination, "wb");

				if($File)
				{
					fwrite($File, $Content);
					fclose($File);
				}
				else
				{
					return false;
				}
			}
		}
	}

	/**
	 input:
	 filename		zipfile containing the xml meta data
	 return:
	 success			parsed xml content of zipfile
	 error			false
	 */

	private function GetXmlFromZip($Filename)
	{
		if(!file_exists($Filename))return false;

		if($XmlContent = self::GetContentFromZip($Filename, 'APP-META.xml'))
		{
			$Xml = new SimpleXMLElement($XmlContent);
			return $Xml;
		}
		else
		{
			return false;
		}
	}

	/**
	 input:
	 filename		xmlfile to parse
	 return:
	 success			parsed xml content of file
	 error			false
	 */

	private function GetXmlFromFile($Filename)
	{
		if(!file_exists($Filename))return false;
		$XmlContent = file_get_contents($Filename);
		$Xml = new SimpleXMLElement($XmlContent);
		return $Xml;
	}

	/**
	 input:
	 packageid		id of package from database to check vor validity
	 customer		check if package can be used by customer and/or admin [true|false]
	 return:
	 success			valid package id (id > 0 based on database layout)
	 error			false
	 */

	private function IsValidPackageId($PackageId, $Customer)
	{
		if(preg_match('/^[0-9]+$/', $PackageId) != 1)return false;

		if($Customer == true)
		{
			$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `Status` = ' . PACKAGE_ENABLED . ' AND `ID` = ' . $this->db->escape($PackageId));

			if($this->db->num_rows($result) > 0)
			{
				return $PackageId;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($PackageId));

			if($this->db->num_rows($result) > 0)
			{
				return $PackageId;
			}
			else
			{
				return false;
			}
		}
	}

	/**
	 input:
	 packageid		id of instance from database to check vor validity
	 return:
	 success			valid instance id (id > 0 based on database layout)
	 error			false
	 */

	private function IsValidInstanceId($InstanceId, $CustomerId)
	{
		if(preg_match('/^[0-9]+$/', $InstanceId) != 1)return false;
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `ID` = ' . $this->db->escape($InstanceId));

		if($this->db->num_rows($result) > 0)
		{
			return $InstanceId;
		}
		else
		{
			return false;
		}
	}

	/**
	 input:
	 packageid		id of package from database
	 customerid		id of customer for who the values should be saved
	 name			name of field to save
	 value			value to save for previously given name
	 return:
	 none
	 */

	private function SetInstallationValue($PackageId, $CustomerId, $Name, $Value)
	{
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `PackageID` = ' . $this->db->escape($PackageId) . ' AND `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `Name` = "' . $this->db->escape($Name) . '"');

		if($this->db->num_rows($result) == 0)
		{
			$this->db->query('INSERT INTO `' . TABLE_APS_TEMP_SETTINGS . '` (`PackageID`, `CustomerID`, `Name`, `Value`) VALUES (' . $this->db->escape($PackageId) . ', ' . $this->db->escape($CustomerId) . ', "' . $this->db->escape($Name) . '", "' . $this->db->escape($Value) . '")');
		}
		else
		{
			$Row = $this->db->fetch_array($result);
			$this->db->query('UPDATE `' . TABLE_APS_TEMP_SETTINGS . '` SET `PackageID` = ' . $this->db->escape($PackageId) . ', `CustomerID` = ' . $this->db->escape($CustomerId) . ', `Name` = "' . $this->db->escape($Name) . '", `Value` ="' . $this->db->escape($Value) . '" WHERE `ID` = ' . $this->db->escape($Row['ID']));
		}
	}

	/**
	 input:
	 packageid		id of package from database
	 customerid		id of customer for who the values should be read
	 name			name of field to read
	 return:
	 success			value of field from database
	 error			false
	 */

	private function GetInstallationValue($PackageId, $CustomerId, $Name)
	{
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `PackageID` = ' . $this->db->escape($PackageId) . ' AND `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `Name` = "' . $this->db->escape($Name) . '"');

		if($this->db->num_rows($result) == 0)
		{
			return false;
		}
		else
		{
			$Row = $this->db->fetch_array($result);
			return $Row['Value'];
		}
	}

	/**
	 input:
	 path		path which could be compromised
	 return:
	 always		corrected path
	 */

	private function MakeSecurePath($path)
	{
		//code based on syscp core code

		$search = Array(
			'#/+#',
			'#\.+#',
			'#\0+#',
			'#\\\\+#'
		);
		$replace = Array(
			'/',
			'',
			'',
			'/'
		);
		$path = preg_replace($search, $replace, $path);

		//no / at the end

		if(substr($path, strlen($path) - 1, 1) == '/')$path = substr($path, 0, strlen($path) - 1);

		//no / at beginning

		if(substr($path, 0, 1) == '/')$path = substr($path, 1);
		return $path;
	}

	/**
	 input:
	 packageid		id of package from database
	 customerid		id of customer from database
	 return:
	 success/error	array of errors found containing fields that were wrong
	 */

	private function ValidatePackageData($PackageId, $CustomerId)
	{
		if(!self::IsValidPackageId($PackageId, true))return false;
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($PackageId));
		$Row = $this->db->fetch_array($result);
		$Xml = self::GetXmlFromFile('./packages/' . $Row['Path'] . '/APP-META.xml');

		if($Xml == false)return false;
		$Error = array();
		foreach($Xml->settings->group as $Group)
		{
			foreach($Group->setting as $Setting)
			{
				$FieldId = strval($Setting['id']);

				if($Setting['type'] == 'string'
				   || $Setting['type'] == 'password')
				{
					if(isset($_POST[$FieldId]))
					{
						if(isset($Setting['min-length'])
						   && strlen($_POST[$FieldId]) < $Setting['min-length'])
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['max-length'])
						   && strlen($_POST[$FieldId]) > $Setting['max-length'])
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['regex'])
						   && !preg_match("/" . $Setting['regex'] . "/", $_POST[$FieldId]))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						/*
						else if(isset($Setting['charset']))
						{
							//CHARSET VALIDATION FOR LATER VERSIONS
						}
						*/

						if(!in_array($FieldId, $Error))self::SetInstallationValue($PackageId, $CustomerId, $FieldId, $_POST[$FieldId]);
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
				else

				if($Setting['type'] == 'email')
				{
					if(isset($_POST[$FieldId]))
					{
						if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $_POST[$FieldId]))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(!in_array($FieldId, $Error))self::SetInstallationValue($PackageId, $CustomerId, $FieldId, $_POST[$FieldId]);
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
				else

				if($Setting['type'] == 'domain-name')
				{
					if(isset($_POST[$FieldId]))
					{
						if(!preg_match("^(http|https)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&%\$#\=~_\-]+))*$", $_POST[$FieldId]))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(!in_array($FieldId, $Error))self::SetInstallationValue($PackageId, $CustomerId, $FieldId, $_POST[$FieldId]);
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
				else

				if($Setting['type'] == 'integer')
				{
					if(isset($_POST[$FieldId]))
					{
						//check if number is in the format of float

						if(round($_POST[$FieldId]) != $_POST[$FieldId])
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(!is_numeric($_POST[$FieldId]))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['min'])
						   && intval($_POST[$FieldId]) < intval($Setting['min']))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['max'])
						   && intval($_POST[$FieldId]) > intval($Setting['max']))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(!in_array($FieldId, $Error))self::SetInstallationValue($PackageId, $CustomerId, $FieldId, intval($_POST[$FieldId]));
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
				else

				if($Setting['type'] == 'float')
				{
					if(isset($_POST[$FieldId]))
					{
						if(!is_numeric($_POST[$FieldId]))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['min'])
						   && floatval($_POST[$FieldId]) < floatval($Setting['min']))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(isset($Setting['max'])
						   && floatval($_POST[$FieldId]) > floatval($Setting['max']))
						{
							if(!in_array($FieldId, $Error))$Error[] = $FieldId;
						}

						if(!in_array($FieldId, $Error))self::SetInstallationValue($PackageId, $CustomerId, $FieldId, floatval($_POST[$FieldId]));
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
				else

				if($Setting['type'] == 'boolean')
				{
					if(isset($_POST[$FieldId])
					   && $_POST[$FieldId] == 'true')
					{
						self::SetInstallationValue($PackageId, $CustomerId, $FieldId, 'true');
					}
					else
					{
						self::SetInstallationValue($PackageId, $CustomerId, $FieldId, 'false');
					}
				}
				else

				if($Setting['type'] == 'enum')
				{
					if(isset($_POST[$FieldId]))
					{
						foreach($Setting->choice as $Choice)
						{
							if($Choice['id'] == $_POST[$FieldId])
							{
								self::SetInstallationValue($PackageId, $CustomerId, $FieldId, $_POST[$FieldId]);
								break;
							}
						}
					}
					else
					{
						if(!in_array($FieldId, $Error))$Error[] = $FieldId;
					}
				}
			}
		}

		//database required?

		$XmlDb = $Xml->requirements->children('http://apstandard.com/ns/1/db');

		if($XmlDb->db->id)
		{
			if(isset($_POST['main_database_password']))
			{
				if(strlen($_POST['main_database_password']) < 8)
				{
					if(!in_array('main_database_password', $Error))$Error[] = 'main_database_password';
				}

				if(!in_array('main_database_password', $Error))self::SetInstallationValue($PackageId, $CustomerId, 'main_database_password', $_POST['main_database_password']);
			}
			else
			{
				if(!in_array('main_database_password', $Error))$Error[] = 'main_database_password';
			}
		}

		//application location

		if(isset($_POST['main_domain']))
		{
			$result2 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = ' . $this->db->escape($CustomerId) . ' AND `id` = ' . $this->db->escape($_POST['main_domain']));

			if($this->db->num_rows($result2) > 0)
			{
				self::SetInstallationValue($PackageId, $CustomerId, 'main_domain', $_POST['main_domain']);
			}
			else
			{
				self::SetInstallationValue($PackageId, $CustomerId, 'main_domain', '-1');

				if(!in_array('main_domain', $Error))$Error[] = 'main_domain';
			}
		}
		else
		{
			self::SetInstallationValue($PackageId, $CustomerId, 'main_domain', '-1');

			if(!in_array('main_domain', $Error))$Error[] = 'main_domain';
		}

		if(isset($_POST['main_location']))
		{
			$Temp = $_POST['main_location'];

			//call function twice for security reasons!

			$Temp = self::MakeSecurePath($Temp);
			$Temp = self::MakeSecurePath($Temp);

			//previous instance check

			$InstallPath = '';

			//find real path for selected domain

			$result3 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = ' . $this->db->escape($CustomerId) . ' AND `id` = ' . $this->db->escape(self::GetInstallationValue($PackageId, $CustomerId, 'main_domain')));

			if($this->db->num_rows($result3) > 0)
			{
				//build real path

				$Row = $this->db->fetch_array($result3);
				$InstallPath = $Row['documentroot'];
				$InstallPath.= $Temp;
				$result4 = $this->db->query('SELECT * FROM `' . TABLE_APS_INSTANCES . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId));

				//get all instances from current customer

				while($Row3 = $this->db->fetch_array($result4))
				{
					//get all domains linked to instances

					$result5 = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row3['ID']) . ' AND `Name` = "main_domain"');

					if($this->db->num_rows($result5) > 0)
					{
						$Row2 = $this->db->fetch_array($result5);
						$Reserved = '';

						//get real domain path

						$result6 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = ' . $this->db->escape($CustomerId) . ' AND `id` = ' . $this->db->escape($Row2['Value']));

						if($this->db->num_rows($result6) > 0)
						{
							$Row = $this->db->fetch_array($result6);
							$Reserved = $Row['documentroot'];
						}

						//get location under domain

						$result7 = $this->db->query('SELECT * FROM `' . TABLE_APS_SETTINGS . '` WHERE `InstanceID` = ' . $this->db->escape($Row3['ID']) . ' AND `Name` = "main_location"');

						if($this->db->num_rows($result7) > 0)
						{
							$Row = $this->db->fetch_array($result7);
							$Reserved.= $Row['Value'];
						}

						//check whether there is another app installed already

						if($Reserved == $InstallPath)
						{
							if(!in_array('main_location', $Error))$Error[] = 'main_location';
							break;
						}
					}
				}
			}

			if(!in_array('main_location', $Error))self::SetInstallationValue($PackageId, $CustomerId, 'main_location', $Temp);
		}
		else
		{
			if(!in_array('main_location', $Error))$Error[] = 'main_location';
			self::SetInstallationValue($PackageId, $CustomerId, 'main_location', '');
		}

		if($Xml->license)
		{
			if($Xml->license['must-accept']
			   && $Xml->license['must-accept'] == 'true')
			{
				if(isset($_POST['license'])
				   && $_POST['license'] == 'true')
				{
					self::SetInstallationValue($PackageId, $CustomerId, 'license', 'true');
				}
				else
				{
					self::SetInstallationValue($PackageId, $CustomerId, 'license', 'false');

					if(!in_array('license', $Error))$Error[] = 'license';
				}
			}
		}

		unset($Xml);
		return $Error;
	}

	/**
	 input:
	 packageid		id of package from database
	 wrongdata		array of fields that were wrong in previous validation check
	 return:
	 error			false
	 success			none
	 */

	private function ShowPackageInstaller($PackageId, $WrongData, $CustomerId)
	{
		global $lng, $filename, $s, $page, $action;
		$Data = '';
		$Fieldname = '';
		$Fieldvalue = '';
		$Groupname = '';

		if(!self::IsValidPackageId($PackageId, true))return false;
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($PackageId));
		$Row = $this->db->fetch_array($result);
		$Xml = self::GetXmlFromFile('./packages/' . $Row['Path'] . '/APP-META.xml');

		//return if parse of xml file has failed

		if($Xml == false)return false;
		$Icon = './images/default.png';

		if($Xml->icon['path'])
		{
			$Icon = './packages/' . $Row['Path'] . '/' . basename($Xml->icon['path']);
		}

		//show error message if some input was wrong

		$ErrorMessage = 0;

		if(count($WrongData) != 0)
		{
			$ErrorMessage = 1;
		}

		//remove previously entered values if new installation has been triggered

		if(!isset($_POST['withinput']))
		{
			$this->db->query('DELETE FROM `' . TABLE_APS_TEMP_SETTINGS . '` WHERE `CustomerID` = ' . $this->db->escape($CustomerId) . ' AND `PackageID` = ' . $this->db->escape($PackageId));
		}

		//where to install app?

		$Temp = 'http://<select size="1" name="main_domain">';
		$Value = self::GetInstallationValue($PackageId, $CustomerId, 'main_domain');
		$result2 = $this->db->query('SELECT * FROM `' . TABLE_PANEL_DOMAINS . '` WHERE `customerid` = ' . $this->db->escape($CustomerId));

		//if customer has no domains, app cannot be installed
		//FIXME

		if($this->db->num_rows($result2) > 0)
		{
			while($Row3 = $this->db->fetch_array($result2))
			{
				if($Value)
				{
					if($Row3['ID'] == $Value)
					{
						$Temp.= '<option selected="selected" value="' . $Row3['id'] . '">' . $Row3['domain'] . '</option>';
					}
					else
					{
						$Temp.= '<option value="' . $Row3['id'] . '">' . $Row3['domain'] . '</option>';
					}
				}
				else
				{
					$Temp.= '<option value="' . $Row3['id'] . '">' . $Row3['domain'] . '</option>';
				}
			}
		}
		else
		{
			$Temp.= '<option value="-1">' . $lng['aps']['no_domains'] . '</option>';
		}

		$Temp.= '</select>';
		$Value = self::GetInstallationValue($PackageId, $CustomerId, 'main_location');

		if($Value)
		{
			$Temp.= '/<input type="text" name="main_location" value="' . $Value . '"/>';
		}
		else
		{
			$Temp.= '/<input type="text" name="main_location" value=""/>';
		}

		if(in_array('main_domain', $WrongData))
		{
			$Temp.= self::FieldError($lng['aps']['nodomains']);
		}

		if(in_array('main_location', $WrongData))
		{
			$Temp.= self::FieldError($lng['aps']['wrongpath']);
		}

		if(!in_array('main_location', $WrongData)
		   && !in_array('main_domain', $WrongData))$Temp.= '<br/>';
		$Temp.= '<em>' . $lng['aps']['application_location_description'] . '</em>';
		$Groupname = $lng['aps']['basic_settings'];
		$Fieldname = $lng['aps']['application_location'];
		$Fieldvalue = $Temp;
		eval("\$Data.=\"" . getTemplate("aps/data") . "\";");

		//database required?

		$XmlDb = $Xml->requirements->children('http://apstandard.com/ns/1/db');

		if($XmlDb->db->id)
		{
			$Temp = '<input type="password" name="main_database_password" />';

			if(in_array('main_database_password', $WrongData))
			{
				$Temp.= self::FieldError($lng['aps']['dbpassword']);
			}

			if(!in_array('main_database_password', $WrongData))$Temp.= '<br/>';
			$Temp.= '<em>' . $lng['aps']['database_password_description'] . '</em>';
			$Groupname = '';
			$Fieldname = $lng['aps']['database_password'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
		}

		foreach($Xml->settings->group as $Group)
		{
			$GroupPrinted = false;
			foreach($Group->setting as $Setting)
			{
				$Temp = '';

				if($Setting['type'] == 'string'
				   || $Setting['type'] == 'email'
				   || $Setting['type'] == 'integer'
				   || $Setting['type'] == 'float'
				   || $Setting['type'] == 'domain-name')
				{
					if(!$GroupPrinted)
					{
						$Groupname = $Group->name;
						$GroupPrinted = true;
					}
					else
					{
						$Groupname = '';
					}

					$Value = self::GetInstallationValue($PackageId, $CustomerId, strval($Setting['id']));

					if($Value)
					{
						$Temp.= '<input type="text" name="' . $Setting['id'] . '" value="' . $Value . '"/>';
					}
					else

					if($Setting['default-value'])
					{
						$Temp.= '<input type="text" name="' . $Setting['id'] . '" value="' . $Setting['default-value'] . '"/>';
					}
					else
					{
						$Temp.= '<input type="text" name="' . $Setting['id'] . '" />';
					}

					if(in_array($Setting['id'], $WrongData))
					{
						if($Setting->{'error-message'})
						{
							$Temp.= self::FieldError($Setting->{'error-message'});
						}
						else
						{
							if($Setting['type'] == 'string')
							{
								$Temp.= self::FieldError($lng['aps']['error_text']);
							}
							else

							if($Setting['type'] == 'email')
							{
								$Temp.= self::FieldError($lng['aps']['error_email']);
							}
							else

							if($Setting['type'] == 'domain-name')
							{
								$Temp.= self::FieldError($lng['aps']['domain']);
							}
							else

							if($Setting['type'] == 'integer')
							{
								$Temp.= self::FieldError($lng['aps']['error_integer']);
							}
							else

							if($Setting['type'] == 'float')
							{
								$Temp.= self::FieldError($lng['aps']['error_float']);
							}
						}
					}
				}
				else

				if($Setting['type'] == 'password')
				{
					if(!$GroupPrinted)
					{
						$Groupname = $Group->name;
						$GroupPrinted = true;
					}
					else
					{
						$Groupname = '';
					}

					$Temp.= '<input type="password" name="' . $Setting['id'] . '" />';

					if(in_array($Setting['id'], $WrongData))
					{
						if($Setting->{'error-message'})
						{
							$Temp.= self::FieldError($Setting->{'error-message'});
						}
						else
						{
							$Temp.= self::FieldError($lng['aps']['error_password']);
						}
					}
				}
				else

				if($Setting['type'] == 'boolean')
				{
					if(!$GroupPrinted)
					{
						$Groupname = $Group->name;
						$GroupPrinted = true;
					}
					else
					{
						$Groupname = '';
					}

					$Value = GetInstallationValue($PackageId, $CustomerId, strval($Setting['id']));

					if($Value)
					{
						if($Value == 'true')
						{
							$Temp.= '<input checked="checked" name="' . $Setting['id'] . '" type="checkbox" value="true" />';
						}
						else
						{
							$Temp.= '<input name="' . $Setting['id'] . '" type="checkbox" value="true" />';
						}
					}
					else

					if($Setting['default-value'] == 'true')
					{
						$Temp.= '<input checked="checked" name="' . $Setting['id'] . '" type="checkbox" value="true" />';
					}
					else
					{
						$Temp.= '<input name="' . $Setting['id'] . '" type="checkbox" value="true" />';
					}
				}
				else

				if($Setting['type'] == 'enum')
				{
					if(!$GroupPrinted)
					{
						$Groupname = $Group->name;
						$GroupPrinted = true;
					}
					else
					{
						$Groupname = '';
					}

					$Temp.= '<select size="1" name="' . $Setting['id'] . '">';
					$Value = self::GetInstallationValue($PackageId, $CustomerId, strval($Setting['id']));
					foreach($Setting->choice as $Choice)
					{
						if($Value)
						{
							if(strval($Choice['id']) == $Value)
							{
								$Temp.= '<option selected="selected" value="' . $Choice['id'] . '">' . $Choice->name . '</option>';
							}
							else
							{
								$Temp.= '<option value="' . $Choice['id'] . '">' . $Choice->name . '</option>';
							}
						}
						else

						if(strval($Choice['id']) == strval($Setting['default-value']))
						{
							$Temp.= '<option selected="selected" value="' . $Choice['id'] . '">' . $Choice->name . '</option>';
						}
						else
						{
							$Temp.= '<option value="' . $Choice['id'] . '">' . $Choice->name . '</option>';
						}
					}

					$Temp.= '</select>';
				}

				//default field description

				if(isset($Setting->description))
				{
					if(!in_array(strval($Setting['id']), $WrongData))$Temp.= '<br/>';
					$Temp.= '<em>' . $Setting->description . '</em>';
				}

				$Fieldname = $Setting->name;
				$Fieldvalue = $Temp;
				eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			}
		}

		if($Xml->license)
		{
			$Temp = '';

			if($Xml->license['must-accept']
			   && $Xml->license['must-accept'] == 'true')
			{
				if($Xml->license->text->name)$Temp.= $Xml->license->text->name . '<br/>';

				if($Xml->license->text->file)
				{
					$Temp.= '<textarea name="text" rows="10" cols="55">';
					$FileContent = file_get_contents('./packages/' . $Row['Path'] . '/license.txt');
					$Temp.= htmlentities($FileContent, ENT_QUOTES, 'ISO-8859-1');
					$Temp.= '</textarea>';
					$Groupname = $lng['aps']['license'];
					$Fieldname = $lng['aps']['license'];
					$Fieldvalue = $Temp;
					eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
				}
				else
				{
					$Temp.= '<a target="_blank" href="' . htmlspecialchars($Xml->license->text->url) . '">Link to License</a>';
					$Groupname = $lng['aps']['license'];
					$Fieldname = $lng['aps']['license'];
					$Fieldvalue = $Temp;
					eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
				}

				$Temp = '<input name="license" type="checkbox" value="true" /> ' . $lng['aps']['error_license'];

				if(in_array('license', $WrongData))
				{
					$Temp.= self::FieldError($lng['aps']['error_licensenoaccept']);
				}

				$Groupname = '';
				$Fieldname = $lng['aps']['license_agreement'];
				$Fieldvalue = $Temp;
				eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			}
		}

		eval("echo \"" . getTemplate("aps/installer") . "\";");
		unset($Xml);
	}

	/**
	 input:
	 packageid		id of package from database
	 mode			verbosity of data to view (basic|advanced)
	 return:
	 error			false
	 success			none
	 */

	private function ShowPackageInfo($PackageId, $All = false)
	{
		global $lng, $filename, $s, $page, $action;
		$Data = '';
		$Fieldname = '';
		$Fieldvalue = '';
		$Groupname = '';

		if(!self::IsValidPackageId($PackageId, true))return false;
		$result = $this->db->query('SELECT * FROM `' . TABLE_APS_PACKAGES . '` WHERE `ID` = ' . $this->db->escape($PackageId));
		$Row = $this->db->fetch_array($result);
		$Xml = self::GetXmlFromFile('./packages/' . $Row['Path'] . '/APP-META.xml');

		//return if parse of xml file has failed

		if($Xml == false)return false;
		$Icon = './images/default.png';

		if($Xml->icon['path'])
		{
			$Icon = './packages/' . $Row['Path'] . '/' . basename($Xml->icon['path']);
		}

		$Summary = htmlspecialchars($Xml->summary);
		$Fieldname = $lng['aps']['version'];
		$Fieldvalue = $Xml->version . ' (Release ' . $Xml->release . ')';
		eval("\$Data.=\"" . getTemplate("aps/data") . "\";");

		if($Xml->homepage)
		{
			$Fieldname = $lng['aps']['homepage'];
			$Fieldvalue = '<a target="_blank" href="' . htmlspecialchars($Xml->homepage) . '">' . htmlspecialchars($Xml->homepage) . '</a>';
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
		}

		if($Xml->{'installed-size'})
		{
			$Fieldname = $lng['aps']['installed_size'];
			$Fieldvalue = 'ca. ' . number_format(intval($Xml->{'installed-size'}) / (1024 * 1024), 2) . ' MB';
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
		}

		if($Xml->categories)
		{
			$Temp = '';
			foreach($Xml->categories->category as $Categories)
			{
				$Temp.= htmlspecialchars($Categories[0]) . '<br/>';
			}

			$Fieldname = $lng['aps']['categories'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
		}

		if($Xml->languages)
		{
			$Temp = '';
			foreach($Xml->languages->language as $Languages)
			{
				$Temp.= $Languages[0] . ' ';
			}

			$Fieldname = $lng['aps']['languages'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
		}

		if($All == true)
		{
			$Fieldname = $lng['aps']['long_description'];
			$Fieldvalue = htmlspecialchars($Xml->description);
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");

			if($Xml->{'configuration-script-language'})
			{
				$Fieldname = $lng['aps']['configscript'];
				$Fieldvalue = $Xml->{'configuration-script-language'};
				eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			}

			$Temp = '<ul>';
			foreach($Xml->changelog->version as $Versions)
			{
				$Temp.= '<li><strong>' . $Versions['version'] . ' (Release ' . $Versions['release'] . ')</strong>';
				$Temp.= '<ul>';
				foreach($Versions->entry as $Entries)
				{
					$Temp.= '<li>' . $Entries[0] . '</li>';
				}

				$Temp.= '</ul></li>';
			}

			$Temp.= '</ul>';
			$Fieldname = $lng['aps']['changelog'];
			$Fieldvalue = $Temp;
			eval("\$Data.=\"" . getTemplate("aps/data") . "\";");

			if($Xml->license)
			{
				if($Xml->license->text->file)
				{
					$Temp = '';

					if($Xml->license->text->name)$Temp = $Xml->license->text->name . '<br/>';
					$Temp.= '<form name="license" action="#"><textarea name="text" rows="10" cols="70">';
					$FileContent = file_get_contents('./packages/' . $Row['Path'] . '/license.txt');
					$Temp.= htmlentities($FileContent, ENT_QUOTES, 'ISO-8859-1');
					$Temp.= '</textarea></form>';
					$Fieldname = $lng['aps']['license'];
					$Fieldvalue = $Temp;
					eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
				}
				else
				{
					$Fieldname = $lng['aps']['license'];
					$Fieldvalue = '<a target="_blank" href="' . htmlspecialchars($Xml->license->text->url) . '">' . $lng['aps']['license_link'] . '</a>';
					eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
				}
			}

			if($Xml->screenshot)
			{
				$Count = 0;
				$Temp = '';
				foreach($Xml->screenshot as $Screenshot)
				{
					$Count+= 1;
					$Temp.= '<img src="./packages/' . $Row['Path'] . '/' . basename($Screenshot['path']) . '" alt="' . $Screenshot->description . '"/><br/><em>' . $Screenshot->description . '</em><br/>';

					if(count($Xml->screenshot) != $Count)$Temp.= '<br/>';
				}

				$Fieldname = $lng['aps']['screenshots'];
				$Fieldvalue = $Temp;
				eval("\$Data.=\"" . getTemplate("aps/data") . "\";");
			}
		}

		eval("echo \"" . getTemplate("aps/package") . "\";");
		unset($Xml);
	}

	private function InfoBox($Message)
	{
		global $lng, $filename, $s, $page, $action;
		eval("echo \"" . getTemplate("aps/infobox") . "\";");
	}

	private function FieldError($Error)
	{
		return '<div class="fielderror">' . $Error . '</div>';
	}
}

?>