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
 * @author Florian Lippert <flo@redenswert.de>
 * @copyright (C) 2003-2004 Florian Lippert
 * @package Functions
 * @version $Id$
 */

	/**
	 * Class to manage the connection to the Database
	 * @package Functions
	 */
	class db
	{
		/**
		 * Link ID for every connection
		 * @var int
		 */
		var $link_id = 0;
		/**
		 * Query ID for every query
		 * @var int
		 */
		var $query_id = 0;
		/**
		 * Errordescription, if an error occures
		 * @var string
		 */
		var $errdesc = '';
		/**
		 * Errornumber, if an error occures
		 * @var int
		 */
		var $errno = 0;
		/**
		 * Servername
		 * @var string
		 */
		var $server = '';
		/**
		 * Username
		 * @var string
		 */
		var $user = '';
		/**
		 * Password
		 * @var string
		 */
		var $password = '';
		/**
		 * Database
		 * @var string
		 */
		var $database = '';

		/**
		 * Class constructor. Connects to Databaseserver and selects Database
		 *
		 * @param string Servername
		 * @param string Username
		 * @param string Password
		 * @param string Database
		 */
		function db($server,$user,$password,$database)
		{
			$this->server=$server;
			$this->user=$user;
			$this->password=$password;
			$this->database=$database;
			$this->link_id=@mysql_connect($this->server,$this->user,$this->password);

			if(!$this->link_id)
			{
				$this->showerror('Establishing connection failed, exiting');
			}

			if($this->database != '')
			{
				if(!@mysql_select_db($this->database, $this->link_id))
				{
					$this->showerror('Trying to use database '.$this->database.' failed, exiting');
				}
			}
		}

		/**
		 * Closes connection to Databaseserver
		 */
		function close()
		{
			return @mysql_close($this->link_id);
		}

		/**
		 * Query the Database
		 *
		 * @param string Querystring
		 * @param bool Unbuffered query?
		 * @return string RessourceId
		 */
		function query($query_str,$unbuffered=false)
		{
			global $numbqueries;
			if(!$unbuffered)
			{
				$this->query_id=mysql_query($query_str,$this->link_id);
			}
			else
			{
				$this->query_id=mysql_unbuffered_query($query_str,$this->link_id);
			}

			if(!$this->query_id)
			{
				$this->showerror('Invalid SQL: '.$query_str);
			}
			$numbqueries++;
			//echo $query_str.' '.$numbqueries.'<br />';
			return $this->query_id;
		}

		/**
		 * Fetches Row from Query and returns it as array
		 *
		 * @param string RessourceId
		 * @param string Datatype, num or assoc
		 * @return array The row
		 */
		function fetch_array($query_id=-1,$datatype='assoc')
		{
			if($query_id!=-1)
			{
				$this->query_id=$query_id;
			}

			if($datatype=='num')
			{
				$datatype=MYSQL_NUM;
			}
			else
			{
				$datatype=MYSQL_ASSOC;
			}
			$this->record=mysql_fetch_array($this->query_id,$datatype);
			return $this->record;
		}

		/**
		 * Query Database and fetche the first row from Query and returns it as array
		 *
		 * @param string Querystring
		 * @param string Datatype, num or assoc
		 * @return array The first row
		 */
		function query_first($query_string, $datatype='assoc')
		{
			$this->query($query_string);
			return $this->fetch_array($this->query_id,$datatype);
		}

		/**
		 * Returns how many rows have been selected
		 *
		 * @param string RessourceId
		 * @return int Number of rows
		 */
		function num_rows($query_id=-1)
		{
			if($query_id!=-1)
			{
				$this->query_id=$query_id;
			}
			return mysql_num_rows($this->query_id);
		}

		/**
		 * Returns the auto_incremental-Value of the inserted row
		 *
		 * @return int auto_incremental-Value
		 */
		function insert_id()
		{
			return mysql_insert_id($this->link_id);
		}

		/**
		 * Returns errordescription and errornumber if an error occured.
		 *
		 * @return int Errornumber
		 */
		function geterrdescno()
		{
			$this->errdesc=mysql_error();
			$this->errno=mysql_errno();
			return $this->errno;
		}

		/**
		 * Dies with an errormessage
		 *
		 * @param string Errormessage
		 */
		function showerror($errormsg)
		{
			global $filename;
			$this->geterrdescno();
			$errormsg.='<br />';
			$errormsg.='mysql error number: '.$this->errno.' <br />';
			$errormsg.='mysql error desc: '.$this->errdesc.' <br />';
			$errormsg.='Script: '.getenv('REQUEST_URI').' <br />';
			$errormsg.='Referer: '.getenv('HTTP_REFERER').' <br />';
			$errormsg.='Time/date: '.date('d/m/Y h:i A').' <br>';
			if($filename != 'cronscript.php')
			{
				die($errormsg);
			}
		}
	}

?>
