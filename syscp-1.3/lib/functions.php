<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @package    Syscp.Misc
 * @subpackage Toolkit
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id$
 */

	/**
	 * Returns Array, whose elements have been checked whether thay are empty or not
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  array  The array to trim
	 *
	 * @return array  The trim'med array
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
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  mixed   String or array of strings to search for
	 * @param  mixed   String or array to replace with
	 * @param  array   The subject array
	 * @param  string  The fields which should be checked for, seperated by spaces
	 *
	 * @return array   The str_replace'd array
	 *
	 * @todo Check if str_replace fits the purpose. We only use this function in the
	 *       code to replace -1 with UL/Unlimited. This code is used in
	 *       admin_admins.php, admin_customers.php admin_index.php customer_index.php
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
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  string  The email address to check
	 *
	 * @return bool    Correct or not
	 *
	 * @todo THis is a simple regexp validation. Check what the returncode_space does. THis
	 *       is used in: admin_admins.php(2x) admin_customers.php(2x) customer_email(2x)
	 */
	function verify_email($email)
	{
		$email=strtolower($email);

		$returncode_preg = preg_match("/^([_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,}))/si",$email) ;
		$returncode_space = false ;
		if ( !strstr ( $email , ' ' ) )
		{
			$returncode_space = true ;
		}

		if ( $returncode_preg == true && $returncode_space == true)
		{
			return true ;
		}
		else
		{
			return false ;
		}
	}

	/**
	 * Function which make webalizer statistics and returns used traffic of a month and year
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  string  Name of logfile
	 * @param  string  Place where stats should be build
	 * @param  string  Caption for webalizer output
	 * @param  int     Month
	 * @param  int     Year
	 *
	 * @return int     Used traffic
	 *
	 * @todo Move this function into the cronscript area. We don'T need it globally.
	 */
	function webalizer_hist($logfile, $outputdir, $caption, $month = 0, $year = 0)
	{
		global $config;

		$httptraffic = 0;

		if ( file_exists ( $config->get('system.logfiles_directory').$logfile.'-access.log' ) )
		{
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
				safe_exec('mkdir -p "'.$outputdir.'"');
			}
			safe_exec('webalizer -n "'.$caption.'" -o "'.$outputdir.'" "'.$config->get('system.logfiles_directory').$logfile.'-access.log"');

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
		}
		return $httptraffic;
	}

	/**
	 * Function which returns a secure path, means to remove all multiple dots and slashes
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  string  The path
	 *
	 * @return string  The corrected path
	 *
	 * @todo No longer used?
	 */
	function makeSecurePath($path)
	{
		$search = array ('/(\/)+/', '/(\.)+/');
		$replace = array ('/', '.');
		$path = preg_replace($search, $replace, $path);

		return $path;
	}

	/**
	 * Function which returns a correct dirname, means to add slashes at the beginning and at the end if there weren't none
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  string  The dirname
	 *
	 * @return string  The corrected dirname
	 *
	 * @todo This should be moved to Syscp.class.php, it's a core security functionality.
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

		$dir = makeSecurePath ( $dir ) ;

		return $dir;
	}

	/**
	 * Function which returns a correct filename, means to add a slash at the beginning if there weren't none
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 * @author Michael Russ <mr@edvruss.com>
	 * @author Martin Burchert <eremit@adm1n.de>
	 *
	 * @param  string  the filename
	 *
	 * @return string  the corrected filename
	 *
	 * @todo Not used any longer?
	 */
	function makeCorrectFile($filename)
	{
		if ( substr($filename, 0, 1) != '/' )
		{
			$filename = '/'.$filename;
		}

		$filename = makeSecurePath ( $filename ) ;

		return $filename;
	}

	/**
	 * Function which returns a correct destination for Postfix Virtual Table
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  string  The destinations
	 *
	 * @return string  the corrected destinations
	 *
	 * @todo Used only in customer_email(4x) maybe move there?
	 */
	function makeCorrectDestination($destination)
	{
		$search   = '/(\ )+/' ;
		$replace  = ' ';
		$destination = preg_replace($search, $replace, $destination);

		if ( substr($destination, 0, 1) == ' ' )
		{
			$destination = substr ( $destination , 1 ) ;
		}
		if ( substr($destination, -1, 1) == ' ' )
		{
			$destination = substr ( $destination , 0 , strlen ( $destination ) - 1 ) ;
		}

		return $destination;
	}

	/**
	 * Function which updates all counters of used ressources in panel_admins and panel_customers
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @return void
	 *
	 * @todo Consider what about to do with this function. After introducing propel and having a
	 *       User model and handler, we should move this function there. FInd an interim place.
	 */
	function updateCounters ()
	{
		global $db;
		$admin_resources = Array () ;

		// Customers
		$customers = $db->query(
			'SELECT `customerid`, `adminid`, ' .
			' `diskspace`, `traffic_used`, `mysqls`, `ftps`, `emails`, `email_accounts`, `email_forwarders`, `subdomains` ' .
			'FROM `' . TABLE_PANEL_CUSTOMERS . '` ' .
			'ORDER BY `customerid`'
		);

		while($customer = $db->fetch_array($customers))
		{
			if ( ! isset ( $admin_resources[$customer['adminid']] ) )
			{
				$admin_resources[$customer['adminid']] = Array () ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['diskspace_used'] ) )
			{
				$admin_resources[$customer['adminid']]['diskspace_used'] = 0 ;
			}
			if ( ($customer['diskspace']/1024) != '-1' )
			{
				$admin_resources[$customer['adminid']]['diskspace_used'] += intval_ressource ( $customer['diskspace'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['traffic_used'] ) )
			{
				$admin_resources[$customer['adminid']]['traffic_used'] = 0 ;
			}
			$admin_resources[$customer['adminid']]['traffic_used'] += $customer['traffic_used'] ;

			if ( ! isset ( $admin_resources[$customer['adminid']]['mysqls_used'] ) )
			{
				$admin_resources[$customer['adminid']]['mysqls_used'] = 0 ;
			}
			if ( $customer['mysqls'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['mysqls_used'] += intval_ressource ( $customer['mysqls'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['ftps_used'] ) )
			{
				$admin_resources[$customer['adminid']]['ftps_used'] = 0 ;
			}
			if ( $customer['ftps'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['ftps_used'] += intval_ressource ( $customer['ftps'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['emails_used'] ) )
			{
				$admin_resources[$customer['adminid']]['emails_used'] = 0 ;
			}
			if ( $customer['emails'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['emails_used'] += intval_ressource ( $customer['emails'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['email_accounts_used'] ) )
			{
				$admin_resources[$customer['adminid']]['email_accounts_used'] = 0 ;
			}
			if ( $customer['email_accounts'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['email_accounts_used'] += intval_ressource ( $customer['email_accounts'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['email_forwarders_used'] ) )
			{
				$admin_resources[$customer['adminid']]['email_forwarders_used'] = 0 ;
			}
			if ( $customer['email_forwarders'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['email_forwarders_used'] += intval_ressource ( $customer['email_forwarders'] ) ;
			}

			if ( ! isset ( $admin_resources[$customer['adminid']]['subdomains_used'] ) )
			{
				$admin_resources[$customer['adminid']]['subdomains_used'] = 0 ;
			}
			if ( $customer['subdomains'] != '-1' )
			{
				$admin_resources[$customer['adminid']]['subdomains_used'] += intval_ressource ( $customer['subdomains'] ) ;
			}

			$customer_mysqls = $db->query_first(
				'SELECT COUNT(*) AS `number_mysqls` ' .
				'FROM `'.TABLE_PANEL_DATABASES.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_emails = $db->query_first(
				'SELECT COUNT(*) AS `number_emails` ' .
				'FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_emails_result = $db->query(
				'SELECT `email`, `email_full`, `destination`, `popaccountid` AS `number_email_forwarders` ' .
				'FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'" '
			);

			$customer_email_forwarders = 0;
			$customer_email_accounts = 0;
			while ( $customer_emails_row = $db->fetch_array ( $customer_emails_result ) )
			{
				if ( $customer_emails_row['destination'] != '' )
				{
					$customer_emails_row['destination'] = explode ( ' ' , makeCorrectDestination ( $customer_emails_row['destination'] ) ) ;
					$customer_email_forwarders += count ( $customer_emails_row['destination'] ) ;
					if ( in_array ( $customer_emails_row['email_full'] , $customer_emails_row['destination'] ) )
					{
						$customer_email_forwarders -= 1 ;
						$customer_email_accounts ++ ;
					}
				}
			}

			$customer_ftps = $db->query_first(
				'SELECT COUNT(*) AS `number_ftps` ' .
				'FROM `'.TABLE_FTP_USERS.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'"'
			);

			$customer_subdomains = $db->query_first(
				'SELECT COUNT(*) AS `number_subdomains` ' .
				'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
				'WHERE `customerid` = "'.$customer['customerid'].'" ' .
				'AND `parentdomainid` <> "0"'
			);

			$db->query(
				'UPDATE `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'SET `mysqls_used` = "'.$customer_mysqls['number_mysqls'].'", ' .
				'    `emails_used` = "'.$customer_emails['number_emails'].'", ' .
				'    `email_accounts_used` = "'.$customer_email_accounts.'", ' .
				'    `email_forwarders_used` = "'.$customer_email_forwarders.'", ' .
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
				'SELECT COUNT(*) AS `number_customers` ' .
				'FROM `'.TABLE_PANEL_CUSTOMERS.'` ' .
				'WHERE `adminid` = "'.$admin['adminid'].'"'
			);

			$admin_domains = $db->query_first(
				'SELECT COUNT(*) AS `number_domains` ' .
				'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
				'WHERE `adminid` = "'.$admin['adminid'].'" ' .
				'AND `isemaildomain` = "1"'
			);

			if ( ! isset ( $admin_resources[$admin['adminid']] ) )
			{
				$admin_resources[$admin['adminid']] = Array () ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['diskspace_used'] ) )
			{
				$admin_resources[$admin['adminid']]['diskspace_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['traffic_used'] ) )
			{
				$admin_resources[$admin['adminid']]['traffic_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['mysqls_used'] ) )
			{
				$admin_resources[$admin['adminid']]['mysqls_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['ftps_used'] ) )
			{
				$admin_resources[$admin['adminid']]['ftps_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['emails_used'] ) )
			{
				$admin_resources[$admin['adminid']]['emails_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['email_accounts_used'] ) )
			{
				$admin_resources[$admin['adminid']]['email_accounts_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['email_forwarders_used'] ) )
			{
				$admin_resources[$admin['adminid']]['email_forwarders_used'] = 0 ;
			}
			if ( ! isset ( $admin_resources[$admin['adminid']]['subdomains_used'] ) )
			{
				$admin_resources[$admin['adminid']]['subdomains_used'] = 0 ;
			}

			$db->query(
				'UPDATE `'.TABLE_PANEL_ADMINS.'` ' .
				'SET `customers_used` = "'.$admin_customers['number_customers'].'", ' .
				'    `diskspace_used` = "'.$admin_resources[$admin['adminid']]['diskspace_used'].'", ' .
				'    `mysqls_used` = "'.$admin_resources[$admin['adminid']]['mysqls_used'].'", ' .
				'    `emails_used` = "'.$admin_resources[$admin['adminid']]['emails_used'].'", ' .
				'    `email_accounts_used` = "'.$admin_resources[$admin['adminid']]['email_accounts_used'].'", ' .
				'    `email_forwarders_used` = "'.$admin_resources[$admin['adminid']]['email_forwarders_used'].'", ' .
				'    `ftps_used` = "'.$admin_resources[$admin['adminid']]['ftps_used'].'", ' .
				'    `subdomains_used` = "'.$admin_resources[$admin['adminid']]['subdomains_used'].'", ' .
				'    `traffic_used` = "'.$admin_resources[$admin['adminid']]['traffic_used'].'", ' .
				'    `domains_used` = "'.$admin_domains['number_domains'].'" ' .
				'WHERE `adminid` = "'.$admin['adminid'].'"'
			);
		}
	}

	/**
	 * Returns if an username is in correct format or not.
	 * A username is valid if it would be a username that is accepted by the
	 * useradd command.
	 *
	 * @author Michael D?rgner <michael@duergner.com>
	 *
	 * @param  string The username to check
	 *
	 * @return bool   Correct or not
	 *
	 * @todo Used in admin_admins, admin_customers admin_settings(2x). We should replace this
	 *       with a validation handler at a later time.
	 */
	function check_username($username) {
		return preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\-\_]*[a-zA-Z0-9\-\_\$]$/",$username);
	}

	/**
	 * Returns if an username_prefix is in correct format or not.
	 * A username_prefix is valid if the resulting username would be a username
	 * that is accepted by the useradd command.
	 *
	 * @author Michael Duergner <michael@duergner.com>
	 *
	 * @param  string The username to check
	 *
	 * @return bool   Correct or not
	 *
	 * @todo Only used in admin_Settings. move there?
	 */
	function check_username_prefix($username_prefix) {
		return preg_match("/^[a-zA-Z0-9][a-zA-Z0-9\-\_]*$/",$username_prefix);
	}

	/**
	 * Returns if a mysql_prefix is in correct format or not.
	 *
	 * @author Michael Duergner <michael@duergner.com>
	 *
	 * @param  string The mysql_prefix to check
	 *
	 * @return bool   Correct or not
	 *
	 * @todo only used in admin_settings, move there?
	 */
	function check_mysql_prefix($mysql_prefix) {
		return preg_match("/^[a-zA-Z0-9\-\_]+$/",$mysql_prefix);
	}

	/**
	 * Returns an integer of the given value which isn't negative.
	 * Returns -1 if the given value was -1.
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  mixed The value
	 *
	 * @return int   The positive value
	 *
	 * @todo Should be replaced by (int), has some addiotnal functionality regarding negative values
	 */
	function intval_ressource ( $the_value )
	{
		$the_value = intval ( $the_value ) ;
		if ( $the_value < 0 && $the_value != '-1')
		{
			$the_value *= -1 ;
		}
		return $the_value ;
	}

	/**
	 * Returns a double of the given value which isn't negative.
	 * Returns -1 if the given value was -1.
	 *
	 * @author Florian Lippert <flo@redenswert.de>
	 *
	 * @param  mixed  The value
	 *
	 * @return double The positive value
	 * @todo Should be replaced by typecast, has some addiotnal functionality regarding negative values
	 */
	function doubleval_ressource ( $the_value )
	{
		$the_value = doubleval ( $the_value ) ;
		if ( $the_value < 0 && $the_value != '-1')
		{
			$the_value *= -1 ;
		}
		return $the_value ;
	}

	/**
	 * Replaces all occurences of variables defined in the second argument
	 * in the first argument with their values.
	 *
	 * @author Michael Duergner
	 *
	 * @param  string The string that should be searched for variables
	 * @param  array  The array containing the variables with their values
	 *
	 * @return string The submitted string with the variables replaced.
	 *
	 * @todo COnsider using another solution, maybe preg_replace
	 */
	function replace_variables($text,$vars) {
		$pattern = "/\{([a-zA-Z0-9\-_]+)\}/";
		// --- martin @ 08.08.2005 -------------------------------------------------------
		// fixing usage of uninitialised variable
		$matches = array();
		// -------------------------------------------------------------------------------
		if(count($vars) > 0 && preg_match_all($pattern,$text,$matches)) {
			for($i = 0;$i < count($matches[1]);$i++) {
				$current = $matches[1][$i];
				if (isset ($vars[$current]) )
				{
					$var = $vars[$current];
					$text = str_replace("{" . $current . "}",$var,$text);
				}
			}
		}
		$text = str_replace ( '\n', "\n" , $text ) ;
		return $text;
	}

	/**
	 * Wrapper for the html_entity_decode function as this function is not
	 * present in Woody's PHP 4.1.2. In Sarge the html_entity_decode function
	 * shipped with PHP is used, for Woody own code is used.
	 *
	 * @author Michael Duergner
	 *
	 * @param  string The string in which the html entites should be decoded.
	 *
	 * @return string The decoded string
	 *
	 * @todo remove this function, we switched to php5.1.x
	 */
	function _html_entity_decode($string)
	{
		if(function_exists('html_entity_decode'))
		{
			return html_entity_decode($string);
		}
		else
		{
			$trans_table = get_html_translation_table(HTML_ENTITIES);
			$trans_table = array_flip($trans_table);
			return strtr($string,$trans_table);
		}
	}

	/**
	 * Check if the submitted string is a valid domainname, i.e.
	 * it consists only of the following characters ([a-z0-9][a-z0-9\-]+\.)+[a-z]{2,4}
	 *
	 * @author Michael Duergner
	 *
	 * @param  string  The domainname which should be checked.
	 *
	 * @return boolean True if the domain is valid, false otherwise
	 *
	 * @todo only used once in admin_domains, move there
	 */
	function check_domain($domainname)
	{
		return preg_match('/^([a-z0-9][a-z0-9\-]+\.)+[a-z]{2,4}$/i',$domainname);
	}

	/**
	 * Returns an array of found directories
	 *
	 * This function checks every found directory if they match either $uid or $gid, if they do
	 * the found directory is valid. It uses recursive function calls to find subdirectories. Due
	 * to the recursive behauviour this function may consume much memory.
	 *
	 * @author Martin Burchert  <martin.burchert@syscp.org>
	 * @author Manuel Bernhardt <manuel.bernhardt@syscp.org>
	 *
	 * @param  string   The path to start searching in
	 * @param  integer  The uid which must match the found directories
	 * @param  integer  The gid which must match the found direcotries
	 * @param  array    recursive transport array !for internal use only!
	 *
	 * @return array    Array of found valid pathes
	 *
	 * @todo maybe move to syscp::? can be enhanced to fit a more general purpose
	 */
	function findDirs ( $path, $uid, $gid, $_fileList = array() )
	{
		$dh = opendir( $path );
		while ( false !== ( $file = readdir( $dh ) ) )
		{
			if ( $file == '.' && (    fileowner( $path.'/'.$file ) == $uid
			                       || filegroup( $path.'/'.$file ) == $gid
			                     )
			   )
			{
				$_fileList[] = $path.'/';
			}
			if ( $file != '..' && $file != '.' && is_dir( $path.'/'.$file ) && is_readable($path.'/'.$file))
			{
				$_fileList = findDirs( $path.'/'.$file, $uid, $gid, $_fileList );
			}
		}
		closedir( $dh );
		return $_fileList;
	}

	/**
	 * Returns a valid html tag for the choosen $fieldType for pathes
	 *
	 * @author Martin Burchert  <martin.burchert@syscp.org>
	 * @author Manuel Bernhardt <manuel.bernhardt@syscp.org>
	 *
	 * @param  string   The path to start searching in
	 * @param  integer  The uid which must match the found directories
	 * @param  integer  The gid which must match the found direcotries
	 * @param  string   Either "Manual" or "Dropdown"
	 *
	 * @return string   The html tag for the choosen $fieldType
	 *
	 * @todo needs to be reworked, generating html here is uh ugly.
	 */
	function makePathfield( $path, $uid, $gid, $fieldType, $value='' )
	{
		global $lng;

		$value = str_replace( $path, '', $value );
		$field = '';
		if ( $fieldType == 'Manual' )
		{
			$field = '<input type="text" name="path" value="'.$value.'" size="30" />';
		}
		elseif ( $fieldType == 'Dropdown' )
		{
			$dirList = findDirs( $path, $uid, $gid );
			if ( sizeof( $dirList ) > 0 )
			{
				$field   = '<select name="path">';
				foreach ( $dirList as $key => $dir )
				{
					$dir    = str_replace( $path, '', $dir );
					$field .= makeoption( $dir, $dir, $value);
				}
				$field .= '</select>';
			}
			else
			{
				$field = $lng['panel']['dirsmissing'];
			}
		}
		return $field;
	}

?>
