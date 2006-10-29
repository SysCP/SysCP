	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}/title.gif" alt="" />&nbsp;{l10n get=SysCP.index.customer_details}
			</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.name}:</td>
			<td class="field_display">{$User.firstname} {$User.name}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.company}:</td>
			<td class="field_display">{$User.company}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.street}:</td>
			<td class="field_display">{$User.street}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.zipcode}/{l10n get=SysCP.index.city}:</td>
			<td class="field_display">{$User.zipcode} {$User.city}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.email}:</td>
			<td class="field_display">{$User.email}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.customernumber}:</td>
			<td class="field_display">{$User.customernumber}</td>
		</tr>
		<tr>
			<td colspan="2" class="maintitle">
				<img src="{$imagedir}/title.gif" alt="" />&nbsp;{l10n get=SysCP.index.account_details}
			</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.globallang.username}:</td>
			<td class="field_display">{$User.loginname}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.domains}:</td>
			<td class="field_display">{$domains}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.subdomains}:</td>
			<td class="field_display">{$User.subdomains_used} ({$User.subdomains})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.diskspace}:</td>
			<td class="field_display">{$User.diskspace_used} ({$User.diskspace})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.traffic} ({$month}):</td>
			<td class="field_display">{$User.traffic_used} ({$User.traffic})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.emails}:</td>
			<td class="field_display">{$User.emails_used} ({$User.emails})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.accounts}:</td>
			<td class="field_display">{$User.email_accounts_used} ({$User.email_accounts})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.forwarders}:</td>
			<td class="field_display">{$User.email_forwarders_used} ({$User.email_forwarders})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.mysqls}:</td>
			<td class="field_display">{$User.mysqls_used} ({$User.mysqls})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{l10n get=SysCP.index.ftps}:</td>
			<td class="field_display">{$User.ftps_used} ({$User.ftps})</td>
		</tr>
	</table>