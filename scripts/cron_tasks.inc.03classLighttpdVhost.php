<?php
class LighttpdVhost extends vHost
{
	public function getVhost()
	{
		$domains = '';
		foreach($this->wwwdomains as $domain)
		{
			$domains.= "|(^(www\.|){$domain}$)";
		}

		foreach($this->wildcarddomains as $domain)
		{
			$domains.= "|(.*\.{$domain}$)";
		}

		foreach($this->domains as $domain)
		{
			$domains.= "|(^{$domain}$)";
		}

		$domains = substr($domains, 1);
		$vhost = '$HTTP["host"] =~ "^' . $domains . '$" {' . "\n";
	}
}

