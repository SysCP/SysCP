	<form method="post" action="{url module=customers action=edit}">
		<input type="hidden" name="id" value="{$Config->get('env.id')}" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.customers.customer_edit}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.globallang.username}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{$result.loginname}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.documentroot}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{$result.homedir}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.globallang.name}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="name" value="{$result.name}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.firstname}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="firstname" value="{$result.firstname}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.company}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="company" value="{$result.company}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.street}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="street" value="{$result.street}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.zipcode}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="zipcode" value="{$result.zipcode}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.city}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="city" value="{$result.city}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.phone}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="phone" value="{$result.phone}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.fax}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="fax" value="{$result.fax}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email" value="{$result.email}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.number}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="customernumber" value="{$result.customernumber}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.language}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="def_language">
						{html_options options=$lang_list selected=$result.def_language}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.diskspace}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="diskspace" value="{$result.diskspace}" maxlength="6" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.traffic} ({l10n get=SysCP.globallang.gb}): *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="traffic" value="{$result.traffic}" maxlength="3" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.subdomains}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="subdomains" value="{$result.subdomains}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.emails}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="emails" value="{$result.emails}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email_accounts}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email_accounts" value="{$result.email_accounts}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email_forwarders}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email_forwarders" value="{$result.email_forwarders}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.ftp_accounts}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="ftps" value="{$result.ftps}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.mysql_databases}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="mysqls" value="{$result.mysqls}" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.stdsubdomain_add}?<br />
					({$result.loginname}.{$Config->get('system.hostname')})
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="createstdsubdomain" options=$createstdsubdomain selected=$createstdsubdomain_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.deactivate_user}?
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="deactivated" options=$deactivated selected=$result.deactivated}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.globallang.password} ({l10n get=SysCP.globallang.emptyfornochanges}):
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="newpassword" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>