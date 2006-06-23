<?php
		if(isset($_POST['send']) && $_POST['send']=='send')
		{
			$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
			if($this->L10nHandler->hasLanguage($def_language))
			{
				$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_CUSTOMERS."` SET `def_language`='".$def_language."' WHERE `customerid`='".$this->User['customerid']."'");
				$_SESSION['language'] = $def_language;
			}
			$this->redirectTo(array('module'=>'index',
			                        'action'=>'index'));
		}
		else
		{
			$this->TemplateHandler->set('lang_list', $this->L10nHandler->getList());
			$this->TemplateHandler->setTemplate('SysCP/index/customer/change_language.tpl');
		}