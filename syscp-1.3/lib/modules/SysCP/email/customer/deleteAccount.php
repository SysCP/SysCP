<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$result['popaccountid']."'");
					$result['destination'] = str_replace ( $result['email_full'] , '' , $result['destination'] ) ;
					$this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_VIRTUAL."` SET `destination` = '".makeCorrectDestination($result['destination'])."', `popaccountid` = '0' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `email_accounts_used` = `email_accounts_used` - 1 WHERE `customerid`='".$this->User['customerid']."'");
   					$this->redirectTo(array('module' => 'email',
   					                        'action' => 'edit',
   					                        'id' => $this->ConfigHandler->get('env.id')));
				}
				else
				{
					$this->TemplateHandler->showQuestion('email_reallydelete_account',
					                                     array('module' => 'email',
					                                           'id' => $this->ConfigHandler->get('env.id'),
					                                           'action' => $this->ConfigHandler->get('env.action')),
					                                     $this->IdnaHandler->decode($result['email_full']));
				}
			}
		}
