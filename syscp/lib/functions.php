<?php
/**
 * filename: $Source$
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
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Functions
 * @version $Id$
 */

	/**
	 * Get template from filesystem
	 *
	 * @param string Templatename
	 * @param string noarea If area should be used to get template
	 * @return string The Template
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function getTemplate($template, $noarea = 0)
	{
		global $templatecache;
		if($noarea != 1)
		{
			$template = AREA.'/'.$template;
		}

		if(!isset($templatecache[$template]))
		{
			$filename = './templates/'.$template.'.tpl';
			if(file_exists($filename))
			{
				$templatefile=str_replace("\"","\\\"",implode(file($filename),''));
			}
			else
			{
				$templatefile='<!-- TEMPLATE NOT FOUND: '.$filename.' -->';
			}
			$templatefile = preg_replace("'<if ([^>]*?)>(.*?)</if>'si", "\".( (\\1) ? \"\\2\" : \"\").\"", $templatefile);
			$templatecache[$template] = $templatefile;
		}
		return $templatecache[$template];
	}

	/**
	 * Prints errormessage on screen
	 *
	 * @param string Errormessage
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function standard_error($error='')
	{
		global $db, $tpl, $userinfo, $s, $header, $footer, $lng;
		if(isset($lng['error'][$error]))
		{
			$error = $lng['error'][$error];
		}
		else
		{
			$error = 'Unknown Error';
		}
		eval("echo \"".getTemplate('misc/error','1')."\";");
		exit;
	}

	/**
	 * Returns HTML Code for two radio buttons with two choices: yes and no 
	 *
	 * @param string Name of HTML-Variable
	 * @param string Value which will be returned if user chooses yes
	 * @param string Value which will be returned if user chooses no
	 * @param string Value which is chosen by default
	 * @return string HTML Code
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function makeyesno($name,$yesvalue,$novalue="",$yesselected="")
	{
		global $lng;
		if($yesselected)
		{
			$yeschecked=' checked';
			$nochecked='';
		}
 		else
		{
			$yeschecked='';
			$nochecked=' checked';
		}
 		$code="<b>".$lng['panel']['yes']."</b> <input type=\"radio\" name=\"$name\" value=\"$yesvalue\"$yeschecked> &nbsp; \n<b>".$lng['panel']['no']."</b> <input type=\"radio\" name=\"$name\" value=\"$novalue\"$nochecked> ";
 		return $code;
	}

	/**
	 * Prints Question on screen
	 *
	 * @param string The question
	 * @param string File which will be called with POST if user clicks yes
	 * @param string Values which will be given to $yesfile. Format: 'variable1=value1;variable2=value2;variable3=value3'
	 * @param string Name of the target eg Domain or eMail address etc.
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function ask_yesno ( $text , $yesfile , $params = '' , $targetname = '')
	{
		global $userinfo , $tpl , $db , $s , $header , $footer , $lng ;
		$hiddenparams = '' ;
		if ( isset ( $params ) )
		{
			$params = explode ( ';' , $params ) ;
			while ( list ( ,$param ) =each ( $params ) )
			{
				$param = explode ( '=' , $param ) ;
				$hiddenparams .= "<input type=\"hidden\" name=\"$param[0]\" value=\"$param[1]\">\n" ;
			}
		}
		if ( isset ( $lng['question'][$text] ) )
		{
			$text = $lng['question'][$text] ;
		}
		$text = str_replace ( '%s' , $targetname , $text ) ;
		eval ( "echo \"".getTemplate('misc/question_yesno','1')."\";" ) ;
	}

	/**
	 * Return HTML Code for an option within a <select>
	 *
	 * @param string The caption
	 * @param string The Value which will be returned
	 * @param string Values which will be selected by default.
	 * @return string HTML Code
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function makeoption($title,$value,$selvalue="")
	{
		if($value==$selvalue)
		{
			$selected='selected="selected"';
		}
 		else
		{
			$selected='';
		}
 		$option="<option value=\"$value\" $selected >$title</option>";
 		return $option;
	}

	/**
	 * Returns Array, whose elements were stripslash'ed
	 *
	 * @param array The array to stripslashes
	 * @return array The stripslash'ed array
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function stripslashes_array($source)
	{
		if(is_array($source))
		{
			while(list($var,$val)=each($source))
			{
				$returnval[$var]=stripslashes($val);
			}
		}
		else
		{
			$returnval=stripslashes($source);
		}
		return $returnval;
	}

	/**
	 * Returns Array, whose elements have been checked whether thay are empty or not
	 *
	 * @param array The array to trim
	 * @return array The trim'med array
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function array_trim($source)
	{
		if(is_array($source))
		{
			while(list($var,$val)=each($source))
			{
				if($val!=' ' && $val!='') $returnval[$var]=$val;
			}
		}
		else
		{
			$returnval=$source;
		}
		return $returnval;
	}

	/**
	 * Replaces Strings in an array, with the advantage that you can select which fields should be str_replace'd
	 *
	 * @param mixed String or array of strings to search for
	 * @param mixed String or array to replace with
	 * @param array The subject array
	 * @param string The fields which should be checked for, seperated by spaces
	 * @return array The str_replace'd array
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	 function str_replace_array($search, $replace, $subject, $fields = '')
	 {
		if(is_array($subject))
		{
			$fields = explode(' ', $fields);
			if(is_array($fields) && !empty($fields))
			{
				while(list(,$field)=each($fields))
				{
					if($field != '')
					{
						$subject[$field] = str_replace($search, $replace, $subject[$field]);
					}
				}
			}
			else
			{
				$subject = str_replace($search, $replace, $subject);
			}
		}
		else
		{
			$subject = str_replace($search, $replace, $subject);
		}
		return $subject;
	 }

	/**
	 * Returns if an emailaddress is in correct format or not
	 *
	 * @param string The email address to check
	 * @return bool Correct or not
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function verify_email($email)
	{
		$email=strtolower($email);

		if(!preg_match("/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si",$email))
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Inserts a task into the PANEL_TASKS-Table
	 *
	 * @param int Type of task
	 * @param string Parameter 1
	 * @param string Parameter 2
	 * @param string Parameter 3
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function inserttask($type,$param1='',$param2='',$param3='')
	{
		global $db;

		if($type=='1')
		{
			$result=$db->query_first(
				'SELECT `type` ' .
				'FROM `' . TABLE_PANEL_TASKS . '` ' .
				'WHERE `type`="1"'
			);

			if($result['type']=='')
			{
				$db->query(
					'INSERT INTO `' . TABLE_PANEL_TASKS . '` ' .
					'(`type`) ' .
					'VALUES ' .
					'("1")'
				);
			}
		}
		elseif($type=='2' && $param1!='' && $param2!='' && $param3!='')
		{
			$data=Array();
			$data['loginname']=$param1;
			$data['uid']=$param2;
			$data['gid']=$param3;
			$data=serialize($data);
			$db->query(
				'INSERT INTO `' . TABLE_PANEL_TASKS . '` ' .
				'(`type`, `data`) ' .
				'VALUES ' .
				'("2", "' . addslashes($data) . '")'
			);
		}
		elseif($type=='3' && $param1!='')
		{
			$data=Array();
			$data['path']=$param1;
			$data=serialize($data);

			$result=$db->query_first(
				'SELECT `type` ' .
				'FROM `' . TABLE_PANEL_TASKS . '` ' .
				'WHERE `type`="3" ' .
				'AND `data`="' . addslashes($data) .'"'
			);

			if($result['type']=='')
			{
				$db->query(
					'INSERT INTO `' . TABLE_PANEL_TASKS . '` ' .
					'(`type`, `data`) ' .
					'VALUES ' .
					'("3", "' . addslashes($data) . '")'
				);
			}
		}
		elseif($type=='4')
		{
			$result=$db->query_first(
				'SELECT `type` ' .
				'FROM `' . TABLE_PANEL_TASKS . '` ' .
				'WHERE `type`="4"'
			);

			if($result['type']=='')
			{
				$db->query(
					'INSERT INTO `' . TABLE_PANEL_TASKS . '` ' .
					'(`type`) ' .
					'VALUES ' .
					'("4")'
				);
			}
		}
	}

	/**
	 * Function which make webalizer statistics and returns used traffic of a month and year
	 *
	 * @param string Name of logfile
	 * @param string Place where stats should be build
	 * @param string Caption for webalizer output
	 * @param int Month
	 * @param int Year
	 * @return int Used traffic
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function webalizer_hist($logfile, $outputdir, $caption, $month = 0, $year = 0)
	{
		global $settings;

		$httptraffic = 0;

		$yesterday = time()-(60*60*24);
		if($month == 0)
		{
			$month = date('n',$yesterday);
		}
		if($year == 0)
		{
			$year = date('Y',$yesterday);
		}

		$outputdir = makeCorrectDir ($outputdir);
		if(!file_exists($outputdir))
		{
			safe_exec('mkdir -p '.$outputdir);
		}
		safe_exec('webalizer -n '.$caption.' -o '.$outputdir.' '.$settings['system']['logfiles_directory'].$logfile.'-access.log');

		$webalizer_hist_size=@filesize($outputdir.'webalizer.hist');
		$webalizer_hist_num=@fopen($outputdir.'webalizer.hist','r');
		$webalizer_hist=@fread($webalizer_hist_num,$webalizer_hist_size);
		@fclose($webalizer_hist_num);
		$webalizer_hist_rows=explode("\n",$webalizer_hist);
		while(list(,$webalizer_hist_row)=each($webalizer_hist_rows))
		{
			if($webalizer_hist_row != '')
			{
				/**
				 * Month: $webalizer_hist_row['0']
				 * Year:  $webalizer_hist_row['1']
				 * KB:    $webalizer_hist_row['5']
				 */
				$webalizer_hist_row=explode(' ',$webalizer_hist_row);
				if($webalizer_hist_row['0'] == $month && $webalizer_hist_row['1'] == $year)
				{
					$httptraffic = $webalizer_hist_row['5'];
				}
			}
		}
		return $httptraffic;
	}

	/**
	 * Function which returns a correct dirname, means to add slashes at the beginning and at the end if none there and to remove all ..'s
	 *
	 * @param string The dirname
	 * @return string The corrected dirname
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function makeCorrectDir($dir)
	{
		if(substr($dir, -1, 1) != '/')
		{
			$dir .= '/';
		}
		if(substr($dir, 0, 1) != '/')
		{
			$dir = '/'.$dir;
		}

		$search = Array ('/(\/)+/', '/(\.)+/');
		$replace = Array ('/', '.');
		$dir = preg_replace($search, $replace, $dir);

		return $dir;
	}

	/**
	 * Function which updates all counters of used ressources in panel_admins and panel_customers
	 * 
	 * @author Florian Lippert <flo@redenswert.de>
	 */
	function updateCounters ()
	{
		global $db;

		// Customers
		$customers = $db->query(
			'SELECT `customerid` ' .
			'FROM `' . TABLE_PANEL_CUSTOMERS . '` ' .
			'ORDER BY `customerid`'
		);

		while($customer = $db->fetch_array($customers))
		{
			$customer_mysqls = $db->query_first(
				'SELECT COUNT(*) AS `number_mysqls`' .
				'FROM `'.TABLE_PANEL_DATABASES.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_emails = $db->query_first(
				'SELECT COUNT(*) AS `number_emails`' .
				'FROM `'.TABLE_MAIL_USERS.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_email_forwarders = $db->query_first(
				'SELECT COUNT(*) AS `number_email_forwarders`' .
				'FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'" ' .
				'AND `popaccountid` = "0"'
			);

			$customer_ftps = $db->query_first(
				'SELECT COUNT(*) AS `number_ftps` ' .
				'FROM `'.TABLE_FTP_USERS.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_subdomains = $db->query_first(
				'SELECT COUNT(*) AS `number_subdomains` ' .
				'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'" ' .
				'AND `isemaildomain` = "0"'
			);

			$db->query(
				'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'SET `mysqls_used` = "'.$customer_mysqls['number_mysqls'].'", ' .
				'    `emails_used` = "'.$customer_emails['number_emails'].'", ' .
				'    `email_forwarders_used` = "'.$customer_email_forwarders['number_email_forwarders'].'", ' .
				'    `ftps_used` = "'.($customer_ftps['number_ftps']-1).'", ' .
				'    `subdomains_used` = "'.$customer_subdomains['number_subdomains'].'" ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);
		}

		// Admins
		$admins = $db->query(
			'SELECT `adminid` ' .
			'FROM `'.TABLE_PANEL_ADMINS.'` ' .
			'ORDER BY `adminid`'
		);

		while($admin = $db->fetch_array($admins))
		{
			$admin_customers = $db->query_first(
				'SELECT COUNT(*) AS `number_customers`, ' .
				'SUM(`diskspace`) AS `diskspace`, ' .
				'SUM(`mysqls`) AS `mysqls`, ' .
				'SUM(`emails`) AS `emails`, ' .
				'SUM(`email_forwarders`) AS `email_forwarders`, ' .
				'SUM(`ftps`) AS `ftps`, ' .
				'SUM(`subdomains`) AS `subdomains`, ' .
				'SUM(`traffic`) AS `traffic` ' .
				'FROM `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'WHERE `adminid` = "'.$admin['adminid'].'"'
			);

			$admin_domains = $db->query_first(
				'SELECT COUNT(*) AS `number_domains` ' .
				'FROM `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'WHERE `adminid` = "'.$admin['adminid'].'"'
			);

			$db->query(
				'UPDATE `'.TABLE_PANEL_ADMINS.'` ' .
				'SET `customers_used` = "'.$admin_customers['number_customers'].'", ' .
				'    `diskspace_used` = "'.$admin_customers['diskspace'].'", ' .
				'    `mysqls_used` = "'.$admin_customers['mysqls'].'", ' .
				'    `emails_used` = "'.$admin_customers['emails'].'", ' .
				'    `email_forwarders_used` = "'.$admin_customers['email_forwarders'].'", ' .
				'    `ftps_used` = "'.$admin_customers['ftps'].'", ' .
				'    `subdomains_used` = "'.$admin_customers['subdomains'].'", ' .
				'    `traffic_used` = "'.$admin_customers['traffic'].'", ' .
				'    `domains_used` = "'.$admin_domains['number_domains'].'" ' .
				'WHERE `adminid` = "'.$admin['adminid'].'"'
			);
		}
	}

	/******************************************************
	 * Wrapper around the exec command.
	 * 
	 * @author Martin Burchert <eremit@adm1n.de>
	 * @version 1.2
	 * @param string exec_string String to be executed
	 * @return string The result of the exec()
	 *
	 * History:
	 ******************************************************
	 * 1.0 : Initial Version 
	 * 1.1 : Added |,&,>,<,`,*,$,~,? as security breaks.
	 * 1.2 : Removed * as security break
	 ******************************************************/
	function safe_exec($exec_string)
	{
		global $settings;
		//
		// define allowed system commands 
		//
		$allowed_commands = array(
			'touch','chown', 'mkdir', 'webalizer', 'cp', 'du', 'touch',
			$settings['system']['apachereload_command'],
			$settings['system']['bindreload_command']);
		//
		// check for ; in execute command
		//
		if ((stristr($exec_string,';')) or
			(stristr($exec_string,'|')) or
			(stristr($exec_string,'&')) or
			(stristr($exec_string,'>')) or
			(stristr($exec_string,'<')) or
			(stristr($exec_string,'`')) or
			(stristr($exec_string,'$')) or
			(stristr($exec_string,'~')) or
			(stristr($exec_string,'?')) )
		{ 
			die ("SECURITY CHECK FAILED!\n' The execute string $exec_string is a possible security risk!\nPlease check your whole server for security problems by hand!\n");
		}
		//
		// check if command is allowed here 
		//	
		$allowed = false;
		foreach ($allowed_commands as $key => $value)
		{
			if ($allowed == false)
			{
				$allowed = stristr($exec_string, $value);
			}
		}
		if ($allowed == false)
		{
			die("SECURITY CHECK FAILED!\nYour command '$exec_string' is not allowed!\nPlease check your whole server for security problems by hand!\n");
		}
		//
		// execute the command and return output
		//
		exec($exec_string, $return);
		return $return;
	}
	
	/******************************************************
	 * Navigation generator
	 * 
	 * @author Martin Burchert <eremit@adm1n.de>
	 * @version 1.0
	 * @param array userinfo the userinfo of the user
	 * @return string the content of the navigation bar
	 *
	 * History:
	 ******************************************************
	 * 1.0 : Initial Version 
	 ******************************************************/
	function getNavigation($s)
	{
		global $db, $lng;
		
		$return = '';
		//
		// query database
		//
		$query  = 
			'SELECT * ' .
			'FROM `'.TABLE_PANEL_NAVIGATION.'` ' .
			'WHERE `area`="'.AREA.'"';
		$result = $db->query($query);
		//
		// presort in multidimensional array
		//
		while ($row = $db->fetch_array($result))
		{
			if ($row['parent_id'] == 0)
			{
				$nav[$row['id']][0] = $row;
			}
			else
			{
				$nav[$row['parent_id']][] = $row;
			}
		}
		//
		// generate output
		//
		if ( (isset($nav)) && (sizeof($nav) > 0))
		{
			foreach ($nav as $parent_id => $row) 
			{
				foreach ($row as $id => $navElem )
				{
					$url = '';
					// get corect lang string
					$lngArr = split(';',$navElem['lang']);
					$text = $lng;
					foreach ($lngArr as $lKey => $lValue)
					{
						$text = $text[$lValue];
					}
					if ($navElem['parent_id'] != '0')
					{
						$indent = '&nbsp;&nbsp;&nbsp;&raquo; ';
					}
					else
					{
						$indent = '&raquo; ';
					}
					$sid = '';
					// append sid only to local
					if ( !preg_match('/^https?\:\/\//', $navElem['url'] ) && (isset($s)) )
					{
						// generate sid with ? oder &
						if (preg_match('/\?/',$navElem['url']))
						{
							$sid = '&s='.$s;
						}
						else
						{
							$sid = '?s='.$s;
						}
					}
					// assign url
					$url = '<a href="'.$navElem['url'].$sid.'">';
					// read template
					eval("\$return .= \"".getTemplate("navigation",1)."\";");
				}
			}
		}
		return $return;
	}	
?>