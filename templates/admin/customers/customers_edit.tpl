$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customer_edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['loginname']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['documentroot']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['documentroot']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['name']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="name" value="{$result['name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['firstname']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="firstname" value="{$result['firstname']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['company']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="company" value="{$result['company']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['street']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="street" value="{$result['street']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['zipcode']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="zipcode" value="{$result['zipcode']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['city']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="city" value="{$result['city']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['phone']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="phone" value="{$result['phone']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['fax']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="fax" value="{$result['fax']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email" value="{$result['email']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['customernumber']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customernumber" value="{$result['customernumber']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['language']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="tendina_nobordo" name="def_language">$language_options</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['diskspace']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="diskspace" value="{$result['diskspace']}" maxlength="6" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['traffic']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="traffic" value="{$result['traffic']}" maxlength="3" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['subdomains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="subdomains" value="{$result['subdomains']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['emails']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="emails" value="{$result['emails']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['accounts']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_accounts" value="{$result['email_accounts']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['forwarders']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_forwarders" value="{$result['email_forwarders']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_quota']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_quota" value="{$result['email_quota']}" maxlength="9" />&nbsp;<select class="dropdown_noborder" name="email_quota_type">$quota_type_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_imap']}: *</td>
				<td class="main_field_display" nowrap="nowrap">$email_imap</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_pop3']}: *</td>
				<td class="main_field_display" nowrap="nowrap">$email_pop3</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['ftps']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ftps" value="{$result['ftps']}" maxlength="9" /></td>
			</tr>
			<if $settings['ticket']['enabled'] == 1 >
			<tr>
				<td class="main_field_name">{$lng['customer']['tickets']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="tickets" value="{$result['tickets']}" maxlength="9" /></td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['customer']['mysqls']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="mysqls" value="{$result['mysqls']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['phpenabled']}?</td>
				<td class="main_field_display" nowrap="nowrap">$phpenabled</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['stdsubdomain_add']}?<br />({$result['loginname']}.{$settings['system']['hostname']})</td>
				<td class="main_field_display" nowrap="nowrap">$createstdsubdomain</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['deactivated_user']}?</td>
				<td class="main_field_display" nowrap="nowrap">$deactivated</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['password']} ({$lng['panel']['emptyfornochanges']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="password" name="customer_password" value="" /></td>
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