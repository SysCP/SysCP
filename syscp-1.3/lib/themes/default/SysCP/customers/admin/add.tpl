	<form method="post" action="{url module=customers action=add}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.customers.customer_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.globallang.username}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="loginname" value="" maxlength="10" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.globallang.name}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="name" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.firstname}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="firstname" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.company}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="company" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.street}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="street" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.zipcode}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="zipcode" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.city}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="city" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.phone}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="phone" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.fax}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="fax" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.number}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="customernumber" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.language}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="def_language">
						{html_options options=$languages selected=$User.def_language}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.diskspace}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="diskspace" value="0" maxlength="6" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.traffic} ({l10n get=SysCP.globallang.gb}): *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="traffic" value="0" maxlength="3" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.subdomains}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="subdomains" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.emails}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="emails" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email_accounts}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email_accounts" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.email_forwarders}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="email_forwarders" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.ftp_accounts}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="ftps" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.mysql_databases}: *
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="mysqls" value="0" maxlength="9" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.stdsubdomain_add}?
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="createstdsubdomain" options=$createstdsubdomain selected=$createstdsubdomain_sel}
				</td>
			</tr>
			<tr>
				<td class="main_field_name" nowrap="nowrap">
					{l10n get=SysCP.globallang.password}:
				</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="password" name="password" value="" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">
					{l10n get=SysCP.customers.sendpassword}?
				</td>
				<td class="main_field_display" nowrap="nowrap">
					{html_radios name="sendpassword" options=$sendpassword selected=$sendpassword_sel}
				</td>
			</tr>
			{eval var=$syscp_plugins}
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
	</form>