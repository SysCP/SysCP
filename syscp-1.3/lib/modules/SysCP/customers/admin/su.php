<?php
	if ($this->User['customers'] != '0')
    {
			$result=$this->DatabaseHandler->queryFirst("SELECT * FROM `".TABLE_PANEL_CUSTOMERS."` WHERE `customerid`='".$this->ConfigHandler->get('env.id')."' ".( $this->User['customers_see_all'] ? '' : " AND `adminid` = '{$this->User['adminid']}' "));
			if($result['loginname']!='')
			{
				// store some current session info
				$curLanguage = $_SESSION['language'];
				$curTheme    = $_SESSION['theme'];
				session_write_close();

				// create new session object, i.e. register a new session handler
				Syscp::uses('Syscp.Handler.Session');
				$this->SessionHandler = new Syscp_Handler_Session();
				$params = array('DatabaseHandler' => $this->DatabaseHandler,
				                'remoteAddr'      => $this->ConfigHandler->get('env.remote_addr'),
				                'httpUserAgent'   => $this->ConfigHandler->get('env.http_user_agent'),
				                'sessionTimeout'  => $this->ConfigHandler->get('session.sessiontimeout'));
				$this->SessionHandler->initialize($params);
//				$session  = new Syscp_Handler_Session( $db, $config );
				// generate new session for the user
				$s = md5(uniqid(microtime(),1));
				session_id($s);
				session_start();
				// fill in some defaults
				$_SESSION['userid'] = $result['customerid'];
				$_SESSION['adminsession'] = 0;
				$_SESSION['language'] = $curLanguage;
				$_SESSION['theme'] = $curTheme;
				// save the session and redirect
				session_write_close();
				$this->redirectTo(array('module'=>'index'));
			}
			else
			{
				$this->redirectTo(array('module' => 'login',
				                        'action' => 'login'));
			}
    }
