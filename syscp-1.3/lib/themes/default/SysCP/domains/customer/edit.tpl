	<form method="post" action="{url module=domains action=edit}">
		<input type="hidden" name="id" value="{$Config->get('env.id')}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.subdomain_edit}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.domainname}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result.domain}</td>
			</tr>
			{if $alias_check == '0'}<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.aliasdomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="alias">
						{html_options options=$domains selected=$result.aliasdomain}
					</select>
				</td>
			</tr>{/if}
			{if $Config->get('panel.customerpathedit') == "Yes"}
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.globallang.path}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{$documentrootPrefix}{$pathSelect}
				</td>
			</tr>
			{/if}
			{if $result.parentdomainid == '0' && $User.subdomains != '0' }<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.wildcarddomain}</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name=iswildcarddomain options=$iswildcarddomain selected=$result.iswildcarddomain}
				</td>
			</tr>{/if}
			{if $result.subcanemaildomain == '1' && $result.parentdomainid != '0'}<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.emaildomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name=isemaildomain options=$isemaildomain selected=$result.isemaildomain}
				</td>
			</tr>{/if}
			<tr>
				<td class="main_field_confirm" colspan="2">
                    {if $Config->get('panel.customerpathedit') != "Yes"}
                        {$pathSelect}
                    {/if}
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>
