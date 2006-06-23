<?php
		$domains = '';
		$result=$this->DatabaseHandler->query("SELECT `domain` FROM `".TABLE_PANEL_DOMAINS."` WHERE `customerid`='".$this->User['customerid']."' AND `parentdomainid`='0' AND `id` <> '" . $this->User['standardsubdomain'] . "' ");
		while($row=$this->DatabaseHandler->fetch_array($result))
		{
			$row['domain'] = $this->IdnaHandler->decode($row['domain']);
			if($domains == '')
			{
				$domains=$row['domain'];
			}
			else
			{
				$domains.=', '.$row['domain'];
			}
		}

		$this->User['email'] = $this->IdnaHandler->decode($this->User['email']);

		$yesterday=time()-(60*60*24);
		$month=date('M Y', $yesterday);

		$this->User['diskspace']     = round($this->User['diskspace']/1024,4);
		$this->User['diskspace_used']= round($this->User['diskspace_used']/(1024*1024),4);
		$this->User['traffic']       = round($this->User['traffic']/(1024*1024),4);
		$this->User['traffic_used']  = round($this->User['traffic_used']/(1024*1024*1024),4);

		$this->User = str_replace_array('-1', $this->L10nHandler->get('customer.unlimited'), $this->User, 'diskspace traffic mysqls emails email_accounts email_forwarders ftps subdomains');

		$this->TemplateHandler->set('month', $month);
		$this->TemplateHandler->set('domains', $domains);
		$this->TemplateHandler->setTemplate('SysCP/index/customer/index.tpl');
		//eval("echo \"".getTemplate("index/index")."\";");
