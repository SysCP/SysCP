<?php
/**
 * filename: $Source$
 * begin: Friday, Aug 06, 2004
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version. This program is distributed in the
 * hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * @author Michael Dürgner <michael@duergner.com>
 * @copyright (C) 2004 Michael Dürgner
 * @package Functions
 * @version $Id$
 */
 
/**
 * Inlcude the idna convert class we use.
 */
require('./lib/idna_convert.class.php');

	/**
	 * Class for wrapping a specific idna conversion class and offering a standard interface
	 * @package Functions
	 */
	class idna_convert_wrapper
	{
		/**
		 * idna converter we use
		 * @var object
		 */
		var $idna_converter;

		/**
		 * Class constructor. Creates a new idna converter
		 */
		function idna_convert_wrapper()
		{
			$this->idna_converter = new idna_convert();
		}
		
		/**
		 * Encode a domain name, a email address or a list of one of both.
		 * 
		 * @param string May be either a single domain name, e single email address or a list of one 
		 * seperated either by ',', ';' or ' '.
		 * 
		 * @return string Returns either a single domain name, a single email address or a list of one of 
		 * both seperated by the same string as the input.
		 */
		function encode($to_encode)
		{
			return $this->_do_action('encode',$to_encode);
		}
		
		/**
		 * Decode a domain name, a email address or a list of one of both.
		 * 
		 * @param string May be either a single domain name, e single email address or a list of one 
		 * seperated either by ',', ';' or ' '.
		 * 
		 * @return string Returns either a single domain name, a single email address or a list of one of 
		 * both seperated by the same string as the input.
		 */
		function decode($to_decode)
		{
			return $this->_do_action('decode',$to_decode);
		}
		
		/**
		 * Do the real de- or encoding. First checks if a list is submitted and seperates it. Afterwards sends 
		 * each entry to the idna converter to do the converting.
		 * 
		 * @param string May be either 'decode' or 'encode'.
		 * @param string The string to de- or endcode.
		 * 
		 * @return string The input string after being processed. 
		 */
		function _do_action($action, $string)
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
				$domain = utf8_decode($this->idna_converter->$action(utf8_encode($domain.'.none')));
				$domain = substr($domain, 0, strlen($domain)-5);
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
?>
