$header
	<form method="post" action="$filename">
	<input type="hidden" name="s" value="$s" />
	<input type="hidden" name="page" value="$page" />
	<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customer_add']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="loginname" value="" maxlength="10" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['name']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="name" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['firstname']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="firstname" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['company']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="company" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['street']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="street" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['zipcode']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="zipcode" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['city']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="city" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['phone']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="phone" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['fax']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="fax" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['customernumber']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customernumber" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['language']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="tendina_nobordo" name="def_language">$language_options</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['diskspace']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="diskspace" value="0" maxlength="6" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['traffic']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="traffic" value="0" maxlength="3" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['subdomains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="subdomains" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['emails']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="emails" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['accounts']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_accounts" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['forwarders']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_forwarders" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['ftps']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ftps" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['mysqls']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="mysqls" value="0" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['phpenabled']}?</td>
				<td class="main_field_display" nowrap="nowrap">$phpenabled</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['stdsubdomain_add']}?</td>
				<td class="main_field_display" nowrap="nowrap">$createstdsubdomain</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['password']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="password" name="customer_password" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['sendpassword']}?</td>
				<td class="main_field_display" nowrap="nowrap">$sendpassword</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
		<tr>
			<td class="main_field_name">*: {$lng['admin']['valuemandatory']}</td>
		</tr>
		<tr>
			<td class="main_field_name">**: {$lng['admin']['valuemandatorycompany']}</td>
		</tr>
	</table>
	<br />
	<br />
$footer