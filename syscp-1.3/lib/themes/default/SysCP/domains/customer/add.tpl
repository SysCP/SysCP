	<form method="post" action="{url module=domains action=add}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.subdomain_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.domainname}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="subdomain" value="" size="15" maxlength="50" /> <b>.</b>
					<select class="dropdown_noborder" name="domain">
						{html_options options=$domains}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.aliasdomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="alias">
						{html_options options=$aliasdomains}
					</select>
				</td>
			</tr>
			{if $Config->get('panel.customerpathedit') == "Yes"}
			<tr>
				<td class="main_field_name">{l10n get=SysCP.globallang.path}:</td>
				<td class="main_field_display">
					{$documentrootPrefix}{$pathSelect}
				</td>
			</tr>
			{/if}
			<tr>
				<td class="main_field_confirm" colspan="2">
                    {if $Config->get('panel.customerpathedit') != "Yes"}
                        {$pathSelect}
                    {/if}
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.domains.subdomain_add}" />
				</td>
			</tr>
		</table>
	</form>
