	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="4" class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.domainsettings}
			</td>
		</tr>
		{if ($User.subdomains_used < $User.subdomains || $User.subdomains == -1) && $parentDomainCount != 0}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=domains action=add}">
					{l10n get=SysCP.domains.subdomain_add}
				</a>
			</td>
		</tr>
		{/if}
		<tr>
			<td class="field_display_border_left">{l10n get=SysCP.domains.domainname}</td>
			<td class="field_display">{l10n get=SysCP.globallang.path}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		{foreach from=$domainList item=parentdomain key=parentdomainname}
		<tr>
			<td class="title" colspan="4"><b>&raquo;&nbsp;{$parentdomainname}</b></td>
		</tr>
		{foreach from=$parentdomain item=domain key=domainname}
		<tr>
			<td class="field_name_border_left">{$domain.domain}</td>
			{if $domain.aliasdomain != ''}
			<td class="field_name">{l10n get=SysCP.domains.aliasdomain} {$domain.aliasdomain}</td>
			{else}
			<td class="field_name">{$domain.documentroot}</td>
			{/if}
			<td class="field_name">
				{if $domain.caneditdomain == 1}
				<a href="{url module=domains action=edit id=$domain.id}">{l10n get=SysCP.globallang.edit}</a>
				{/if}
			<td class="field_name">
				{if $domain.parentdomainid != 0 && !$domain.hasAliasdomains}
				<a href="{url module=domains action=delete id=$domain.id}">{l10n get=SysCP.globallang.delete}</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		{/foreach}
		{if ($User.subdomains_used < $User.subdomains || $User.subdomains == -1) && $parentDomainCount != 0}
		<tr>
			<td class="field_display_border_left" colspan="4">
				<a href="{url module=domains action=add}">{l10n get=SysCP.domains.subdomain_add}</a>
			</td>
		</tr>
		{/if}
	</table>
