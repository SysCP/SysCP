<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->query_first("SELECT `id`, `username`, `homedir`, `up_count`, `up_bytes`, `down_count`, `down_bytes` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
			if(isset($result['username']) && $result['username']!=$this->User['loginname'])
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$this->DatabaseHandler->query("UPDATE `".TABLE_FTP_USERS."` SET `up_count`=`up_count`+'".$result['up_count']."', `up_bytes`=`up_bytes`+'".$result['up_bytes']."', `down_count`=`down_count`+'".$result['down_count']."', `down_bytes`=`down_bytes`+'".$result['down_bytes']."' WHERE `username`='".$this->User['loginname']."' ");
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
					$this->DatabaseHandler->query("UPDATE `".TABLE_FTP_GROUPS."` SET `members`=REPLACE(`members`,',".$result['username']."','') WHERE `customerid`='".$this->User['customerid']."'");
//					$this->DatabaseHandler->query("DELETE FROM `".TABLE_FTP_GROUPS."` WHERE `customerid`='".$this->User['customerid']."' AND `id`='".$this->ConfigHandler->get('env.id')."'");
					if($this->User['ftps_used']=='1')
					{
						$resetaccnumber=" , `ftp_lastaccountnumber`='0'";
					}
					else
					{
						$resetaccnumber='';
					}
					$result=$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `ftps_used`=`ftps_used`-1 $resetaccnumber WHERE `customerid`='".$this->User['customerid']."'");
            		$this->redirectTo(array('module' => 'ftp',
            		                        'action' => 'list'));
				}
				else
				{
					$this->TemplateHandler->showQuestion('ftp_reallydelete',
					                                     array('module' => 'ftp',
					                                           'id' => $this->ConfigHandler->get('env.id'),
					                                           'action' => $this->ConfigHandler->get('env.action')),
					                                     $result['username']);
				}
			}
			else
			{
				$this->TemplateHandler->showError('ftp_cantdeletemainaccount');
				return false;
			}
		}