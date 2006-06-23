<?php
			$result=$this->DatabaseHandler->query("SELECT * FROM `".TABLE_PANEL_HTACCESS."` WHERE `customerid`='".$this->User['customerid']."'");
			$htaccess = array();
			while($row=$this->DatabaseHandler->fetch_array($result))
			{
				$row['path']=str_replace($this->User['homedir'],'',$row['path']);
				$row['options_indexes'] = str_replace('1', $this->L10nHandler->get('panel.yes'), $row['options_indexes']);
				$row['options_indexes'] = str_replace('0', $this->L10nHandler->get('panel.no'), $row['options_indexes']);

				$htaccess[] = $row;
			}
			$this->TemplateHandler->set('htaccess', $htaccess);
			$this->TemplateHandler->setTemplate('SysCP/extras/customer/htaccess_list.tpl');
			//eval("echo \"".getTemplate("extras/htaccess")."\";");