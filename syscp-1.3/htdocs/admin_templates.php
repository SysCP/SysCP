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
 * @copyright  (c) 2005-2006 Michael Duergner
 * @package    Org.Syscp.Core
 * @subpackage Panel.Admin
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_templates.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 * 
 * @todo code cleanup is needed, exspecially in queries, loops, conditions
 * @todo code restructuring is needed, we need to seperate view, controller and model logic,
 *       as a preparation towards MVC patterns
 */


	define('AREA', 'admin');

	/**
	 * Include our init.php, which manages Sessions, Language etc.
	 */
	require_once '../lib/init.php';
	
	if($config->get('env.page')=='email' )
	{
		if(isset($_POST['subjectid']))
		{
			$subjectid=intval($_POST['subjectid']);
			$mailbodyid=intval($_POST['mailbodyid']);
		}
		elseif(isset($_GET['subjectid']))
		{
			$subjectid=intval($_GET['subjectid']);
			$mailbodyid=intval($_GET['mailbodyid']);
		}

		if($config->get('env.action')=='')
		{
			$available_templates=array(
				'createcustomer',
				'pop_success'
			);
			
			$templates_array=array();
			$result=$db->query("SELECT `id`, `language`, `varname` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='{$userinfo['adminid']}' AND `templategroup`='mails' ORDER BY `language`, `varname`");
			while($row=$db->fetch_array($result))
			{
				$parts=array();
				preg_match('/^([a-z]([a-z_]+[a-z])*)_(mailbody|subject)$/',$row['varname'],$parts);
				$templates_array[$row['language']][$parts[1]][$parts[3]]=$row['id'];
			}
			$templates='';
			foreach($templates_array as $tLanguage => $template_defs)
			{
				foreach($template_defs as $tAction => $email)
				{
					$subjectid=$email['subject'];
					$mailbodyid=$email['mailbody'];
					$template=$lng['admin']['templates'][$tAction];
					eval("\$templates.=\"".getTemplate("templates/templates_template")."\";");
				}
			}
			
			$add = false;
			$languages = $language->getList();
			while(list($language_file, $language_name) = each($languages))
			{
				$templates_done=array();
				$result=$db->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$language_name.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');
				while(($row=$db->fetch_array($result))!=false)
				{
					$templates_done[]=str_replace('_subject','',$row['varname']);
				}
				if(count(array_diff($available_templates,$templates_done))>0)
				{
					$add = true;
				}
			}
			
			eval("echo \"".getTemplate("templates/templates")."\";");
		}

		elseif($config->get('env.action')=='delete' && $subjectid!=0 && $mailbodyid!=0)
		{
			$result=$db->query_first("SELECT `language`, `varname` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$userinfo['adminid']."' AND `id`='$subjectid'");
			if($result['varname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$db->query("DELETE FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$userinfo['adminid']."' AND (`id`='$subjectid' OR `id`='$mailbodyid')");
					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
				else
				{
					ask_yesno('admin_template_reallydelete', $config->get('env.filename'), "subjectid=$subjectid;mailbodyid=$mailbodyid;page=".$config->get('env.page').";action=".$config->get('env.action'), $result['language'].' - '.$lng['admin']['templates'][str_replace('_subject','',$result['varname'])]);
				}
			}
		}

		elseif($config->get('env.action')=='add')
		{
			$available_templates=array(
				'createcustomer',
				'pop_success'
			);
			if(isset($_POST['prepare']) && $_POST['prepare']=='prepare')
			{
				$language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['language'] ) ) ) ;
				
				$templates=array();
				$result=$db->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$language.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');
				while(($row=$db->fetch_array($result))!=false)
				{
					$templates[]=str_replace('_subject','',$row['varname']);
				}
				$templates=array_diff($available_templates,$templates);
				
				$template_options='';
				foreach($templates as $template) {
					$template_options.=makeoption($lng['admin']['templates'][$template],$template);
				}
				eval("echo \"".getTemplate("templates/templates_add_2")."\";");
				
			}
			else if(isset($_POST['send']) && $_POST['send']=='send')
			{
				$language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['language'] ) ) ) ;
				$template = addslashes($_POST['template']);
				$subject = htmlentities(addslashes($_POST['subject']));
				$mailbody = htmlentities(addslashes($_POST['mailbody']));

				$templates=array();
				$result=$db->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$language.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');
				while(($row=$db->fetch_array($result))!=false)
				{
					$templates[]=str_replace('_subject','',$row['varname']);
				}
				$templates=array_diff($available_templates,$templates);

				if($language == '')
				{
					standard_error('nolanguageselect');
				}
				elseif($subject == '')
				{
					standard_error('nosubjectcreate');
				}
				elseif($mailbody == '')
				{
					standard_error('nomailbodycreate');
				}
				elseif(array_search($template,$templates) === false)
				{
					standard_error('templatenotfound');
				}

				else
				{

					$result=$db->query("INSERT INTO `".TABLE_PANEL_TEMPLATES."` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('{$userinfo['adminid']}', '$language', 'mails', '".$template."_subject','$subject')");
					$result=$db->query("INSERT INTO `".TABLE_PANEL_TEMPLATES."` (`adminid`, `language`, `templategroup`, `varname`, `value`)
					                   VALUES ('{$userinfo['adminid']}', '$language', 'mails', '".$template."_mailbody','$mailbody')");
					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
				}
			}
			else
			{
				$add = false;
				$language_options = '';
				$languages = $language->getList();
				while(list($language_file, $language_name) = each($languages))
				{
					$templates=array();
					$result=$db->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$userinfo['adminid'].'\' AND `language`=\''.$language_name.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');
					while(($row=$db->fetch_array($result))!=false)
					{
						$templates[]=str_replace('_subject','',$row['varname']);
					}
					if(count(array_diff($available_templates,$templates))>0)
					{
						$add = true;
						$language_options .= makeoption($language_name, $language_file, $userinfo['language']);
					}
				}
				if($add)
				{
					eval("echo \"".getTemplate("templates/templates_add_1")."\";");
				}
				else
				{
					standard_error('alltemplatesdefined');
					exit;
				}
			}
		}

		elseif($config->get('env.action')=='edit')
		{
			$result=$db->query_first("SELECT `language`, `varname`, `value` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$userinfo['adminid']."' AND `id`='$subjectid'");
			if($result['varname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$subject = htmlentities(addslashes($_POST['subject']));
					$mailbody = htmlentities(addslashes($_POST['mailbody']));

					if($subject == '')
					{
						standard_error('nosubjectcreate');
					}
					elseif($mailbody == '')
					{
						standard_error('nomailbodycreate');
					}

					else
					{
						$db->query("UPDATE `".TABLE_PANEL_TEMPLATES."` SET `value`='$subject' WHERE `adminid`='".$userinfo['adminid']."' AND `id`='$subjectid'");
						$db->query("UPDATE `".TABLE_PANEL_TEMPLATES."` SET `value`='$mailbody' WHERE `adminid`='".$userinfo['adminid']."' AND `id`='$mailbodyid'");

    					redirectTo ( $config->get('env.filename') , Array ( 'page' => $config->get('env.page') , 's' => $config->get('env.s') ) ) ;
					}
				}
				else
				{
					$template=$lng['admin']['templates'][str_replace('_subject','',$result['varname'])];
					$subject=$result['value'];
					$result=$db->query_first("SELECT `language`, `varname`, `value` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `id`='$mailbodyid'");
					$mailbody=$result['value'];
					eval("echo \"".getTemplate("templates/templates_edit")."\";");
				}
			}
		}
	}

?>
