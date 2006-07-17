# {$Config->get('system.apacheconf_directory')}{$Config->get('system.apacheconf_filename')}
# Created {$now}
# Do NOT manually edit this file, all changes will be deleted after the next domain change at the panel.

### NEVER EVER CHANGE THE FOLLOWING LINE or you will break the whole traffic accounting
LogFormat "%I\n%O" SyscpTrafficLog

{if $hasDiroptions}
Include {$Config->get('system.apacheconf_directory')}diroptions.conf
{/if}

{foreach from=$ipList item=item}
NameVirtualHost {$item}
{/foreach}

# DummyHost for DefaultSite
<VirtualHost {$Config->get('system.ipaddress')}:80>
	ServerName {$Config->get('system.hostname')}
</VirtualHost>

{foreach from=$domains item=domain}
# DomainID: {$domain.id} - CustomerID: {$domain.customerid} - CustomerLogin: {$domain.customer.loginname}
<VirtualHost {$domain.ipandport}>
	ServerName {$domain.domain}
	ServerAdmin {$domain.customer.email}
{foreach from=$domain.aliases item=alias}
	ServerAlias {$alias}
{/foreach}

{if $domain.redirectTo}
	Redirect / {$domain.redirectTo}
{else}
	DocumentRoot {$domain.documentroot}
  {if $domain.openbasedir == 1}
	php_admin_value open_basedir {$domain.documentroot}
  {/if}
	php_admin_flag safe_mode {if $domain.safemode == 1}On{else}Off{/if}

	ErrorLog "{$domain.error_logfile}"
	CustomLog "{$domain.access_logfile}" combined
{/if}
    {$specialsettings}

  	### NEVER EVER CHANGE THE FOLLOWING LINE
  	# or you will break the whole traffic accounting
	CustomLog "{$domain.trafficLog}" SyscpTrafficLog

	### DON'T REMOVE THIS PART EITHER
	# this line can be used by modules to find domains in the vhost file
	# to add custom entries into the vhost.
	### VHOST:{$domain.id}:{$domain.domain}:{$domain.customerid}:{$domain.customer.loginname}

</VirtualHost>

{/foreach}

{* !! COMMENT NOT VISIBLE IN RESULT

Some remarks regarding the datas parsed to this template:
$ipList : Contains a list of all ip:port pairs used in the domain list.
			array (id => 'ip:port');

$Config : the confighandler object like in the panel

$hasDiroptions : a boolean indicating if the diroptions.conf file has been found and must
                 be included in the header

$now : The current time.

$domains : An array holding ALL domain data of the table_panel_domains. Additionally some values
           has been added to this array, NOTHING has been removed!
           Added:
           $domains[id]['customer'] : Holding the row of the owning customer from panel_customer
           $domains[id]['parentdomain'] : The name of the parentdomain of this domain, or if this
                                          domain does not have a parentdomain the domainname of
                                          the domain itself.
           $domains[id]['redirectTo'] : false if the domain does not have a redirect, otherwise the
                                        target uri
           $domains[id]['aliases'] : a list of all aliases to this domain

END COMMENT !! *}