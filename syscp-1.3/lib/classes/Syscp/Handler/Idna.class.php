<?php
/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id:admin_admins.php 460 2006-04-23 15:07:49 +0200 (So, 23 Apr 2006) martin $
 */

// Include the PEAR Idna Converter class.
Syscp::uses('PEAR.Net.IDNA');

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */
class Syscp_Handler_Idna implements Syscp_Handler_Idna_Interface
{
	/**
	 * Instanciated object of the IDNA converter
	 *
	 * @var    Net_IDNA
	 */
	private $IdnaConverter;

	/**
	 * Class constructor. Creates a new idna converter
	 *
	 * @access public
	 *
	 * @return Org_Syscp_Core_IDNA
	 */
	public function initialize($params = array())
	{
		$this->IdnaConverter = Net_IDNA::getInstance();
//		$this->IdnaConverter = new idna_convert();
	}

	/**
	 * Encode a domain name, a email address or a list of one of both.
	 *
	 * @access public
	 *
	 * @param  string  $to_encode  May be either a single domain name,
	 *                             a single email address or a list of
	 *                             one seperated either by ',', ';' or
	 *                             ' '.
	 *
	 * @return string  Returns either a single domain name, a single
	 *                 email address or a list of one of both seperated
	 *                 by the same string as the input.
	 */
	public function encode($toEncode)
	{
		return $this->_doAction('encode',$toEncode);
	}

	/**
	 * Decode a domain name, a email address or a list of one of both.
	 *
	 * @access public
	 *
	 * @param  string  $to_decode  May be either a single domain name,
	 *                             a single email address or a list of
	 *                             one seperated either by ',', ';' or
	 *                             ' '.
	 *
	 * @return string  Returns either a single domain name, a single
	 *                 email address or a list of one of both seperated
	 *                 by the same string as the input.
	 */
	public function decode($toDecode)
	{
		return $this->_doAction('decode',$toDecode);
	}

	/**
	 * Do the real de- or encoding. First checks if a list is submitted and seperates it. Afterwards sends
	 * each entry to the idna converter to do the converting.
	 *
	 * @access private
	 *
	 * @param  string  May be either 'decode' or 'encode'.
	 * @param  string  The string to de- or endcode.
	 *
	 * @return string  The input string after being processed.
	 */
	private function _doAction($action, $string)
	{
		$string = trim($string);
		if(strpos($string,',') !== false)
		{
			$strings = explode(',',$string);
			$sepchar = ',';
		}
		elseif(strpos($string,';') !== false)
		{
			$strings = explode(';',$string);
			$sepchar = ';';
		}
		elseif(strpos($string,' ') !== false)
		{
			$strings = explode(' ',$string);
			$sepchar = ' ';
		}
		else
		{
			$strings = array($string);
			$sepchar = '';
		}
		for($i = 0; $i < count($strings); $i++)
		{
			if(strpos($strings[$i],'@') !== false)
			{
				$split = explode('@',$strings[$i]);
				$localpart = $split[0];
				$domain = $split[1];
				$email = true;
			}
			else
			{
				$domain = $strings[$i];
				$email = false;
			}
			if(strlen($domain) !== 0)
			{
				$domain = utf8_decode($this->IdnaConverter->$action(utf8_encode($domain.'.none')));
				$domain = substr($domain, 0, strlen($domain)-5);
			}
			if($email)
			{
				$strings[$i] = $localpart . '@' . $domain;
			}
			else
			{
				$strings[$i] = $domain;
			}
		}
		return implode($sepchar,$strings);
	}
}
