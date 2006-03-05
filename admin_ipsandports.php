<?php
/**
 * filename: $Source: /cvsroot/syscp/syscp/admin_domains.php,v $
 * begin: Friday, Aug 06, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Michael Duergner <michael@duergner.com>, Luca Longinotti <chtekk@gentoo.org>
 * @copyright (C) 2005-2006 Michael Duergner
 * @copyright (C) 2006 Luca Longinotti
 * @package Panel
 * @version $Id: $
 */

	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require("./lib/init.php");

	if(isset($_POST['id']))
	{
		$id=intval($_POST['id']);
	}
	elseif(isset($_GET['id']))
	{
		$id=intval($_GET['id']);
	}

	if($page == 'ipsandports' || $page == 'overview')
	{
		if($action=='')
		{
			if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$defaultid = intval($_POST['defaultipandport']);

				if($defaultid=='')
				{
					standard_error('myipdefault');
				}
				else
				{
					$db->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `default` = '0' WHERE `default` = '1'");
					$db->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `default` = '1' WHERE `id`='$defaultid'");

					redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
				}
			}
			else
			{
				$ipsandports='';
				$ipsandports_default='';
				$ipsandports_default_id='';
				$result=$db->query("SELECT `id`, `ip`, `port`, `default` FROM `".TABLE_PANEL_IPSANDPORTS."` ORDER BY `ip` ASC");
				while($row=$db->fetch_array($result))
				{
					if($row['default']=='1')
					{
						$ipsandports_default_id = $row['id'];
					}
					$ipsandports_default.=makeoption($row['ip'].'/'.$row['port'],$row['id'],$ipsandports_default_id);
					eval("\$ipsandports.=\"".getTemplate("ipsandports/ipsandports_ipandport")."\";");
				}
				eval("echo \"".getTemplate("ipsandports/ipsandports")."\";");
			}
		}

		elseif($action=='delete' && $id!=0)
		{
			$result=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE `ipandport`='$id'");
			if($result['id']=='')
			{
				$result=$db->query_first("SELECT `default` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='$id'");
				if($result['default']=='0')
				{
					$result=$db->query_first("SELECT `ip` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='$id'");
					$result2=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='$id'");
					if( ( $result['ip']!=$settings['system']['ipaddress'] ) || ( $result['ip']==$settings['system']['ipaddress'] && $result2['id']!='' ) )
					{
						$result=$db->query_first("SELECT `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='$id'");
						if($result['ip']!='')
						{
							if(isset($_POST['send']) && $_POST['send']=='send')
							{
								$db->query("DELETE FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='$id'");

								inserttask('1');
								inserttask('4');

								redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
							}
							else
							{
								ask_yesno('admin_ip_reallydelete', $filename, "id=$id;page=$page;action=$action", $result['ip'].'/'.$result['port']);
							}
						}
					}
					else
					{
						standard_error('cantdeletesystemip');
					}
				}
				else
				{
					standard_error('cantdeletedefaultip');
				}
			}
			else
			{
				standard_error('ipstillhasdomains');
			}
		}

		elseif($action=='add')
		{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$ip = addslashes($_POST['ip']);
					$port = intval($_POST['port']);

					$result=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='$ip' AND `port`='$port'");

					if($ip=='')
					{
						standard_error(array('stringisempty','myipaddress'));
					}
					elseif($port=='')
					{
						standard_error(array('stringisempty','myport'));
					}
					elseif($result['id']!='')
					{
						standard_error('myipnotdouble');
					}
					else
					{
						$db->query("INSERT INTO `".TABLE_PANEL_IPSANDPORTS."` (`ip`, `port`) VALUES ('$ip', '$port')");

						inserttask('1');
						inserttask('4');

						redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					eval("echo \"".getTemplate("ipsandports/ipsandports_add")."\";");
				}
		}

		elseif($action=='edit' && $id!=0)
		{
			$result=$db->query_first("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='$id'");
			if($result['ip']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$ip = addslashes($_POST['ip']);
					$port = intval($_POST['port']);

					$result2=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='$ip' AND `port`='$port'");
					$result3=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='$id'");

					if($ip=='')
					{
						standard_error(array('stringisempty','myipaddress'));
					}
					elseif($port=='')
					{
						standard_error(array('stringisempty','myport'));
					}
					elseif($result['ip']!=$ip && $result['ip']==$settings['system']['ipaddress'] && $result3['id']=='')
					{
						standard_error('cantchangesystemip');
					}
					elseif($result2['id']!='' && $result2['id']!=$id)
					{
						standard_error('myipnotdouble');
					}
					else
					{
						$db->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `ip`='$ip', `port`='$port' WHERE `id`='$id'");

						inserttask('1');
						inserttask('4');

						redirectTo ( $filename , Array ( 'page' => $page , 's' => $s ) ) ;
					}
				}
				else
				{
					eval("echo \"".getTemplate("ipsandports/ipsandports_edit")."\";");
				}
			}
		}
	}
?>