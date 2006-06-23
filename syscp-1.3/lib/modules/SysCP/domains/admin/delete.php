<?php
		if($this->ConfigHandler->get('env.id')!=0)
		{
			$result=$this->DatabaseHandler->queryFirst("SELECT `d`.`id`, `d`.`domain`, `d`.`customerid`, `d`.`documentroot`, `d`.`isemaildomain`, `d`.`zonefile` FROM `".TABLE_PANEL_DOMAINS."` `d`, `".TABLE_PANEL_CUSTOMERS."` `c` WHERE `d`.`id`='".$this->ConfigHandler->get('env.id')."' AND `d`.`id` <> `c`.`standardsubdomain`".( $this->User['customers_see_all'] ? '' : " AND `d`.`adminid` = '{$this->User['adminid']}' "));
			$alias_check=$this->DatabaseHandler->queryFirst('SELECT COUNT(`id`) AS `count` FROM `'.TABLE_PANEL_DOMAINS.'` WHERE `aliasdomain`=\''.$this->ConfigHandler->get('env.id').'\'');
			if($result['domain']!='' && $alias_check['count'] == 0)
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$query =
						'SELECT `id` ' .
						'FROM `'.TABLE_PANEL_DOMAINS.'` ' .
						'WHERE (`id`="'.$this->ConfigHandler->get('env.id').'" OR `parentdomainid`="'.$this->ConfigHandler->get('env.id').'") ' .
						'  AND  `isemaildomain`="1"';
					$subResult = $this->DatabaseHandler->query($query);
					$idString = array();
					while ( $subRow = $this->DatabaseHandler->fetch_array($subResult) )
					{
						$idString[] = '`domainid` = "'.$subRow['id'].'"';
					}
					$idString = implode(' OR ', $idString);
					if ( $idString != '' )
					{
						$query =
							'DELETE FROM `'.TABLE_MAIL_USERS.'` ' .
							'WHERE '.$idString;
						$this->DatabaseHandler->query($query);
						$query =
							'DELETE FROM `'.TABLE_MAIL_VIRTUAL.'` ' .
							'WHERE '.$idString;
						$this->DatabaseHandler->query($query);
					}
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_DOMAINS."` WHERE `id`='".$this->ConfigHandler->get('env.id')."' OR `parentdomainid`='".$result['id']."'");
					$deleted_domains = $this->DatabaseHandler->affected_rows();
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `subdomains_used` = `subdomains_used` - 0".($deleted_domains - 1)." WHERE `customerid` = '{$result['customerid']}'");
					$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `domains_used` = `domains_used` - 1 WHERE `adminid` = '{$this->User['adminid']}'");
					/**
					 * @todo Find a way to reimplement this
					 */
					// updateCounters () ;

					$this->HookHandler->call( 'OnDeleteDomain',
					              array( 'id' => $this->ConfigHandler->get('env.id') ) );

					$this->redirectTo(array('module' => 'domains',
					                        'action' => 'list'));
				}
				else
				{
					$this->TemplateHandler->showQuestion('admin_domain_reallydelete',
					                    array('module' => 'domains',
					                          'id'     => $this->ConfigHandler->get('env.id'),
					                          'action' => $this->ConfigHandler->get('env.action')),
					                    $this->IdnaHandler->decode($result['domain']));
					return true;
				}
			}
		}