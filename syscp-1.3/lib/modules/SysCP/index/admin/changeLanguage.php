<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Florian Lippert <flo@redenswert.de>
 * @copyright  (c) 2003-2006 Florian Lippert
 * @package    Syscp.Modules
 * @subpackage Index
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_index.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

if(isset($_POST['send']) && $_POST['send']=='send')
{
	$def_language = addslashes ( htmlentities ( _html_entity_decode ( $_POST['def_language'] ) ) ) ;
	if( $this->L10nHandler->hasLanguage( $def_language ) )
	{
		$this->DatabaseHandler->query("UPDATE `".TABLE_PANEL_ADMINS."` SET `def_language`='".$def_language."' WHERE `adminid`='".$this->User['adminid']."'");
		$_SESSION['language'] = $def_language;
	}
	$this->redirectTo(array('module'=>'index',
	                        'action'=>'index'));
}
else
{
	// Change language view
	$this->TemplateHandler->set('lang_list', $this->L10nHandler->getList());
	$this->TemplateHandler->setTemplate('SysCP/index/admin/change_language.tpl');
}