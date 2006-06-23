<?php
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$ip = addslashes($_POST['ip']);
					$port = intval($_POST['port']);

					$result=$this->DatabaseHandler->queryFirst("SELECT `id` FROM `".TABLE_PANEL_IPSANDPORTS."` WHERE `ip`='$ip' AND `port`='$port'");

					if($ip=='')
					{
						$this->TemplateHandler->showError(array('stringisempty','myipaddress'));
						return false;
					}
					elseif($port=='')
					{
						$this->TemplateHandler->showError(array('stringisempty','myport'));
						return false;
					}
					elseif($result['id']!='')
					{
						$this->TemplateHandler->showError('myipnotdouble');
						return false;
					}
					else
					{
						$this->DatabaseHandler->query("INSERT INTO `".TABLE_PANEL_IPSANDPORTS."` (`ip`, `port`) VALUES ('$ip', '$port')");
						$ipportID = $this->DatabaseHandler->insert_id();

						$this->HookHandler->call( 'OnCreateIPPort',
						              array( 'id' => $ipportID ) );

						$this->redirectTo(array('module' => 'ipsandports',
						                        'action' => 'list'));
					}
				}
				else
				{
					$this->TemplateHandler->setTemplate('SysCP/ipsandports/admin/add.tpl');
					//eval("echo \"".getTemplate("ipsandports/ipsandports_add")."\";");
				}