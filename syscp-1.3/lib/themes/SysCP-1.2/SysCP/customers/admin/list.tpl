<table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
	<tr>
		<td colspan="20" class="title">{l10n get=SysCP.customers.customers}</td>
	</tr>
	{if ($User.customers_used < $User.customers || $User.customers == '-1') && 15 < $User.customers_used}
		<tr>
     		<td colspan="20" class="maintable">
     			<a href="{url action=add module=customers}">{l10n get=SysCP.customers.customer_add}</a>
     		</td>
    	</tr>
    {/if}
    {if 0 < $pages}
    	<tr>
    		<td colspan="20" class="maintable">{$paging}</td>
    	</tr>
    {/if}
	<tr>
		<td class="maintable">
			{l10n get=SysCP.globallang.id}&nbsp;&nbsp;<a href="{url action=list sortby=loginname sortorder=desc module=customers}"><img src="themes/default/order_desc.gif" border="0"/></a>
			<a href="{url action=list module=customers sortby=loginname sortorder=asc}"><img src="themes/default/order_asc.gif" border="0"/></a>
		</td>
		{if $User.customers_see_all}
			<td class="maintable">
				{l10n get=SysCP.customers.admin}&nbsp;&nbsp;
				<a href="{url action=list module=customers sortby=adminid sortorder=desc}"><img src="themes/default/order_desc.gif" border="0"/></a>
				<a href="{url action=list module=customers sortby=adminid sortorder=asc}"><img src="themes/default/order_asc.gif" border="0"/></a>
			</td>
		{/if}

		<td class="maintable">{l10n get=SysCP.globallang.name}</td>
		<td class="maintable">{l10n get=SysCP.customers.domains}</td>
		<td class="maintable">{l10n get=SysCP.customers.space}<br />{l10n get=SysCP.customers.traffic}</td>
		<td class="maintable">{l10n get=SysCP.customers.mysql}<br />{l10n get=SysCP.customers.ftp}</td>
		<td class="maintable">{l10n get=SysCP.customers.emails}<br />{l10n get=SysCP.customers.subdomains}</td>
		<td class="maintable">{l10n get=SysCP.customers.email_accounts}<br />{l10n get=SysCP.customers.email_forwarders}</td>
		<td class="maintable">{l10n get=SysCP.customers.active}&nbsp;&nbsp;<a href="{url action=list module=customers sortby=deactivated sortorder=desc}"><img src="themes/default/order_desc.gif" border="0"/></a>
		<a href="{url action=list module=customers sortby=deactivated sortorder=asc}"><img src="themes/default/order_asc.gif" border="0"/></a></td>
		<td class="maintable">&nbsp;</td>
	</tr>

	{foreach from=$customer_list item=row}
		<tr>
			<td class="maintable">
				<a href="{url module=customers action=su id=$row.customerid}" target="_blank">{$row.loginname}</a>
			</td>
			{if $User.customers_see_all}
				<td class="maintable">{$row.adminname}</td>
			{/if}
			<td class="maintable">{$row.name}<br/>{$row.firstname}</td>
			<td class="maintable">{$row.domains}</td>
			<td class="maintable">
				{if $row.diskspace < $row.diskspace_used && $row.diskspace != 'UL'}
					{assign var=color value='style="color:red"'}
				{else}
					{assign var=color value=" "}
				{/if}
				<span {if $row.diskspace < $row.diskspace_used && $row.diskspace != 'UL'} style="color:red;"{/if}>
					{$row.diskspace_used}/{$row.diskspace}
				</span> ({l10n get=SysCP.globallang.mb})<br />
				<span {if $row.traffic < $row.traffic_used && $row.traffic != 'UL'} style="color:red;"{/if}>
					{$row.traffic_used}/{$row.traffic}</span> ({l10n get=SysCP.globallang.gb})
			</td>
			<td class="maintable">
				{$row.mysqls_used}/{$row.mysqls}<br />
				{$row.ftps_used}/{$row.ftps}
			</td>
			<td class="maintable">
				{$row.emails_used}/{$row.emails}<br />
				{$row.subdomains_used}/{$row.subdomains}
			</td>
			<td class="maintable">
				{$row.email_accounts_used}/{$row.email_accounts}<br />
				{$row.email_forwarders_used}/{$row.email_forwarders}
			</td>
			<td class="maintable">{$row.deactivated}</td>
			<td class="maintable">
				<a href="{url module=customers action=delete id=$row.customerid}">{l10n get=SysCP.globallang.delete}</a><br />
				<a href="{url module=customers action=edit   id=$row.customerid}">{l10n get=SysCP.globallang.edit}</a>
			</td>
		</tr>
    {/foreach}

    {if 0 < $pages}
    	<tr>
			<td colspan="20" class="maintable">{$paging}</td>
		</tr>
	{/if}
	{if $User.customers_used < $User.customers || $User.customers == '-1'}
		<tr>
     		<td colspan="20" class="maintable">
     			<a href="{url module=customers action=add}">{l10n get=SysCP.customers.customer_add}</a>
     		</td>
		</tr>
	{/if}
</table>
