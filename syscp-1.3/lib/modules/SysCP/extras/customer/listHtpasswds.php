<?php
			$result = $this->DatabaseHandler->query("SELECT `id`, `username`, `path` FROM `".TABLE_PANEL_HTPASSWDS."` WHERE `customerid`='".$this->User['customerid']."' ORDER BY `username` ASC");
			$htpasswds = array();
			while($row=$this->DatabaseHandler->fetch_array($result))
			{
				$row['path']=str_replace($this->User['homedir'],'',$row['path']);
				$htpasswds[] = $row;
			}
			$this->TemplateHandler->set('htpasswds', $htpasswds);
			$this->TemplateHandler->setTemplate('SysCP/extras/customer/htpasswd_list.tpl');

