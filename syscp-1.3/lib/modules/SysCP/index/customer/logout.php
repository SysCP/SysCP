<?php
		session_destroy();
		//$this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_SESSIONS."` WHERE `userid` = '{$this->User['customerid']}' AND `adminsession` = '0'");
		$this->redirectTo(array('module' => 'login',
		                        'action' => 'login'));
		return true;