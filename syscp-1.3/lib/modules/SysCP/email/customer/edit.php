<?php
			$result = $this->DatabaseHandler->query_first("SELECT `id`, `email`, `email_full`, `iscatchall`, `destination`, `customerid`, `popaccountid` FROM `".TABLE_MAIL_VIRTUAL."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['email']) && $result['email']!='')
			{
				$result['email']       = $this->IdnaHandler->decode($result['email']);
				$result['email_full']  = $this->IdnaHandler->decode($result['email_full']);
				$result['destination'] = explode(' ', $result['destination']);
				foreach($result['destination'] as $key => $destination)
				{
					$result['destination'][$key] = $this->IdnaHandler->decode($destination);
					if($result['destination'][$key] == $result['email_full'] ||
					   $result['destination'][$key] == '')
					{
						unset($result['destination'][$key]);
					}
				}

				$this->TemplateHandler->set('result', $result);
				$this->TemplateHandler->setTemplate('SysCP/email/customer/edit.tpl');
			}