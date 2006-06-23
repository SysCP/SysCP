<?php

			$result=$this->DatabaseHandler->query_first("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					if ( CRYPT_STD_DES == 1 )
					{
						$saltfordescrypt = substr(md5(uniqid(microtime(),1)),4,2);
						$password = addslashes(crypt($_POST['password'], $saltfordescrypt));
					}
					else
					{
						$password = addslashes(crypt($_POST['password']));
					}
					$passwordtest=$_POST['password'];
					if ($passwordtest=='')
					{
						$this->TemplateHandler->showError(array('stringisempty','mypassword'));
						return false;
					}
					else
					{
						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_HTPASSWDS."` SET `password`='$password' WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");

						$this->HookHandler->call( 'OnUpdateHTPasswd',
						              array( 'id'   => $this->ConfigHandler->get('env.id'),
						                     'path' => $result['path'] ) );

            			$this->redirectTo(array('module' => 'extras',
            			                        'action' => 'listHtpasswds'));
					}
				}
				else
				{
					$result['path']=str_replace($this->User['homedir'],'',$result['path']);
					$this->TemplateHandler->set('result', $result);
					$this->TemplateHandler->setTemplate('SysCP/extras/customer/htpasswd_edit.tpl');
				}
			}