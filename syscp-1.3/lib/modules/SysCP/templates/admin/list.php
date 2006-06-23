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

			$available_templates=array(
				'createcustomer',
				'pop_success'
			);

			$templates_array=array();
			$result=$this->DatabaseHandler->query("SELECT `id`, `language`, `varname` FROM `".TABLE_PANEL_TEMPLATES."` WHERE `adminid`='{$this->User['adminid']}' AND `templategroup`='mails' ORDER BY `language`, `varname`");
			while($row=$this->DatabaseHandler->fetch_array($result))
			{
				$parts=array();
				preg_match('/^([a-z]([a-z_]+[a-z])*)_(mailbody|subject)$/',$row['varname'],$parts);
				$templates_array[$row['language']][$parts[1]][$parts[3]]=$row['id'];
			}
			$templates = array();
			foreach($templates_array as $tLanguage => $template_defs)
			{
				foreach($template_defs as $tAction => $email)
				{
					$subjectid    = $email['subject'];
					$mailbodyid   = $email['mailbody'];
					$templateName = $this->L10nHandler->get('admin.templates.'.$tAction);

					$temp = array();
					$temp['language']  = $tLanguage;
					$temp['subjectid'] = $subjectid;
					$temp['mailbodyid'] = $mailbodyid;
					$temp['template'] = $templateName;

					$templates[] = $temp;
//					eval("\$templates.=\"".getTemplate("templates/templates_template")."\";");
				}
			}

			$add = false;
			$languages = $this->L10nHandler->getList();
			while(list($language_file, $language_name) = each($languages))
			{
				$templates_done=array();
				$result=$this->DatabaseHandler->query('SELECT `varname` FROM `'.TABLE_PANEL_TEMPLATES.'` WHERE `adminid`=\''.$this->User['adminid'].'\' AND `language`=\''.$language_name.'\' AND `templategroup`=\'mails\' AND `varname` LIKE \'%_subject\'');
				while(($row=$this->DatabaseHandler->fetch_array($result))!=false)
				{
					$templates_done[]=str_replace('_subject','',$row['varname']);
				}
				if(count(array_diff($available_templates,$templates_done))>0)
				{
					$add = true;
				}
			}

			$this->TemplateHandler->set('templates', $templates);
			$this->TemplateHandler->set('add', $add);
			$this->TemplateHandler->setTemplate('SysCP/templates/admin/list.tpl');
