<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
  <tr>
    <td colspan="4" class="title">{l10n get=SysCP.domains.domainsettings}</td>
  </tr>
  <tr>
    <td class="maintable">{l10n get=SysCP.domains.domainname}</td>
    <td class="maintable">{l10n get=SysCP.globallang.path}</td>
    <td class="maintable" colspan="2">&nbsp;</td>
  </tr>
  {if ($User.subdomains_used < $User.subdomains || $User.subdomains == -1) && $parentDomainCount != 0}
  <tr>
    <td class="maintable" colspan="4">
      <a href="{url module=domains action=add}">{l10n get=SysCP.domains.subdomain_add}</a>
    </td>
  </tr>
  {/if}
  {foreach from=$domainList item=parentdomain key=parentdomainname}
    <tr>
      <td class="maintable" colspan="4"><b>&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&nbsp;{$parentdomainname}</b></td>
    </tr>
    {foreach from=$parentdomain item=domain key=domainname}
      <tr>
        <td class="maintable">{$domain.domain}</td>
        {if $domain.aliasdomain != ''}
          <td class="maintable">{l10n get=SysCP.domains.aliasdomain} {$domain.aliasdomain}</td>
        {else}
          <td class="maintable">{$domain.documentroot}</td>
        {/if}
        <td class="maintable">
          {if $domain.caneditdomain == 1}
            <a href="{url module=domains action=edit id=$domain.id}">{l10n get=SysCP.globallang.edit}</a>
          {/if}
        <td class="maintable">
         {if $domain.parentdomainid != 0 && !$domain.hasAliasdomains}
            <a href="{url module=domains action=delete id=$domain.id}">{l10n get=SysCP.globallang.delete}</a>
          {/if}
        </td>
      </tr>
    {/foreach}
  {/foreach}
  {if ($User.subdomains_used < $User.subdomains || $User.subdomains == -1) && $parentDomainCount != 0}
  <tr>
    <td class="maintable" colspan="4">
      <a href="{url module=domains action=add}">{l10n get=SysCP.domains.subdomain_add}</a>
    </td>
  </tr>
  {/if}
</table>
