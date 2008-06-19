<?php

class ApacheVhost extends vHost
{
    public function getVhost()
    {
        $vhost = "<VirtualHost $this->ip:$this->port>\n";
        /**
         * Merge all domains to one array,
         * all types can be handleded the same in apache
         */
        $domains = array();
        foreach($this->wwwdomains as $domain)
        {
            $domains[] = $domain;
            $domains[] = "www.{$domain}";
        }
        foreach($this->domains as $domain)
        {
            $domains[] = $domain;
        }
        foreach($this->wildcarddomains as $domain)
        {
            $domains[] = "*.{$domain}";
        }
        /**
         * Create the ServerNames
         */
        if(count($domains) > 0)
        {
            $vhost .= "\tServerName {$domains[0]}\n";
            if(count($domains) > 1)
            {
                foreach($domains as $domain)
                {
                    $vhost .= "\tServerAlias $domain\n";
                }
            }
        }
        else
        {
            return '';
        }

        // Add an admin if the mail is set
        if($this->adminmail != '')
        {
            $vhost .= "\tServerAdmin {}$this->adminmail}\n";
        }

        // Set the docroot or make an redirect
        if(preg_match('/^https?\:\/\//', $this->documentroot))
        {
            $vhost .= "\tRedirect / {$this->documentroot}\n";
        }
        else
        {
            $vhost .= "\tDocumentRoot \"{$this->documentroot}\"\n";
        }
    }
}
