<?php
			$result=$this->DatabaseHandler->query("SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->User['customerid']."' ORDER BY `username` ASC");
			$accounts='';
			$rows = $this->DatabaseHandler->num_rows($result);
			if ($this->ConfigHandler->get('panel.paging') > 0)
			{
				$pages = intval($rows / $this->ConfigHandler->get('panel.paging'));
			}
			else
			{
				$pages = 0;
			}
			if ($pages != 0)
			{
				if(isset($_GET['no']))
				{
					$pageno = intval($_GET['no']);
				}
				else
				{
					$pageno = 1;
				}
				if ($pageno > $pages)
				{
					$pageno = $pages + 1;
				}
				elseif ($pageno < 1)
				{
					$pageno = 1;
				}
				$pagestart = ($pageno - 1) * $this->ConfigHandler->get('panel.paging');
				$result=$this->DatabaseHandler->query(
					"SELECT `id`, `username`, `homedir` FROM `".TABLE_FTP_USERS."` WHERE `customerid`='".$this->User['customerid']."' ORDER BY `username` ASC " .
					" LIMIT $pagestart , ".$this->ConfigHandler->get('panel.paging').";"
				);
				$paging = '';
				for ($count = 1; $count <= $pages+1; $count++)
				{
					if ($count == $pageno)
					{
						$paging .= "<a href=\"".$this->ConfigHandler->get('env.filename')."?s=".$this->ConfigHandler->get('env.s')."&page=".$this->ConfigHandler->get('env.page')."&no=$count\"><b>$count</b></a>&nbsp;";
					}
					else
					{
						$paging .= "<a href=\"".$this->ConfigHandler->get('env.filename')."?s=".$this->ConfigHandler->get('env.s')."&page=".$this->ConfigHandler->get('env.page')."&no=$count\">$count</a>&nbsp;";
					}
				}
			}
			else
			{
				$paging = "";
			}
			$accounts = array();
			while($row=$this->DatabaseHandler->fetch_array($result))
			{
				$row['documentroot']=str_replace($this->User['homedir'],'',$row['homedir']);
				$accounts[] = $row;
			}
			$ftps_count = $this->DatabaseHandler->num_rows($result);

			$this->TemplateHandler->set('accounts', $accounts);
			$this->TemplateHandler->set('paging', $paging);
			$this->TemplateHandler->set('pages', $pages);
			$this->TemplateHandler->setTemplate('SysCP/ftp/customer/list.tpl');