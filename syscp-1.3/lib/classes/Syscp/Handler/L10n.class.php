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

/**
 * @todo Extended documentation here!
 *
 * @author     Martin Burchert <eremit@syscp.org>
 * @copyright  (c) 2006, The Authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler
 */
class Syscp_Handler_L10n implements Syscp_Handler_L10n_Interface
{
	private $DatabaseHandler  = null;
	private $defaultLanguage  = '';
	private $selectedLanguage = '';
	/**
	 * The list of languages and their files from the frontcontroller,
	 * within this implementation this attribute equals $langList
	 *
	 * @var array
	 */
	private $languageFilelist = array();

	private $langData    = array();
	private $defaultData = array();
	private $langList    = array();

	const ERROR_MISSING_PARAM  = 'You need to specify the %s parameter.';
	const ERROR_PARAM_TYPE     = 'The param %s needs to be an instance of %s.';
	const ERROR_STRING_MISSING = 'ERROR :: String "%s" not found!';

	public function initialize($params = array())
	{
		// we don't want to repeat ourselves
		$required = array('DatabaseHandler'  => 'Syscp_Handler_Database_Interface',
		                  'defaultLanguage'  => '',
		                  'languageFilelist' => '' );

		foreach($required as $index => $instanceOf)
		{
			if(isset($params[$index]))
			{
				if($instanceOf == '' || $params[$index] instanceof $instanceOf)
				{
					$this->$index = $params[$index];
				}
				else
				{
					$error = sprintf(self::ERROR_PARAM_TYPE, $index, $instanceOf);
					throw new Syscp_Handler_L10n_Exception($error);
				}
			}
			else
			{
				$error = sprintf(self::ERROR_MISSING_PARAM, $index);
				throw new Syscp_Handler_L10n_Exception($error);
			}
		}

		if(isset($params['SelectedLanguage']))
		{
			$this->selectedLanguage = $params['SelectedLanguage'];
		}
		// build the list of languages
		$this->buildList();
		$this->loadLanguages();
	}

	public function hasLanguage($language)
	{
		if( isset( $this->langList[$language]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function getLanguageList()
	{
		// init vars
		$return = array();

		// iterate the internal langlist and extract all
		// languages
		foreach(array_keys($this->langList) as $key )
		{
			$key = html_entity_decode($key);
			$return[$key] = $key;
		}
		// return the list
		return $return;
	}


	public function get($value)
	{
		$error  = sprintf(self::ERROR_STRING_MISSING, $value);

		// try to find a selected language value
		$result = $this->getFromData( $value, $this->langData );

		// if we don't get a result, we will have to look into the
		// default language data
		if ($result === false)
		{
			$result = $this->getFromData($value, $this->defaultData);
		}

		// if we still get no value, we return the error, otherwise
		// we return the value we got
		if ( $result === false )
		{
			return $error;
		}
		else
		{
			return $result;
		}
	}

	public function exists($value)
	{
		// try to find a selected language value
		$result = $this->getFromData($value, $this->langData);

		// if we don't get a result, we will have to look into the
		// default language data
		if ( $result === false )
		{
			$result = $this->getFromData($value, $this->defaultData);
		}

		// if we still get no value, we return the error, otherwise
		// we return the value we got
		if ($result === false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	public function setLanguage($language)
	{
		$this->selectedLanguage = $language;
		$this->loadLanguages();
	}

	private	function buildList()
	{
		$this->langList = $this->languageFilelist;
/*		// query the whole table
		$query =
			'SELECT * ' .
			'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
		$result = $this->DatabaseHandler->query($query);
		// fetch results from database and store them in the
		// internal languagelist array
		while($row = $this->DatabaseHandler->fetchArray($result))
		{
			$this->langList[$row['language']][] = $row['file'];
		}
		// return succeed message
		return true;
*/
	}
	private function getFromData($string, $arrData)
	{
		// lets split the requested string into an array
		$string = split('\.', $string);

		// and now lets search the array for the specified element
		foreach($string as $element)
		{
			if (isset($arrData[$element]))
			{
				// we have found the subArray key, lets proceed within
				// the subarray
				$arrData = $arrData[$element];
			}
			else
			{
				// we haven't found the subArray key, lets clean the
				// array
				$arrData = array();
			}
		}

		// and finally check if the result we have by now is an array
		if (is_array($arrData))
		{
			return false;
		}
		else
		{
			return $arrData;
		}
	}
	private function loadLanguages()
	{
		// load the default language files into $lng
		$lng = array();
		foreach($this->langList[$this->defaultLanguage] as $file )
		{
			include SYSCP_PATH_BASE.$file;
		}

		// store the values of the default language
		$this->defaultData = $lng;

		// if there is a selected language, then
		// load the selected language file into $lng
		if ($this->selectedLanguage != '')
		{
			$lng = array();
			foreach( $this->langList[$this->selectedLanguage] as $file )
			{
				include SYSCP_PATH_BASE.$file;
			}

			// store the values of the selected language
			$this->langData = $lng;
		}
		// exit and return success
		return true;
	}
	/**
	 * @deprecated
	 */
	public function getList()
	{
		return $this->getLanguageList();
	}


}