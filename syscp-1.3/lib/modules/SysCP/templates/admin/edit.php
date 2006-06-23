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

			$result=$this->DatabaseHandler->queryFirst("SELECT `language`, `varname`, `value` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='".$this->User['adminid']."' AND `id`='$subjectid'");
			if($result['varname']!='')
			{
				if(isset($_POST['send']) && $_POST['send']=='send')
				{
					$subject = htmlentities(addslashes($_POST['subject']));
					$mailbody = htmlentities(addslashes($_POST['mailbody']));

					if($subject == '')
					{
						$this->TemplateHandler->showError('nosubjectcreate');
						return false;
					}
					elseif($mailbody == '')
					{
						$this->TemplateHandler->showError('nomailbodycreate');
						return false;
					}

					else
					{
						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_TEMPLATES."` SET `value`='$subject' WHERE `adminid`='".$this->User['adminid']."' AND `id`='$subjectid'");
						$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_TEMPLATES."` SET `value`='$mailbody' WHERE `adminid`='".$this->User['adminid']."' AND `id`='$mailbodyid'");

    					$this->redirectTo(array('module'=>'templates',
    					                        'action'=>'list'));
					}
				}
				else
				{
					$tpl_name = $this->L10nHandler->get('admin.templates.'.str_replace('_subject','',$result['varname']));
					//$temp =$lng['admin']['templates'][str_replace('_subject','',$result['varname'])];
					$subject=$result['value'];
					$result=$this->DatabaseHandler->queryFirst("SELECT `language`, `varname`, `value` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `id`='$mailbodyid'");
					$mailbody=$result['value'];

					$this->TemplateHandler->set('mailbody', $mailbody);
					$this->TemplateHandler->set('subject', $subject);
					$this->TemplateHandler->set('mailbodyid', $mailbodyid);
					$this->TemplateHandler->set('subjectid', $subjectid);
					$this->TemplateHandler->set('lang', $result['language']);
					$this->TemplateHandler->set('tpl_name', $tpl_name);
					$this->TemplateHandler->setTemplate('SysCP/templates/admin/edit.tpl');
					//eval("echo \"".getTemplate("templates/templates_edit")."\";");
				}
			}
