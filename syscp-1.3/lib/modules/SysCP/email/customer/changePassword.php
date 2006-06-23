<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['popaccountid']) && $result['popaccountid']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$password=addslashes($_POST['password']);
					if($password=='')
					{
						standard_error(array('stringisempty','mypassword'));
						exit;
					}
					else
					{
						$result=$this->DatabaseHandler->query("UPDATE `".TABLE_MAIL_USERS."` SET `password` = '$password', `password_enc`=ENCRYPT('$password') WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$result['popaccountid']."'");
       					$this->redirectTo(array('module' => 'email',
       					                        'action' => 'edit',
       					                        'id' => $this->ConfigHandler->get('env.id')));
					}
				}
				else
				{
					$result['email_full'] = $this->IdnaHandler->decode($result['email_full']);
					$this->TemplateHandler->set('result', $result);
					$this->TemplateHandler->setTemplate('SysCP/email/customer/changePass.tpl');
				}
			}
		}
