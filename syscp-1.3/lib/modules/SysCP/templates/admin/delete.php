<?php
		if(isset($_POST['subjectid']))
		{
			$subjectid=intval($_POST['subjectid']);
			$mailbodyid=intval($_POST['mailbodyid']);
		}
		elseif(isset($_GET['subjectid']))
		{
			$subjectid=intval($_GET['subjectid']);
			$mailbodyid=intval($_GET['mailbodyid']);
		}

		if($subjectid!=0 && $mailbodyid!=0)
		{
			$result=$this->DatabaseHandler->queryFirst("SELECT `language`, `varname` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$this->User['adminid']."' AND `id`='$subjectid'");
			if($result['varname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$this->DatabaseHandler->query("DELETE FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$this->User['adminid']."' AND (`id`='$subjectid' OR `id`='$mailbodyid')");
					$this->redirectTo(array('module'=>'templates',
					                        'action'=>'list'));
				}
				else
				{
					$this->TemplateHandler->showQuestion('admin_template_reallydelete',
					                                     array('module' => 'templates',
					                                           'subjectid' => $subjectid,
					                                           'mailbodyid' => $mailbodyid,
					                                           'action' => $this->ConfigHandler->get('env.action')),
					                                     $result['language'].' - '.$this->L10nHandler->get('admin.templates.'.str_replace('_subject','',$result['varname'])));
				}
			}
		}
