<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['email']) && $result['email']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$update_users_query_addon = '';
					if ( $result['destination'] != '' )
					{
						$result['destination'] = explode ( ' ', $result['destination'] ) ;
						$number_forwarders = count ($result['destination']);
						if ( $result['popaccountid'] != 0 )
						{
							$this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$result['popaccountid']."'");
							$update_users_query_addon = " , `email_accounts_used` = `email_accounts_used` - 1 ";
							$number_forwarders -= 1;
						}
					}
					else
					{
						$number_forwarders = 0;
					}
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `emails_used`=`emails_used` - 1 , `email_forwarders_used` = `email_forwarders_used` - $number_forwarders $update_users_query_addon WHERE `customerid`='".$this->User['customerid']."'");
					$this->redirectTo(array('module' => 'email',
					                        'action' => 'list'));
				}
				else
				{
					$this->TemplateHandler->showQuestion('email_reallydelete',
					                                     array('module' => 'email',
					                                           'id' => $this->ConfigHandler->get('env.id'),
					                                           'action' => $this->ConfigHandler->get('env.action')),
					                                     $this->IdnaHandler->decode($result['email_full']));
				}
			}
		}