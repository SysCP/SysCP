<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2007 the SysCP Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org>
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @package    Panel
 * @version    $Id: admin_admins.php 1845 2008-05-02 23:51:50Z atari $
 */

define('AREA', 'admin');

/**
 * Include our init.php, which manages Sessions, Language etc.
 */

require ("./lib/init.php");

if(isset($_POST['id']))
{
	$id = intval($_POST['id']);
}
elseif(isset($_GET['id']))
{
	$id = intval($_GET['id']);
}

if($page == 'message')
{
	if($action == '')
	{
		if(isset($_POST['send'])
		   && $_POST['send'] == 'send')
		{
			$bcc_headers = '';

			if($_POST['receipient'] == 0)
			{
				$result = $db->query('SELECT `loginname`, `name`, `email`  FROM `' . TABLE_PANEL_ADMINS . "`");

				while($row = $db->fetch_array($result))
				{
					$bcc_headers = 'Bcc: ' . $row['name'] . ' <' . $row['email'] . '> ' . "\r\n";
				}
			}
			elseif($_POST['receipient'] == 1)
			{
				if($userinfo['customers_see_all'] == "1")
				{
					$result = $db->query('SELECT `firstname`, `name`, `email`  FROM `' . TABLE_PANEL_CUSTOMERS . "`");
				}
				else
				{
					$result = $db->query('SELECT `firstname`, `name`, `email`  FROM `' . TABLE_PANEL_CUSTOMERS . "` WHERE `adminid`='" . $userinfo['adminid'] . "'");
				}

				while($row = $db->fetch_array($result))
				{
					$bcc_headers = 'Bcc: ' . $row['name'] . ' <' . $row['email'] . '> ' . "\r\n";
				}
			}
			else
			{
				standard_error('noreceipientsgiven');
			}

			$from = $db->query_first("SELECT `email`, `name` FROM `" . TABLE_PANEL_ADMINS . "` WHERE adminid='" . $userinfo['adminid'] . "'");
			$headers = 'From: ' . $from['name'] . ' <' . $from['email'] . '>' . "\r\n";
			$headers.= $bcc_headers;
			$subject = $_POST['subject'];
			$message = wordwrap($_POST['message'], 70);

			if(!empty($message))
			{
				mail('', $subject, $message, $headers);
				header("Location: $filename?page=$page&s=$s");
			}
			else
			{
				standard_error('nomessagetosend');
			}
		}
		else
		{
			$receipients = '';

			if($userinfo['customers_see_all'] == "1")
			{
				$receipients.= makeoption($lng['panel']['reseller'], 0);
			}

			$receipients.= makeoption($lng['panel']['customer'], 1);
			eval("echo \"" . getTemplate("message/message") . "\";");
		}
	}
}

?>
