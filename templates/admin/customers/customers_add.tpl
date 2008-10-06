$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="send" value="send" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customer_add']}</b></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['accountdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="loginname" value="" maxlength="10" /></td>
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
				<td class="main_field_name">{$lng['login']['language']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="def_language">$language_options</select></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['contactdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['name']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="name" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['firstname']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="firstname" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['title']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="title" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['company']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="company" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['street']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="street" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['zipcode']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="zipcode" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['city']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="city" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['country']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="country" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['phone']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="phone" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['fax']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="fax" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="email" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['customernumber']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="customernumber" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['taxid']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="taxid" value="" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['servicedata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['diskspace']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="diskspace" value="" maxlength="6" />&nbsp;{$diskspace_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['traffic']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="traffic" value="" maxlength="3" />&nbsp;{$traffic_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['subdomains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="subdomains" value="" maxlength="9" />&nbsp;{$subdomains_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['emails']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="emails" value="" maxlength="9" />&nbsp;{$emails_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['accounts']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_accounts" value="" maxlength="9" />&nbsp;{$email_accounts_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['forwarders']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_forwarders" value="" maxlength="9" />&nbsp;{$email_forwarders_ul}</td>
			</tr>
			<if $settings['system']['mail_quota_enabled'] == 1>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_quota']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_quota" value="" maxlength="9" />&nbsp;<select class="dropdown_noborder" name="email_quota_type">$quota_type_option</select>&nbsp;{$diskspace_ul}</td>
			</tr>
			</if>
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
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="ftps" value="" maxlength="9" />&nbsp;{$ftps_ul}</td>
			</tr>
			<if $settings['ticket']['enabled'] == 1 >
			<tr>
				<td class="main_field_name">{$lng['customer']['tickets']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="tickets" value="" maxlength="9" />&nbsp;{$tickets_ul}</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['customer']['mysqls']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="mysqls" value="" maxlength="9" />&nbsp;{$mysqls_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['phpenabled']}?</td>
				<td class="main_field_display" nowrap="nowrap">$phpenabled</td>
			</tr>
			<if $userinfo['edit_billingdata'] == '1' && $settings['billing']['activate_billing'] == '1'>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['included_domains']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="included_domains_qty" value="" size="3" /> x <input type="text" name="included_domains_tld" value="de com" size="14" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['additional_traffic']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="additional_traffic_fee" value="" size="7" /> &#8364; / <input type="text" name="additional_traffic_unit" value="" size="6" /> GB</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['additional_diskspace']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="additional_diskspace_fee" value="" size="7" /> &#8364; / <input type="text" name="additional_diskspace_unit" value="" size="6" /> MB</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_fee" value="" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_length']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_length" value="" size="10" /> <select class="dropdown_noborder" name="interval_type">$interval_type</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="interval_payment">$interval_payment</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['payment_every']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="payment_every" value="" size="5" /> x {$lng['service']['interval_length']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['setup_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="setup_fee" value="" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['taxclass']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="taxclass">$taxclasses_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['active']}?</td>
				<td class="main_field_display" nowrap="nowrap">$service_active</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['start_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="servicestart_date" value="0" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['invoicedata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['contract_date']} ({$lng['panel']['dateformat']}): *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="contract_date" value="{$contract_date}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['contract_number']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="contract_number" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top">{$lng['customer']['additional_service_description']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea name="additional_service_description" cols="20" rows="5"></textarea></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['term_of_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="term_of_payment" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['calc_tax']}?</td>
				<td class="main_field_display" nowrap="nowrap">{$calc_tax}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['payment_method']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="payment_method">{$payment_method}</select></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top">{$lng['customer']['bankaccount_holder']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea name="bankaccount_holder" cols="20" rows="3"></textarea></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_number']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="bankaccount_number" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_blz']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="bankaccount_blz" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_bank']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="bankaccount_bank" value="" /></td>
			</tr>
			</if>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
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
