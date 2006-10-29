	<form method="post" action="{url module=domains action=edit}">
		<input type="hidden" name="id" value="{$Config->get('env.id')}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.edit}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.customer}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{$result.name} {$result.firstname} ({$result.loginname})
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.domain}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result.domain}</td>
			</tr>
			{if $alias_check == '0'}<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.aliasdomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="alias">
						{html_options options=$domains selected=$result.aliasdomain}
					</select>
				</td>
			</tr>{/if}
			{if $User.change_serversettings == '1'}<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.documentroot}:<font size="-2"><br />
					({l10n get=SysCP.globallang.emptyfordefaults})
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="documentroot" value="{$result.documentroot}" size="60" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.ipport}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="ipandport">
						{html_options options=$ipsandports selected=$result.ipandport}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.nameserver}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="isbinddomain" options=$isbinddomain selected=$result.isbinddomain}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.zonefile}:<font size="-2"><br />
					({l10n get=SysCP.globallang.emptyfordefaults})
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="zonefile" value="{$result.zonefile}" size="60" />
				</td>
			</tr>{/if}
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.emaildomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="isemaildomain" options=$isemaildomain selected=$result.isemaildomain}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.subdomainforemail}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="subcanemaildomain" options=$subcanemaildomain selected=$result.subcanemaildomain}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.edit}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="caneditdomain" options=$caneditdomain selected=$result.caneditdomain}
				</td>
			</tr>
			{if $User.change_serversettings == '1'}<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.openbasedir}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="openbasedir" options=$openbasedir selected=$result.openbasedir}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.safemode}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="safemode" options=$safemode selected=$result.safemode}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.apacheaccesslogfile}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{$result.access_logfile}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.apacheerrorlogfile}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{$result.error_logfile}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.ownvhostsettings}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<textarea class="textarea_noborder" rows="12" cols="60" name="specialsettings">{$result.specialsettings}</textarea>
				</td>
			</tr>{/if}
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>