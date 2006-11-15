<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * Most elements are taken from the phpBB (www.phpbb.com) installer,
 * (c) 1999 - 2004 phpBB Group.
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) the authors
 * @package    Org.Syscp.Installer
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:install.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

if(file_exists('../../etc/userdata.inc.php'))
{
    /**
     * Includes the Usersettings eg. MySQL-Username/Passwort etc. to test if SysCP is already installed
     */

    require_once '../../etc/userdata.inc.php';

    if(isset($sql)
       && is_array($sql))
    {
        die('Sorry, SysCP is already configured...');
    }
}

error_reporting(E_ALL);

/**
 * Include the functions
 */

require_once '../../lib/functions.php';

/**
 * Include the MySQL-Connection-Class
 */

require_once '../../lib/classes/Org/Syscp/Core/DB/Mysql.class.php';

/**
 * Include the MySQL-Table-Definitions
 */

require_once '../../etc/tables.inc.php';

/**
 * Language Managament
 */

$languages = Array(
    'german' => 'Deutsch',
    'english' => 'English',
    'french' => 'Francais'
);
$standardlanguage = 'english';

if(isset($_GET['language'])
   && isset($languages[$_GET['language']]))
{
    $language = addslashes($_GET['language']);
}
elseif(isset($_POST['language'])
       && isset($languages[$_POST['language']]))
{
    $language = addslashes($_POST['language']);
}
else
{
    $language = $standardlanguage;
}

if(file_exists('../../lib/lng/install/'.$language.'.lng.php'))
{
    /**
     * Includes file /lng/$language.lng.php if it exists
     */

    require_once '../../lib/lng/install/'.$language.'.lng.php';
}

/**
 * BEGIN FUNCTIONS -----------------------------------------------
 */

function page_header()
{

?>
<html>
<head>
<link href="../templates/main.css" rel="stylesheet" type="text/css">
<title>SysCP Installation</title>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<!--
    We request you retain the full copyright notice below including the link to www.syscp.de.
    This not only gives respect to the large amount of time given freely by the developers
    but also helps build interest, traffic and use of SysCP. If you refuse
    to include even this then support on our forums may be affected.
    The SysCP Team : 2003-2006
    Template made by Kirill Bauer.
// -->
  <div style="position:absolute; top:10px; right:10px">SysCP Installer &copy; 2003-2006 by <a href="http://www.syscp.de/" target="_blank">the SysCP Team</a></div>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td width="800"><img src="../images/header.gif" width="800" height="89"></td>
      <td background="../images/header_r.gif">&nbsp;</td>
    </tr>
  </table>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
   <tr>
     <td width="240" valign="top" background="../images/background_l.gif">&nbsp;</td>
     <td background="../images/background_m.gif" width="2">&nbsp;</td>
     <td background="../images/background_r.gif" class="maintable" valign="top"><br /><br />
<?php
}

function page_footer()
{

?>
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
        echo "\t\t<tr>\n\t\t\t<td class=\"maintable\">$text";
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
    for ($i = 0;$i < $linecount;$i++)
    {
        if(($i != ($linecount-1))
           || (strlen($lines[$i]) > 0))
        {
            if(substr($lines[$i], 0, 1) != "#")
            {
                $output.= $lines[$i]."\n";
            }
            else
            {
                $output.= "\n";
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
    for ($i = 0;$i < $token_count;$i++)
    {
        // Don't wanna add an empty string as the last thing in the array.

        if(($i != ($token_count-1))
           || (strlen($tokens[$i] > 0)))
        {
            // This is the total number of single quotes in the token.

            $total_quotes = preg_match_all("/'/", $tokens[$i], $matches);

            // Counts single quotes that are preceded by an odd number of backslashes,
            // which means they're escaped quotes.

            $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
            $unescaped_quotes = $total_quotes-$escaped_quotes;

            // If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.

            if(($unescaped_quotes%2) == 0)
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

                $temp = $tokens[$i].$delimiter;

                // save memory..

                $tokens[$i] = "";

                // Do we have a complete statement yet?

                $complete_stmt = false;
                for ($j = $i+1;(!$complete_stmt && ($j < $token_count));$j++)
                {
                    // This is the total number of single quotes in the token.

                    $total_quotes = preg_match_all("/'/", $tokens[$j], $matches);

                    // Counts single quotes that are preceded by an odd number of backslashes,
                    // which means they're escaped quotes.

                    $escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
                    $unescaped_quotes = $total_quotes-$escaped_quotes;

                    if(($unescaped_quotes%2) == 1)
                    {
                        // odd number of unescaped quotes. In combination with the previous incomplete
                        // statement(s), we now have a complete statement. (2 odds always make an even)

                        $output[] = $temp.$tokens[$j];

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

                        $temp.= $tokens[$j].$delimiter;

                        // save memory.

                        $tokens[$j] = "";
                    }
                }

                // for..
            }

            // else
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
    $servername = addslashes($_POST['servername']);
}
else
{
    if(!empty($_SERVER['SERVER_NAME']))
    {
        $servername = addslashes($_SERVER['SERVER_NAME']);
    }
    else
    {
        $servername = '';
    }
}

//guess serverip

if(!empty($_POST['serverip']))
{
    $serverip = addslashes($_POST['serverip']);
}
else
{
    if(!empty($_SERVER['SERVER_ADDR']))
    {
        $serverip = addslashes($_SERVER['SERVER_ADDR']);
    }
    else
    {
        $serverip = '';
    }
}

if(!empty($_POST['mysql_host']))
{
    $mysql_host = addslashes($_POST['mysql_host']);
}
else
{
    $mysql_host = 'localhost';
}

if(!empty($_POST['mysql_database']))
{
    $mysql_database = addslashes($_POST['mysql_database']);
}
else
{
    $mysql_database = 'syscp';
}

if(!empty($_POST['mysql_unpriv_user']))
{
    $mysql_unpriv_user = addslashes($_POST['mysql_unpriv_user']);
}
else
{
    $mysql_unpriv_user = 'syscp';
}

if(!empty($_POST['mysql_unpriv_pass']))
{
    $mysql_unpriv_pass = addslashes($_POST['mysql_unpriv_pass']);
}
else
{
    $mysql_unpriv_pass = '';
}

if(!empty($_POST['mysql_root_user']))
{
    $mysql_root_user = addslashes($_POST['mysql_root_user']);
}
else
{
    $mysql_root_user = 'root';
}

if(!empty($_POST['mysql_root_pass']))
{
    $mysql_root_pass = addslashes($_POST['mysql_root_pass']);
}
else
{
    $mysql_root_pass = '';
}

if(!empty($_POST['admin_user']))
{
    $admin_user = addslashes($_POST['admin_user']);
}
else
{
    $admin_user = 'admin';
}

if(!empty($_POST['admin_pass1']))
{
    $admin_pass1 = addslashes($_POST['admin_pass1']);
}
else
{
    $admin_pass1 = '';
}

if(!empty($_POST['admin_pass2']))
{
    $admin_pass2 = addslashes($_POST['admin_pass2']);
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

if(isset($_POST['installstep'])
   && $_POST['installstep'] == '1'
   && $admin_pass1 == $admin_pass2
   && $admin_pass1 != ''
   && $admin_pass2 != ''
   && $mysql_unpriv_pass != ''
   && $mysql_root_pass != ''
   && $servername != ''
   && $serverip != ''
   && $mysql_unpriv_user != $mysql_root_user)
{
    page_header();

?>
	<table celllpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintable" align="center" style="font-size: 18pt;">SysCP Installation</td>
		</tr>
<?php

    //first test if we can access the database server with the given root user and password

    status_message('begin', $lng['install']['testing_mysql']);
    $db_root = new Syscp_DB_Mysql($mysql_host, $mysql_root_user, $mysql_root_pass, '');

    //ok, if we are here, the database class is build up (otherwise it would have already die'd this script)

    status_message('green', 'OK');

    //so first we have to delete the database and the user given for the unpriv-user if they exit

    status_message('begin', $lng['install']['erasing_old_db']);
    $db_root->query("DELETE FROM `mysql`.`user` WHERE `User` = '$mysql_unpriv_user' AND `Host` = '$mysql_access_host';");
    $db_root->query("DELETE FROM `mysql`.`db` WHERE `User` = '$mysql_unpriv_user' AND `Host` = '$mysql_access_host';");
    $db_root->query("DELETE FROM `mysql`.`tables_priv` WHERE `User` = '$mysql_unpriv_user' AND `Host` = '$mysql_access_host';");
    $db_root->query("DELETE FROM `mysql`.`columns_priv` WHERE `User` = '$mysql_unpriv_user' AND `Host` = '$mysql_access_host';");
    $db_root->query("DROP DATABASE IF EXISTS `$mysql_database` ;");
    $db_root->query("FLUSH PRIVILEGES;");
    status_message('green', 'OK');

    //then we have to create a new user and database for the syscp unprivileged mysql access

    status_message('begin', $lng['install']['create_mysqluser_and_db']);
    $db_root->query("CREATE DATABASE `$mysql_database`;");
    $db_root->query("GRANT ALL PRIVILEGES ON `$mysql_database`.* TO $mysql_unpriv_user@$mysql_access_host IDENTIFIED BY 'password';");
    $db_root->query("SET PASSWORD FOR $mysql_unpriv_user@$mysql_access_host = PASSWORD('$mysql_unpriv_pass');");
    $db_root->query("FLUSH PRIVILEGES;");
    status_message('green', 'OK');

    //now a new database and the new syscp-unprivileged-mysql-account have been created and we can fill it now with the data.

    status_message('begin', $lng['install']['testing_new_db']);
    $db = new Syscp_DB_Mysql($mysql_host, $mysql_unpriv_user, $mysql_unpriv_pass, $mysql_database);
    status_message('green', 'OK');
    status_message('begin', $lng['install']['importing_data']);
    $db_schema = '../../lib/installer/syscp.sql';
    $sql_query = @fread(@fopen($db_schema, 'r'), @filesize($db_schema));
    $sql_query = remove_remarks($sql_query);
    $sql_query = split_sql_file($sql_query, ';');
    for ($i = 0;$i < sizeof($sql_query);$i++)
    {
        if(trim($sql_query[$i]) != '')
        {
            $result = $db->query($sql_query[$i]);
        }
    }

    status_message('green', 'OK');

    //now let's chenage the settings in our settings-table

    status_message('begin', $lng['install']['changing_data']);
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = 'admin@$servername' WHERE `settinggroup` = 'panel' AND `varname` = 'adminmail'");
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '$serverip' WHERE `settinggroup` = 'system' AND `varname` = 'ipaddress'");
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '$servername' WHERE `settinggroup` = 'system' AND `varname` = 'hostname'");
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '$version' WHERE `settinggroup` = 'panel' AND `varname` = 'version'");
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$languages[$language]."' WHERE `settinggroup` = 'panel' AND `varname` = 'standardlanguage'");
    $db->query("UPDATE `".TABLE_PANEL_SETTINGS."` SET `value` = '".$mysql_access_host."' WHERE `settinggroup` = 'system' AND `varname` = 'mysql_access_host'");

    // insert the lastcronrun to be the installation date

    $query = 'UPDATE `%s` '.'SET `value` = UNIX_TIMESTAMP() '.'WHERE `settinggroup` = \'system\' '.'  AND `varname` = \'lastcronrun\'';
    $query = sprintf($query, TABLE_PANEL_SETTINGS);
    $db->query($query);

    // and lets insert the default ip and port

    $db->query('INSERT INTO `panel_ipsandports`      '.' SET `ip`      = \''.$serverip.'\',  '.'     `port`    = \'80\',             '.'     `default` = \'1\'               ');
    status_message('green', 'OK');

    //last but not least create the main admin

    status_message('begin', $lng['install']['adding_admin_user']);
    $db->query("INSERT INTO `".TABLE_PANEL_ADMINS."` (`loginname`, `password`, `name`, `email`, `customers`, `customers_used`, `customers_see_all`, `domains`, `domains_used`, `domains_see_all`, `change_serversettings`, `diskspace`, `diskspace_used`, `mysqls`, `mysqls_used`, `emails`, `emails_used`, `email_accounts`, `email_accounts_used`, `email_forwarders`, `email_forwarders_used`, `ftps`, `ftps_used`, `subdomains`, `subdomains_used`, `traffic`, `traffic_used`, `deactivated`) VALUES ('".$admin_user."', '".md5($admin_pass1)."', 'Siteadmin', 'admin@$servername', -1, 0, 1, -1, 0, 1, 1, -1024, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1, 0, -1048576, 0, 0);");
    status_message('green', 'OK');

    //now we create the userdata.inc.php with the mysql-accounts

    status_message('begin', $lng['install']['creating_configfile']);
    $userdata = "<?php\n";
    $userdata.= "//automatically generated userdata.inc.php for SysCP\n";
    $userdata.= "\$sql['host']='$mysql_host';\n";
    $userdata.= "\$sql['user']='$mysql_unpriv_user';\n";
    $userdata.= "\$sql['password']='$mysql_unpriv_pass';\n";
    $userdata.= "\$sql['db']='$mysql_database';\n";
    $userdata.= "\$sql['root_user']='$mysql_root_user';\n";
    $userdata.= "\$sql['root_password']='$mysql_root_pass';\n";
    $userdata.= "?>";

    //we test now if we can store the userdata.inc.php in ../lib

    if($fp = @fopen('../../etc/userdata.inc.php', 'w'))
    {
        $result = @fputs($fp, $userdata, strlen($userdata));
        @fclose($fp);
        status_message('green', $lng['install']['creating_configfile_succ']);
        chmod('../../etc/userdata.inc.php', 0440);
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
        echo "\t\t<tr>\n\t\t\t<td class=\"maintable\"><p style=\" margin-left:150px;  margin-right:150px; padding: 9px; border:1px solid #999;\">".nl2br(htmlspecialchars($userdata))."</p></td>\n\t\t</tr>\n";
    }

?>
		<tr>
			<td class="maintable" align="center"><br /><?php echo $lng['install']['syscp_succ_installed']; ?><br /><a href="../index.php"><?php echo $lng['install']['click_here_to_login']; ?></a></td>
		</tr>
	</table><br />
<?php
    page_footer();
}
else
{
    page_header();

?>
	<table celllpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintable"><?php echo $lng['install']['language']; ?>: </td>
			<td class="maintable" align="left"><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get"><select name="language"><?php
    $language_options = '';

    while(list($language_file, $language_name) = each($languages))
    {
        $language_options.= makeoption($language_name, $language_file, $language);
    }

    echo $language_options;

?></select> <input type="submit" name="choselang" value="Go" /></form></td>
		</tr>
	</table><br />
	<table celllpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintable" align="center" style="font-size: 18pt;"><?php echo $lng['install']['welcome']; ?></td>
		</tr>
		<tr>
			<td class="maintable"><?php echo $lng['install']['welcometext']; ?></td>
		</tr>
	</table><br />
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<table celllpadding="3" cellspacing="1" border="0" align="center" class="maintable">
		<tr>
		 <td class="maintable" colspan="2" align="center" style="font-size: 15px; padding-top: 3px;"><b><?php echo $lng['install']['database']; ?></b></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"><?php echo $lng['install']['mysql_hostname']; ?>:</td>
		 <td class="maintable"><input type="text" name="mysql_host" value="<?php echo $mysql_host; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"><?php echo $lng['install']['mysql_database']; ?>:</td>
		 <td class="maintable"><input type="text" name="mysql_database" value="<?php echo $mysql_database; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo (($mysql_unpriv_user == $mysql_root_user) ? ' style="color:blue;"' : ''); ?>><?php echo $lng['install']['mysql_unpriv_user']; ?>:</td>
		 <td class="maintable"><input type="text" name="mysql_unpriv_user" value="<?php echo $mysql_unpriv_user; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && $mysql_unpriv_pass == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['mysql_unpriv_pass']; ?>:</td>
		 <td class="maintable"><input type="password" name="mysql_unpriv_pass" value="<?php echo $mysql_unpriv_pass; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo (($mysql_unpriv_user == $mysql_root_user) ? ' style="color:blue;"' : ''); ?>><?php echo $lng['install']['mysql_root_user']; ?>:</td>
		 <td class="maintable"><input type="text" name="mysql_root_user" value="<?php echo $mysql_root_user; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && $mysql_root_pass == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['mysql_root_pass']; ?>:</td>
		 <td class="maintable"><input type="password" name="mysql_root_pass" value="<?php echo $mysql_root_pass; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" colspan="2" align="center" style="font-size: 15px; padding-top: 7px;"><b><?php echo $lng['install']['admin_account']; ?></b></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"><?php echo $lng['install']['admin_user']; ?>:</td>
		 <td class="maintable"><input type="text" name="admin_user" value="<?php echo $admin_user; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && ($admin_pass1 == '' || $admin_pass1 != $admin_pass2)) ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['admin_pass']; ?>:</td>
		 <td class="maintable"><input type="password" name="admin_pass1" value="<?php echo $admin_pass1; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && ($admin_pass2 == '' || $admin_pass1 != $admin_pass2)) ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['admin_pass_confirm']; ?>:</td>
		 <td class="maintable"><input type="password" name="admin_pass2" value="<?php echo $admin_pass2; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" colspan="2" align="center" style="font-size: 15px; padding-top: 7px;"><b><?php echo $lng['install']['serversettings']; ?></b></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && $servername == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['servername']; ?>:</td>
		 <td class="maintable"><input type="text" name="servername" value="<?php echo $servername; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right"<?php echo ((!empty($_POST['installstep']) && $serverip == '') ? ' style="color:red;"' : ''); ?>><?php echo $lng['install']['serverip']; ?>:</td>
		 <td class="maintable"><input type="text" name="serverip" value="<?php echo $serverip; ?>"></td>
		</tr>
		<tr>
		 <td class="maintable" align="right" colspan="2" style=" padding-top: 10px;"><input type="hidden" name="language" value="<?php echo $language; ?>"><input type="hidden" name="installstep" value="1"><input type="submit" name="submitbutton" value="<?php echo $lng['install']['next']; ?>"></td>
		</tr>
	</table>
	</form><br />
<?php
    page_footer();
}

/**
 * END INSTALL ---------------------------------------------------
 */

?>