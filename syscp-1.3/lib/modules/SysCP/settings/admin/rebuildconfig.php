<?php
	if ($this->User['change_serversettings'] == '1')
	{
		if ( isset($_POST['send']) && $_POST['send'] == 'send' )
		{
			$this->HookHandler->call( 'OnUpdateDomain',
			              array( 'id' => '*' ) );
			$this->redirectTo(array('module' => 'index',
			                        'action' => 'index'));
		}
		else
		{
			$this->TemplateHandler->showQuestion('admin_configs_reallyrebuild',
			                                     array('module' => 'settings',
			                                           'id'     => $this->ConfigHandler->get('env.id'),
			                                           'action' => 'rebuildconfig'));
		}

	}