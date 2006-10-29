	<form method="post" action="{url module=domains action=add}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.domains.add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.customer}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="customerid">
						{html_options options=$customers}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.domain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="domain" value="" size="60" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.domains.aliasdomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="alias">
						{html_options options=$domains}
					</select>
				</td>
			</tr>
			{if $User.change_serversettings == '1'}<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.documentroot}:<br />
					<font size="-2">({l10n get=SysCP.globallang.emptyfordefaults})</font>
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="documentroot" value="" size="60" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.ipport}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="ipandport">
						{html_options options=$ipsandports selected=$ipsandports_sel}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.nameserver}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="isbinddomain" options=$isbinddomain selected=$isbinddomain_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.zonefile}:<br />
					<font size="-2">({l10n get=SysCP.globallang.emptyfordefaults})</font>
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="zonefile" value="" size="60" />
				</td>
			</tr>{/if}
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.emaildomain}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="isemaildomain" options=$isemaildomain selected=$isemaildomain_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.subdomainforemail}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="subcanemaildomain" options=$subcanemaildomain selected=$subcanemaildomain_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.edit}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="caneditdomain" options=$caneditdomain selected=$caneditdomain_sel}
				</td>
			</tr>
			{if $User.change_serversettings == '1'}<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.openbasedir}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="openbasedir" options=$openbasedir selected=$openbasedir_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.safemode}:</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="safemode" options=$safemode selected=$safemode_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.apacheaccesslogfile}:<br/>
					<font size="-2">({l10n get=SysCP.globallang.emptyfordefaults})</font>
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="access_log" value="" size="60" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.domains.apacheerrorlogfile}:<br/>
					<font size="-2">({l10n get=SysCP.globallang.emptyfordefaults})</font>
				</td>
				<td class="main_field_display" nowrap="nowrap">
				<input type="text" name="error_log" value="" size="60" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">{l10n get=SysCP.domains.ownvhostsettings}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<textarea class="textarea_noborder" rows="12" cols="60" name="specialsettings"></textarea>
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