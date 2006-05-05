<?php
/**
 * This file is part of the SysCP project. 
 * Copyright (c) 2003-2006 the SysCP Project. 
 * 
 * For the full copyright and license information, please view the COPYING 
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 * 
 * @copyright  (c) the authors
 * @package    Org.Syscp.Core
 * @subpackage Language
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version    $Id: tables.inc.php 424 2006-03-18 08:46:23Z martin $
 */

/**
 * a Language Model for the language management within SysCP
 *
 * @package    Org.Syscp.Core
 * @subpackage Language
 *
 * @author     Martin Burchert <martin.burchert@syscp.org>
 * @version    1.0
 */
class Syscp_Language 
{
	/**
	 * Stores the language data of the selected language
	 *
	 * @var    array
	 * @access private
	 */
	var $_data;

	/**
	 * Stores the language data of the default language
	 *
	 * @var    array
	 * @access private
	 */
	var $_defaultData;

	/**
	 * stores currently set language
	 *
	 * @var    string
	 * @access private
	 */
	var $_lang;

	/**
	 * stores currently set default language
	 *
	 * @var    string
	 * @access private
	 */
	var $_defaultLang;

	/**
	 * cache for language list
	 *
	 * @var array
	 * @access private
	 */
	var $_langList;

	/**
	 * Constructor
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @return  Org_Syscp_Core_Language
	 */
	function __construct( $db, $defaultLanguage )
	{
		$this->_data        = null;
		$this->_defaultData = null;
		$this->_defaultLang = $defaultLanguage;
		$this->_lang        = null;
		$this->_langList    = null;
		
		$this->_buildList( $db );
	}
	
	/**
	 * loads the language data from the language files
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   Syscp_DB_Mysql  $db  A database connection object
	 * 
	 * @return  boolean  false if an error occured, otherwise true
	 */
	function load( $db = null )
	{
		// check for a given database connection
		if ( is_null( $db ) )
		{
			return false;
		}
		else
		{
			// build the language list
			$this->_buildList( $db );
			
			// load the default language files into $lng
			$lng = array();
			foreach( $this->_langList[$this->_defaultLang] as $file )
			{
				include makeSecurePath( SYSCP_PATH_LIB.$file );
			}
			// store the values of the default language
			$this->_defaultData = $lng;
			
			// load the selected language file into $lng
			$lng = array();
			foreach( $this->_langList[$this->_lang] as $file )
			{
				include makeSecurePath( SYSCP_PATH_LIB.$file );
			}
			// store the values of the selected language
			$this->_data = $lng;
			
			// exit and return success
			return true;
		}
	}
	
	/**
	 * returns the string of the requested language value
	 * 
	 * Please delimit array keys by '.', which means if you 
	 * request the value of $lng['panel']['edit'], you actually
	 * do this by using $language->get('panel/edit'); 
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $value  the string you want to have translated
	 * 
	 * @return  string  the resulting translated string or an ERROR string
	 */
	function get( $value )
	{
		$error  = 'ERROR :: String "'.$value.'" not found!';

		// try to find a selected language value
		$result = $this->_getFromData( $value, $this->_data );
		
		// if we don't get a result, we will have to look into the
		// default language data
		if ( $result === false ) 
		{
			$result = $this->_getFromData( $value, $this->_defaultData );
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
	
	/**
	 * returns if the language object has the requested string stored
	 * 
	 * Please delimit array keys by '.', which means if you 
	 * request the value of $lng['panel']['edit'], you actually
	 * do this by using $language->exists('panel/edit'); 
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $value  the string you want to have translated
	 * 
	 * @return  boolean if there is a string or not
	 */
	function exists( $value )
	{
		// try to find a selected language value
		$result = $this->_getFromData( $value, $this->_data );
		
		// if we don't get a result, we will have to look into the
		// default language data
		if ( $result === false ) 
		{
			$result = $this->_getFromData( $value, $this->_defaultData );
		}
		
		// if we still get no value, we return the error, otherwise
		// we return the value we got
		if ( $result === false )
		{
			return false;
		}
		else 
		{
			return true;
		}
	}

	/**
	 * returns a list of all installed languages
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @access  public
	 * @since   1.0
	 *
	 * @return  array  an array in the form of 
	 *                 array( lang_name => lang_name ); 
	 */
	function getList()
	{
		// init vars
		$return = array();
		
		// iterate the internal langlist and extract all
		// languages
		foreach( array_keys( $this->_langList) as $key )
		{
			$key = html_entity_decode($key);
			$return[ $key ] = $key;
		}
		
		// return the list
		return $return;
	}
	
	/**
	 * converts the stored language strings to an old style array
	 * 
	 * This method is mainly used for old compatibility and will 
	 * most likely be removed in near future. 
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @access  public
	 * @since   1.0
	 * @deprecated
	 *
	 * @return  array  an array in same style it's stored in the lng files
	 */
	function toArray()
	{
		$return = array();

		// here we should use any kind of merge algorithm, like array_merge,
		// but i don't know if it's implemented as of woody's php
		$return = $this->_defaultData;
		$return = $this->_data;
		
		return $return;		
	}
	
	/**
	 * returns the translated string from the given data array
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  private
	 *
	 * @param   string  $string   the string index to be returned
	 * @param   array   $arrData  the data array to be used
	 * 
	 * @return  mixed   boolean in the case of no found string, or ... 
	 *                  string, if the requested string is found
	 */
	function _getFromData( $string, $arrData )
	{
		// lets split the requested string into an array
		$string = split('.', $string);
		
		// and now lets search the array for the specified element
		foreach( $string as $element )
		{
			if ( isset( $arrData[$element] ) )
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
		if ( is_array( $arrData ) )
		{
			return false;
		}
		else 
		{
			return $arrData;
		}
	}
	
	/**
	 * builds the list of installed languages and their files
	 * 
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  private
	 *
	 * @param   db  $db  an instanciated database object
	 * 
	 * @return  boolean  false, if there is an error, otherwise true
	 */
	function _buildList( $db = null ) 
	{
		if ( is_null( $db ) )
		{
			return false;
		}
		else 
		{
			// query the whole table
			$query =
				'SELECT * ' .
				'FROM `'.TABLE_PANEL_LANGUAGE.'` ';
			$result = $db->query($query);
			
			// fetch results from database and store them in the 
			// internal languagelist array
			while ($row = $db->fetch_array($result))
			{
				$this->_langList[$row['language']][] = $row['file'];
			} 
			
			// return succeed message
			return true;
		}
	}
	
	/**
	 * Returns if the requested language exists
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $language  Name of the language to be checked
	 * 
	 * @return  boolean  If the language exists or not. 
	 */
	function hasLanguage( $language )
	{
		if( isset( $this->_langList[$language]))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * Sets the current default language
	 *
	 * @author  Martin Burchert <eremit@syscp.org>
	 * 
	 * @since   1.0
	 * @access  public
	 * 
	 * @param   string  $language  Name of the language to be used as default
	 * 
	 * @return  void
	 */
	function setLanguage( $language )
	{
		$this->_lang = $language;
	}
}

?>
