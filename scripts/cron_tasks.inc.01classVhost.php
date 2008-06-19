<?php

class Vhost
{
    private $domains = array();
    private $wwwdomains = array();
    private $wildcarddomains = array();
    private $ip;
    private $port;
    private $adminmail;
    private $documentroot;

    public function addDomain($domain, $wildcard = false, $withwww = true)
    {
        if($wildcard)
        {
            $this->wildcarddomains[] = $domain;
        }
        elseif($withwww)
        {
            $this->wwwdomains[] = $domain;
        }
        else
        {
            $this->domains[] = $domain;
        }
        return true;
    }

    public function setIpPort($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        return true;
    }

    public function setAdminmail($mail)
    {
        $this->adminmail = $mail;
        return true;
    }

    public function setDocumentroot($documentroot)
    {
        $this->documentroot = $documentroot;
        return true;
    }

    
}