<?php
	if ($this->User['change_serversettings'] == '1')
	{
		if ( isset($_POST['send']) && $_POST['send'] == 'send' )
		{
			// init the dirstack
			$dirstack = array();
			// push the first element to the dirstack
			array_push($dirstack, SYSCP_PATH_BASE.'cache/');
			// iterate the dirstack while there are values in it
			while(sizeof($dirstack) > 0)
			{
				$dirname = array_pop($dirstack);
				$dir     = new DirectoryIterator($dirname);
				foreach($dir as $file)
				{
					// don't delete files starting with a .
					if (substr($file->getFilename(), 0, 1) != '.')
					{
						if($file->isDir())
						{
							array_push($dirstack, $file->getPathname());
						}
						else
						{
							unlink($file->getPathname());
						}
					}
				}
			}

			$this->redirectTo(array('module' => 'index',
			                        'action' => 'index'));
		}
		else
		{
			$this->TemplateHandler->showQuestion('admin_reallyclearcache',
			                                     array('module' => 'settings',
			                                           'id'     => $this->ConfigHandler->get('env.id'),
			                                           'action' => 'clearcache'));
		}

	}