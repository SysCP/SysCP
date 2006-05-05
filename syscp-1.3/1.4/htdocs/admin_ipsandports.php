<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @author     Michael Duergner <michael@duergner.com>
 * @author     Luca Longinotti <chtekk@gentoo.org>
 * @copyright  (c) 2005-2006 Michael Duergner
 * @copyright  (c) 2006 Luca Longinotti
 * @package    Org.Syscp.Core
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: install.php 337 2006-02-20 12:46:59Z martin $
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 * @todo we should consider removing this files and put the logic into the settings logic
 */
	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';

//	if(isset($_POST['id']))
//	{
//		$id=intval($_POST['id']);
//	}
//	elseif(isset($_GET['id']))
//	{
//		$id=intval($_GET['id']);
//	}

	if($config->get('env.page') == 'ipsandports' || $config->get('env.page') == 'overview')
	{
		if($config->get('env.action')=='')
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

					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
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
					$ipsandports_default.=makeoption($row['ip'].':'.$row['port'],$row['id'],$ipsandports_default_id);
					eval("\$ipsandports.=\"".getTemplate("ipsandports/ipsandports_ipandport")."\";");
				}
				eval("echo \"".getTemplate("ipsandports/ipsandports")."\";");
			}
		}

		elseif($config->get('env.action')=='delete' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_DOMAINS."` WHERE `ipandport`='".$config->get('env.id')."'");
			if($result['id']=='')
			{
				$result=$db->query_first("SELECT `default` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$config->get('env.id')."'");
				if($result['default']=='0')
				{
					$result=$db->query_first("SELECT `ip` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$config->get('env.id')."'");
					$result2=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='".$config->get('env.id')."'");
					if( ( $result['ip']!=$config->get('system.ipaddress') ) || ( $result['ip']==$config->get('system.ipaddress') && $result2['id']!='' ) )
					{
						$result=$db->query_first("SELECT `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$config->get('env.id')."'");
						if($result['ip']!='')
						{
							if(isset($_POST['send']) && $_POST['send']=='send')
							{
								$db->query("DELETE FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$config->get('env.id')."'");

								$hooks->call( 'core.deleteIPPort', 
								              array( 'id' => $config->get('env.id') ) );

								redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
							}
							else
							{
								ask_yesno('admin_ip_reallydelete', $config->get('env.filename'), "id=".$config->get('env.id').";page=".$config->get('env.page').";action=".$config->get('env.action'), $result['ip'].':'.$result['port']);
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

		elseif($config->get('env.action')=='add')
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
						$ipportID = $db->insert_id();

						$hooks->call( 'core.createIPPort', 
						              array( 'id' => $ipportID ) );

						redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					eval("echo \"".getTemplate("ipsandports/ipsandports_add")."\";");
				}
		}

		elseif($config->get('env.action')=='edit' && $config->get('env.id')!=0)
		{
			$result=$db->query_first("SELECT `id`, `ip`, `port` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `id`='".$config->get('env.id')."'");
			if($result['ip']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$ip = addslashes($_POST['ip']);
					$port = intval($_POST['port']);

					$result2=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='$ip' AND `port`='$port'");
					$result3=$db->query_first("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='".$result['ip']."' AND `id`!='".$config->get('env.id')."'");

					if($ip=='')
					{
						standard_error(array('stringisempty','myipaddress'));
					}
					elseif($port=='')
					{
						standard_error(array('stringisempty','myport'));
					}
					elseif($result['ip']!=$ip && $result['ip']==$config->get('system.ipaddress') && $result3['id']=='')
					{
						standard_error('cantchangesystemip');
					}
					elseif($result2['id']!='' && $result2['id']!=$config->get('env.id'))
					{
						standard_error('myipnotdouble');
					}
					else
					{
						$db->query("UPDATE `".TABLE_PANEL_IPSANDPORTS."` SET `ip`='$ip', `port`='$port' WHERE `id`='".$config->get('env.id')."'");

						$hooks->call( 'core.updateIPPort', 
						              array( 'id' => $config->get('env.id') ) );

						redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
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