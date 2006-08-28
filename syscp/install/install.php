<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 13, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * Most elements are taken from the phpBB (www.phpbb.com)
 * installer, (c) 1999 - 2004 phpBB Group.
 *
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package System
 * @version $Id$
 */

	if(file_exists('../lib/userdata.inc.php'))
	{
		/**
		 * Includes the Usersettings eg. MySQL-Username/Passwort etc. to test if SysCP is already installed
		 */
		require('../lib/userdata.inc.php');
		if( isset ($sql) && is_array ($sql) )
		{
			die('Sorry, SysCP is already configured...');
		}
	}

	/**
	 * Include the functions
	 */
	require('../lib/functions.php');

	/**
	 * Include the MySQL-Connection-Class
	 */
	require('../lib/class_mysqldb.php');

	/**
	 * Include the MySQL-Table-Definitions
	 */
	require('../lib/tables.inc.php');

	/**
	 * Language Managament
	 */
	$languages = Array( 'german' => 'Deutsch' , 'english' => 'English' , 'french' => 'Francais' ) ;
	$standardlanguage = 'english';
	if(isset($_GET['language']) && isset($languages[$_GET['language']]))
	{
		$language = $_GET['language'];
	}
	elseif(isset($_POST['language']) && isset($languages[$_POST['language']]))
	{
		$language = $_POST['language'];
	}
	else
	{
		$language = $standardlanguage;
	}

	if(file_exists('./lng/'.$language.'.lng.php'))
	{
		/**
		 * Includes file /lng/$language.lng.php if it exists
		 */
		require('./lng/'.$language.'.lng.php');
	}

	/**
	 * BEGIN FUNCTIONS -----------------------------------------------
	 */

	function page_header()
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" />
	<link rel="stylesheet" href="../templates/main.css" type="text/css" />
	<title>SysCP</title>
</head>
	<body style="margin: 0; padding: 0;" onload="document.loginform.loginname.focus()">
		<!--
			We request you retain the full copyright notice below including the link to www.syscp.org.
			This not only gives respect to the large amount of time given freely by the developers
			but also helps build interest, traffic and use of SysCP. If you refuse
			to include even this then support on our forums may be affected.
			The SysCP Team : 2003-2006
		// -->
		<!--
			Templates by Luca Piona (info@havanastudio.ch) and Luca Longinotti (chtekk@gentoo.org)
		// -->
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="800"><img src="../images/header.gif" width="800" height="90" alt="" /></td>
				<td class="header">&nbsp;</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td valign="top" bgcolor="#FFFFFF">
				<br />
				<br />
<?php
	}

	function page_footer()
	{
?>
				</td>
			</tr>
		</table>
		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td width="100%" class="footer">
					<br />SysCP &copy; 2003-2006 by <a href="http://www.syscp.org/" target="_blank">the SysCP Team</a>
					<br />&nbsp;
					<br /><a href="http://validator.w3.org/check?uri=referer" target="_blank"><img src="../images/valid-xhtml10.gif" alt="Valid XHTML 1.0 Transitional" height="15" width="80" border="0" /></a>&nbsp;&nbsp;<a href="http://jigsaw.w3.org/css-validator/" target="_blank"><img style="border:0;width:80px;height:15px" src="../images/valid-css.gif" alt="Valid CSS!" border="0" /></a>
					<br />&nbsp;
				</td>
			</tr>
		</table>
	</body>
</html>
<?php
	}

	function status_message($case, $text)
	{
		if($case == 'begin')
		{
			echo "\t\t<tr>\n\t\t\t<td class=\"main_field_name\">$text";
		}
		else
		{
			echo " <span style=\"color:$case;\">$text</span></td>\n\t\t</tr>\n";
		}
	}

	//
	// remove_remarks will strip the sql comment lines out of an uploaded sql file
	//
	function remove_remarks($sql)
	{
		$lines = explode("\n", $sql);

		// try to keep mem. use down
		$sql = "";

		$linecount = count($lines);
		$output = "";

		for ($i = 0; $i < $linecount; $i++)
		{
			if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0))
			{
				if (substr($lines[$i], 0, 1) != "#")
				{
					$output .= $lines[$i] . "\n";
				}
				else
				{
					$output .= "\n";
				}
				// Trading a bit of speed for lower mem. use here.
				$lines[$i] = "";
			}
		}
		return $output;
	}

	//
	// split_sql_file will split an uploaded sql file into single sql statements.
	// Note: expects trim() to have already been run on $sql.
	//
	function split_sql_file($sql, $delimiter)
	{
		// Split up our string into "possible" SQL statements.
		$tokens = explode($delimiter, $sql);

		// try to save mem.
		$sql = "";
		$output = array();

		// we don't actually care about the matches preg gives us.
		$matches = array();

		// this is faster than calling count($oktens) every time thru the loop.
		$token_count = count($tokens);
		for ($i = 0; $i < $token_count; $i++)
		{
			// Don't wanna add an empty string as the last thing in the array.
			if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0)))
			{
				// This is the total number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				// Counts single quotes that are preceded by an odd number of backslashes,
				// which means they're escaped quotes.
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);

				$unescaped_quotes = $total_quotes - $escaped_quotes;

				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if (($unescaped_quotes % 2) == 0)
				{
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = "";
				}
				else
				{
				// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;
					// save memory..
					$tokens[$i] = "";

					// Do we have a complete statement yet?
					$complete_stmt = false;

					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++)
					{
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means they're escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

						$unescaped_quotes = $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1)
						{
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];

							// save memory.
							$tokens[$j] = "";
							$temp = "";

							// exit the loop.
							$complete_stmt = true;
							// make sure the outer loop continues at the right point.
							$i = $j;
						}
						else
						{
							// even number of unescaped quotes. We still don't have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = "";
						}

					} // for..
				} // else
			}
		}
		return $output;
	}

	/**
	 * END FUNCTIONS ---------------------------------------------------
	 */



	/**
	 * BEGIN VARIABLES ---------------------------------------------------
	 */

	//guess Servername
	if(!empty($_POST['servername']))
	{
		$servername = $_POST['servername'];
	}
	else
	{
		if(!empty($_SERVER['SERVER_NAME']))
		{
			$servername = $_SERVER['SERVER_NAME'];
		}
		else
		{
			$servername = '';
		}
	}

	//guess serverip
	if(!empty($_POST['serverip']))
	{
		$serverip = $_POST['serverip'];
	}
	else
	{
		if(!empty($_SERVER['SERVER_ADDR']))
		{
			$serverip = $_SERVER['SERVER_ADDR'];
		}
		else
		{
			$serverip = '';
		}
	}

	if(!empty($_POST['mysql_host']))
	{
		$mysql_host = $_POST['mysql_host'];
	}
	else
	{
		$mysql_host = '127.0.0.1';
	}

	if(!empty($_POST['mysql_database']))
	{
		$mysql_database = $_POST['mysql_database'];
	}
	else
	{
		$mysql_database = 'syscp';
	}

	if(!empty($_POST['mysql_unpriv_user']))
	{
		$mysql_unpriv_user = $_POST['mysql_unpriv_user'];
	}
	else
	{
		$mysql_unpriv_user = 'syscp';
	}

	if(!empty($_POST['mysql_unpriv_pass']))
	{
		$mysql_unpriv_pass = $_POST['mysql_unpriv_pass'];
	}
	else
	{
		$mysql_unpriv_pass = '';
	}

	if(!empty($_POST['mysql_root_user']))
	{
		$mysql_root_user = $_POST['mysql_root_user'];
	}
	else
	{
		$mysql_root_user = 'root';
	}

	if(!empty($_POST['mysql_root_pass']))
	{
		$mysql_root_pass = $_POST['mysql_root_pass'];
	}
	else
	{
		$mysql_root_pass = '';
	}

	if(!empty($_POST['admin_user']))
	{
		$admin_user = $_POST['admin_user'];
	}
	else
	{
		$admin_user = 'admin';
	}

	if(!empty($_POST['admin_pass1']))
	{
		$admin_pass1 = $_POST['admin_pass1'];
	}
	else
	{
		$admin_pass1 = '';
	}

	if(!empty($_POST['admin_pass2']))
	{
		$admin_pass2 = $_POST['admin_pass2'];
	}
	else
	{
		$admin_pass2 = '';
	}

	if($mysql_host == 'localhost')
	{
		$mysql_access_host = 'localhost';
	}
	else
	{
		$mysql_access_host = $serverip;
	}

	/**
	 * END VARIABLES ---------------------------------------------------
	 */




	/**
	 * BEGIN INSTALL ---------------------------------------------------
	 */

	if(isset($_POST['installstep']) && $_POST['installstep'] == '1' && $admin_pass1 == $admin_pass2 && $admin_pass1 != '' && $admin_pass2 != '' && $mysql_unpriv_pass != '' && $mysql_root_pass != '' && $servername != '' && $serverip != '' && $mysql_unpriv_user != $mysql_root_user)
	{
		page_header();
?>
	<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle"><b><img src="../images/title.gif" alt="" />&nbsp;SysCP Installation</b></td>
		</tr>
<?php
		//first test if we can access the database server with the given root user and password
		status_message('begin', $lng['install']['testing_mysql']);
		$db_root = new db($mysql_host, $mysql_root_user, $mysql_root_pass, '');
		//ok, if we are here, the database class is build up (otherwise it would have already die'd this script)
		status_message('green', 'OK');

		//so first we have to delete the database and the user given for the unpriv-user if they exit
		status_message('begin', $lng['install']['erasing_old_db']);
		$db_root->query("DELETE FROM `mysql`.`user` WHERE `User` = '".$db_root->escape($mysql_unpriv_user)."' AND `Host` = '".$db_root->escape($mysql_access_host)."'");
		$db_root->query("DELETE FROM `mysql`.`db` WHERE `User` = '".$db_root->escape($mysql_unpriv_user)."' AND `Host` = '".$db_root->escape($mysql_access_host)."'");
		$db_root->query("DELETE FROM `mysql`.`tables_priv` WHERE `User` = '".$db_root->escape($mysql_unpriv_user)."' AND `Host` = '".$db_root->escape($mysql_access_host)."'");
		$db_root->query("DELETE FROM `mysql`.`columns_priv` WHERE `User` = '".$db_root->escape($mysql_unpriv_user)."' AND `Host` = '".$db_root->escape($mysql_access_host)."'");
		$db_root->query("DROP DATABASE IF EXISTS `".$db_root->escape(str_replace('`', '', $mysql_database))."` ;");
		$db_root->query("FLUSH PRIVILEGES;");
		status_message('green', 'OK');

		//then we have to create a new user and database for the syscp unprivileged mysql access
		status_message('begin', $lng['install']['create_mysqluser_and_db']);
		$db_root->query("CREATE DATABASE `".$db_root->escape(str_replace('`', '', $mysql_database))."`");
		$db_root->query("GRANT ALL PRIVILEGES ON `".$db_root->escape(str_replace('`', '', $mysql_database))."`.* TO '".$db_root->escape($mysql_unpriv_user)."'@'".$db_root->escape($mysql_access_host)."' IDENTIFIED BY 'password'");
		$db_root->query("SET PASSWORD FOR '".$db_root->escape($mysql_unpriv_user)."'@'".$db_root->escape($mysql_access_host)."' = PASSWORD('".$db_root->escape($mysql_unpriv_pass)."')");
		$db_root->query("FLUSH PRIVILEGES;");
		status_message('green', 'OK');

		//now a new database and the new syscp-unprivileged-mysql-account have been created and we can fill it now with the data.
		status_message('begin', $lng['install']['testing_new_db']);
		$db = new db($mysql_host, $mysql_unpriv_user, $mysql_unpriv_pass, $mysql_database);
		status_message('green', 'OK');

		status_message('begin', $lng['install']['importing_data']);
		$db_schema = './syscp.sql';
		$sql_query = @file_get_contents($db_schema, 'r');
		$sql_query = remove_remarks($sql_query);
		$sql_query = split_sql_file($sql_query, ';');

		for ($i = 0; $i < sizeof($sql_query); $i++)
		{
			if (trim($sql_query[$i]) != '')
			{
				$result = $db->query($sql_query[$i]);
			}
		}
		status_message('green', 'OK');

		//now let's change the settings in our settings-table
		status_message('begin', $lng['install']['changing_data']);
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = 'admin@".$db->escape($servername)."' WHERE `settinggroup` = 'panel' AND `varname` = 'adminmail'");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$db->escape($serverip)."' WHERE `settinggroup` = 'system' AND `varname` = 'ipaddress'");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$db->escape($servername)."' WHERE `settinggroup` = 'system' AND `varname` = 'hostname'");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$db->escape($version)."' WHERE `settinggroup` = 'panel' AND `varname` = 'version'");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$db->escape($languages[$language])."' WHERE `settinggroup` = 'panel' AND `varname` = 'standardlanguage'");
		$db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$db->escape($mysql_access_host)."' WHERE `settinggroup` = 'system' AND `varname` = 'mysql_access_host'");

		// insert the lastcronrun to be the installation date
		$query = 'UPDATE `%s` ' .
		         'SET `value` = UNIX_TIMESTAMP() ' .
		         'WHERE `settinggroup` = \'system\' ' .
		         '  AND `varname` = \'lastcronrun\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS );
		$db->query($query);

		// and lets insert the default ip and port
		$query = 'INSERT INTO `%s` ' .
		         ' SET `ip`   = \'%s\', ' .
		         '     `port` = \'80\' ';
		$query = sprintf( $query, TABLE_PANEL_IPSANDPORTS, $db->escape($serverip) );
		$db->query($query);

		$defaultip = $db->insert_id();
		// insert the defaultip
		$query = 'UPDATE `%s` ' .
		         'SET `value` = \'%s\' ' .
		         'WHERE `settinggroup` = \'system\' ' .
		         '  AND `varname` = \'defaultip\'';
		$query = sprintf( $query, TABLE_PANEL_SETTINGS, $db->escape($defaultip) );
		$db->query($query);

		status_message('green', 'OK');

		//last but not least create the main admin
		status_message('begin', $lng['install']['adding_admin_user']);
		$db->query("INSERT INTO `".TABLE_PANEL_ADMINS."` (`loginname`, `password`, `name`, `email`, `customers`, `customers_used`, `customers_see_all`, `domains`, `domains_used`, `domains_see_all`, `change_serversettings`, `diskspace`, `diskspace_used`, `mysqls`, `mysqls_used`, `emails`, `emails_used`, `email_accounts`, `email_accounts_used`, `email_forwarders`, `email_forwarders_used`, `ftps`, `ftps_used`, `subdomains`, `subdomains_used`, `traffic`, `traffic_used`, `deactivated`) VALUES ('".$db->escape($admin_user)."', '".md5($admin_pass1)."', 'Siteadmin', 'admin@".$db->escape($servername)."', -1, 0, 1, -1, 0, 1, 1, -1024, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1048576, 0, 0)");
		status_message('green', 'OK');

		//now we create the userdata.inc.php with the mysql-accounts
		status_message('begin', $lng['install']['creating_configfile']);
		$userdata="<?php\n";
		$userdata.="//automatically generated userdata.inc.php for SysCP\n";
		$userdata.="\$sql['host']='".addcslashes($mysql_host, "'\\")."';\n";
		$userdata.="\$sql['user']='".addcslashes($mysql_unpriv_user, "'\\")."';\n";
		$userdata.="\$sql['password']='".addcslashes($mysql_unpriv_pass, "'\\")."';\n";
		$userdata.="\$sql['db']='".addcslashes($mysql_database, "'\\")."';\n";
		$userdata.="\$sql['root_user']='".addcslashes($mysql_root_user, "'\\")."';\n";
		$userdata.="\$sql['root_password']='".addcslashes($mysql_root_pass, "'\\")."';\n";
		$userdata.="?>";

		//we test now if we can store the userdata.inc.php in ../lib
		if($fp = @fopen('../lib/userdata.inc.php', 'w'))
		{
			$result = @fputs($fp, $userdata, strlen($userdata));
			@fclose($fp);
			status_message('green', $lng['install']['creating_configfile_succ']);
			chmod('../lib/userdata.inc.php', 0440);
		}
		elseif($fp = @fopen('/tmp/userdata.inc.php', 'w'))
		{
			$result = @fputs($fp, $userdata, strlen($userdata));
			@fclose($fp);
			status_message('orange', $lng['install']['creating_configfile_temp']);
			chmod('/tmp/userdata.inc.php', 0440);
		}
		else
		{
			status_message('red', $lng['install']['creating_configfile_failed']);
			echo "\t\t<tr>\n\t\t\t<td class=\"main_field_name\"><p>".nl2br(htmlspecialchars($userdata))."</p></td>\n\t\t</tr>\n";
		}
?>
		<tr>
			<td class="main_field_display" align="center">
				<?php echo $lng['install']['syscp_succ_installed']; ?><br />
				<a href="../index.php"><?php echo $lng['install']['click_here_to_login']; ?></a>
			</td>
		</tr>
	</table>
	<br />
	<br />
<?php
		page_footer();
	}
	else
	{
		page_header();
?>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="get">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="../images/title.gif" alt="" />&nbsp;<?php echo $lng['install']['welcome']; ?></b></td>
			</tr>
			<tr>
				<td class="main_field_name" colspan="2"><?php echo $lng['install']['welcometext']; ?></td>
			</tr>
			<tr>
				<td class="main_field_name"><?php echo $lng['install']['language']; ?>: </td>
				<td class="main_field_display" nowrap="nowrap">
					<select name="language" class="dropdown_noborder"><?php
					$language_options = '';
					while(list($language_file, $language_name) = each($languages))
					{
						$language_options .= "\n\t\t\t\t\t\t".makeoption($language_name, $language_file, $language);
					}
					echo $language_options;
				?>

					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input class="bottom" type="submit" name="chooselang" value="Go" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="../images/title.gif" alt="" />&nbsp;<?php echo $lng['install']['database']; ?></b></td>
			</tr>
			<tr>
				<td class="main_field_name"><?php echo $lng['install']['mysql_hostname']; ?>:</td>
				<td class="main_field_display"><input type="text" name="mysql_host" value="<?php echo htmlspecialchars($mysql_host); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"><?php echo $lng['install']['mysql_database']; ?>:</td>
				<td class="main_field_display"><input type="text" name="mysql_database" value="<?php echo htmlspecialchars($mysql_database); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo (($mysql_unpriv_user == $mysql_root_user) ? ' style="color:blue;"' : ''); ?>><?php echo $lng['install']['mysql_unpriv_user']; ?>:</td>
				<td class="main_field_display"><input type="text" name="mysql_unpriv_user" value="<?php echo htmlspecialchars($mysql_unpriv_user); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && $mysql_unpriv_pass == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['mysql_unpriv_pass']; ?>:</td>
				<td class="main_field_display"><input type="password" name="mysql_unpriv_pass" value="<?php echo htmlspecialchars($mysql_unpriv_pass); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo (($mysql_unpriv_user == $mysql_root_user) ? ' style="color:blue;"' : ''); ?>><?php echo $lng['install']['mysql_root_user']; ?>:</td>
				<td class="main_field_display"><input type="text" name="mysql_root_user" value="<?php echo htmlspecialchars($mysql_root_user); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && $mysql_root_pass == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['mysql_root_pass']; ?>:</td>
				<td class="main_field_display"><input type="password" name="mysql_root_pass" value="<?php echo htmlspecialchars($mysql_root_pass); ?>"></td>
			</tr>
			<tr>
				<td class="maintitle" colspan="2"><b><img src="../images/title.gif" alt="" />&nbsp;<?php echo $lng['install']['admin_account']; ?></b></td>
			</tr>
			<tr>
				<td class="main_field_name"><?php echo $lng['install']['admin_user']; ?>:</td>
				<td class="main_field_display"><input type="text" name="admin_user" value="<?php echo htmlspecialchars($admin_user); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && ($admin_pass1 == '' || $admin_pass1 != $admin_pass2)) ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['admin_pass']; ?>:</td>
				<td class="main_field_display"><input type="password" name="admin_pass1" value="<?php echo htmlspecialchars($admin_pass1); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && ($admin_pass2 == '' || $admin_pass1 != $admin_pass2)) ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['admin_pass_confirm']; ?>:</td>
				<td class="main_field_display"><input type="password" name="admin_pass2" value="<?php echo htmlspecialchars($admin_pass2); ?>"></td>
			</tr>
			<tr>
				<td class="maintitle" colspan="2"><b><img src="../images/title.gif" alt="" />&nbsp;<?php echo $lng['install']['serversettings']; ?></b></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && $servername == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['servername']; ?>:</td>
				<td class="main_field_display"><input type="text" name="servername" value="<?php echo htmlspecialchars($servername); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_name"<?php echo ((!empty($_POST['installstep']) && $serverip == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['serverip']; ?>:</td>
				<td class="main_field_display"><input type="text" name="serverip" value="<?php echo htmlspecialchars($serverip); ?>"></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="language" value="<?php echo htmlspecialchars($language); ?>"><input type="hidden" name="installstep" value="1"><input class="bottom" type="submit" name="submitbutton" value="<?php echo $lng['install']['next']; ?>"></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
<?php
		page_footer();
	}

	/**
	 * END INSTALL ---------------------------------------------------
	 */

?>