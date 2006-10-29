<?php

/**
 * This file is part of the SysCP project.
 * Copyright (c) 2003-2006 the SysCP Project.
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.syscp.org/misc/COPYING.txt
 *
 * @author     Luca Longinotti <chtekk@syscp.org>
 * @copyright  (c) 2006, the authors
 * @package    Syscp.Framework
 * @subpackage Syscp.Handler.XmlRpc
 * @license    GPLv2 http://files.syscp.org/misc/COPYING.txt
 * @version
 */

class Syscp_Handler_XmlRpc implements Syscp_Handler_XmlRpc_Interface
{
    private $crypt = null;
    private $host = null;
    private $port = null;
    private $encoding = 'iso-8859-1';
    private $verbosity = 'pretty';
    private $currxml = null;
    const ERROR_MISSING_PARAM = 'You need to specify the %s parameter.';
    const ERROR_MISSING_EXTENSION = 'You need to install the PHP XmlRpc extension!';
    const ERROR_CONNECTION_FAILED = 'Connecting to %s failed with error "%s (%s)"';

    public function initialize($params = array())
    {
        if(isset($params['url']))
        {
            $url = $params['url'];
            $url = parse_url($url);

            if($url['scheme'] == '')
            {
                $this->crypt = '';
            }
            else
            {
                $this->crypt = $url['scheme'].'://';
            }

            $this->host = $url['host'];
            $this->port = $url['port'];
        }
        else
        {
            $error = sprintf(self::ERROR_MISSING_PARAM, 'url');
            throw new Syscp_Handler_XmlRpc_Exception($error);
        }

        if(!extension_loaded('xmlrpc'))
        {
            throw new Syscp_Handler_XmlRpc_Exception(self::ERROR_MISSING_EXTENSION);
        }
    }

    public function setEncoding($enc = 'iso-8859-1')
    {
        $this->encoding = $enc;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setVerbosity($verb = 'pretty')
    {
        $this->verbosity = $verb;
    }

    public function getVerbosity()
    {
        return $this->verbosity;
    }

    public function doRequest($path = '', $method = '', $methodparams = '')
    {
        $conn = fsockopen($this->crypt.$this->host, $this->port, $errno, $errstr, 10);

        if(!$conn)
        {
            $error = sprintf(self::ERROR_CONNECTION_FAILED, $this->crypt.$this->host.':'.$this->port, $errstr, $errno);
            throw new Syscp_Handler_XmlRpc_Exception($error);
        }

        $options = array(
            'encoding' => $this->encoding,
            'verbosity' => $this->verbosity
        );
        $requestxml = xmlrpc_encode_request($method, $methodparams, $options);
        $request = "POST $path HTTP/1.0\r\n"."User-Agent: Syscp_Handler_XmlRpc\r\n"."Host: $this->host:$this->port\r\n"."Content-Type: text/xml\r\n"."Content-Length: ".strlen($requestxml)."\r\n\r\n".$requestxml;
        fwrite($conn, $request);
        $response = '';

        while(!feof($conn))
        {
            $response.= fread($conn, 4096);
        }

        fclose($conn);
        $this->currxml = substr($response, strpos($response, '<?xml'));
    }

    public function getRawData()
    {
        return $this->currxml;
    }

    public function getDecodedData()
    {
        return xmlrpc_decode($this->currxml);
    }
}

