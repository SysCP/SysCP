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
$this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_USER,
                        'User logged out successfully...' );
$this->LogHandler->info(Syscp_Handler_Log_Interface::FACILITY_AUTH,
                        sprintf('User %s logged out successfully...',
                                $this->User['loginname']));

session_destroy();
$this->redirectTo(array('module' => 'login',
                        'action' => 'login'));