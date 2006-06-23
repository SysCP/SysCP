<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->query_first("SELECT `id`, `customerid`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['username']) && $result['username']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
					$this->HookHandler->call( 'OnDeleteHTPasswd',
					              array( 'id'   => $this->ConfigHandler->get('env.id'),
					                     'path' => $result['path'] ) );
           			$this->redirectTo(array('module' => 'extras',
           			                        'action' => 'listHtpasswd'));
				}
				else
				{
					$this->TemplateHandler->showQuestion('extras_reallydelete',
					                                     array('module' => 'extras',
					                                           'id' => $this->ConfigHandler->get('env.id'),
					                                           'action' => $this->ConfigHandler->get('env.action')),
					                                     $result['username'] . ' (' . str_replace($this->User['homedir'],'',$result['path']) . ')');
				}
			}