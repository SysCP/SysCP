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
	 */
	function ask_yesno($text,$yesfile,$params='')
	{
		global $userinfo,$tpl,$db,$s,$header,$footer,$lng;
		$hiddenparams='';
		if(isset($params))
		{
			$params=explode(';',$params);
			while(list(,$param)=each($params))
			{
				$param=explode('=',$param);
				$hiddenparams.="<input type=\"hidden\" name=\"$param[0]\" value=\"$param[1]\">\n";
			}
		}
		if(isset($lng['question'][$text]))
		{
			$text = $lng['question'][$text];
		}
		eval("echo \"".getTemplate('misc/question_yesno','1')."\";");
	}

	/**
	 * Return HTML Code for an option within a <select>
	 *
	 * @param string The caption
	 * @param string The Value which will be returned
	 * @param string Values which will be selected by default.
	 * @return string HTML Code
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
	 */
	function inserttask($type,$param1='',$param2='',$param3='')
	{
		global $db;

		if($type=='1')
		{
			$result=$db->query_first("SELECT `type` FROM `".TABLE_PANEL_TASKS."` WHERE `type`='1'");
			if($result['type']=='')
			{
				$db->query("INSERT INTO `".TABLE_PANEL_TASKS."` (`type`) VALUES ('1');");
			}
		}
		elseif($type=='2' && $param1!='' && $param2!='' && $param3!='')
		{
			$data=Array();
			$data['loginname']=$param1;
			$data['uid']=$param2;
			$data['gid']=$param3;
			$data=serialize($data);
			$db->query("INSERT INTO `".TABLE_PANEL_TASKS."` (`type`, `data`) VALUES ('2', '".addslashes($data)."');");
		}
		elseif($type=='3' && $param1!='')
		{
			$data=Array();
			$data['path']=$param1;
			$data=serialize($data);
			$result=$db->query_first("SELECT `type` FROM `".TABLE_PANEL_TASKS."` WHERE `type`='3' AND `data`='".addslashes($data)."'");
			if($result['type']=='')
			{
				$db->query("INSERT INTO `".TABLE_PANEL_TASKS."` (`type`, `data`) VALUES ('3', '".addslashes($data)."');");
			}
		}
		elseif($type=='4')
		{
			$result=$db->query_first("SELECT `type` FROM `".TABLE_PANEL_TASKS."` WHERE `type`='4'");
			if($result['type']=='')
			{
				$db->query("INSERT INTO `".TABLE_PANEL_TASKS."` (`type`) VALUES ('4');");
			}
		}
	}

	/**
	 * Function which make webalizer statistics and returns used traffic of a month and year
	 *
	 * @param string Name of logfile
	 * @param string Documentroot of Domain
	 * @param string Caption for webalizer output
	 * @param int Month
	 * @param int Year
	 * @return int Used traffic
	 */
	function webalizer_hist($logfile, $documentroot, $caption, $month = 0, $year = 0)
	{
		global $settings;

		$yesterday = time()-(60*60*24);
		if($month == 0)
		{
			$month = date('n',$yesterday);
		}
		if($year == 0)
		{
			$year = date('Y',$yesterday);
		}

		exec('webalizer -n '.$caption.' -o '.$documentroot.'/webalizer/ '.$settings['system']['logfiles_directory'].$logfile.'-access.log');

		$webalizer_hist_size=@filesize($documentroot.'/webalizer/webalizer.hist');
		$webalizer_hist_num=@fopen($documentroot.'/webalizer/webalizer.hist','r');
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
	 */
	function makeCorrectDir($dir)
	{
		$dir = str_replace('..', '', $dir);
		if(substr($dir, -1, 1) != '/')
		{
			$dir .= '/';
		}
		if(substr($dir, 0, 1) != '/')
		{
			$dir = '/'.$dir;
		}
		return $dir;
	}

?>
