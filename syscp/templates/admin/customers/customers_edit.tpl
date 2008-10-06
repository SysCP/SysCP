$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<input type="hidden" name="send" value="send" />
		<if $override_billing_data_edit === true><input type="hidden" name="override_billing_data_edit" value="1" /></if>
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customer_edit']}</b></td>
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
				<td class="main_field_display" nowrap="nowrap">{$result['loginname']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['documentroot']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['documentroot']}</td>
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
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="name" value="{$result['name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['firstname']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="firstname" value="{$result['firstname']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['title']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="title" value="{$result['title']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['company']}: **</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="company" value="{$result['company']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['street']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="street" value="{$result['street']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['zipcode']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="zipcode" value="{$result['zipcode']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['city']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="city" value="{$result['city']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['country']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="country" value="{$result['country']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['phone']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="phone" value="{$result['phone']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['fax']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="fax" value="{$result['fax']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="email" value="{$result['email']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['customernumber']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="customernumber" value="{$result['customernumber']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['taxid']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="taxid" value="{$result['taxid']}" /></td>
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
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="diskspace" value="{$result['diskspace']}" maxlength="6" />&nbsp;{$diskspace_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['traffic']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="traffic" value="{$result['traffic']}" maxlength="3" />&nbsp;{$traffic_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['subdomains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="subdomains" value="{$result['subdomains']}" maxlength="9" />&nbsp;{$subdomains_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['emails']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="emails" value="{$result['emails']}" maxlength="9" />&nbsp;{$emails_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['accounts']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_accounts" value="{$result['email_accounts']}" maxlength="9" />&nbsp;{$email_accounts_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['forwarders']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_forwarders" value="{$result['email_forwarders']}" maxlength="9" />&nbsp;{$email_forwarders_ul}</td>
			</tr>
			<if $settings['system']['mail_quota_enabled'] == 1>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_quota']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="email_quota" value="{$result['email_quota']}" maxlength="9" />&nbsp;<select class="dropdown_noborder" name="email_quota_type">$quota_type_option</select>&nbsp;{$email_quota_ul}</td>
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
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="ftps" value="{$result['ftps']}" maxlength="9" />&nbsp;{$ftps_ul}</td>
			</tr>
			<if $settings['ticket']['enabled'] == 1 >
			<tr>
				<td class="main_field_name">{$lng['customer']['tickets']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="tickets" value="{$result['tickets']}" maxlength="9" />&nbsp;{$tickets_ul}</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['customer']['mysqls']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="textul" name="mysqls" value="{$result['mysqls']}" maxlength="9" />&nbsp;{$mysqls_ul}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['phpenabled']}?</td>
				<td class="main_field_display" nowrap="nowrap">$phpenabled</td>
			</tr>
			<if $settings['billing']['activate_billing'] == '1'>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<if $enable_billing_data_edit === true || $userinfo['edit_billingdata'] != '1'><input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /><else><input class="bottom" type="submit" name="enable_billing_data_edit" value="{$lng['panel']['allow_modifications']}" /></if>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['included_domains']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="included_domains_qty" value="{$result['included_domains_qty']}" size="3" /> x <input type="text" name="included_domains_tld" value="{$result['included_domains_tld']}" size="14" /><else>{$result['included_domains_qty']} x {$result['included_domains_tld']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['additional_traffic']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="additional_traffic_fee" value="{$result['additional_traffic_fee']}" size="7" /> &#8364; / <input type="text" name="additional_traffic_unit" value="{$result['additional_traffic_unit']}" size="6" /><else>{$result['additional_traffic_fee']} &#8364; / {$result['additional_traffic_unit']}</if> GB</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['additional_diskspace']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="additional_diskspace_fee" value="{$result['additional_diskspace_fee']}" size="7" /> &#8364; / <input type="text" name="additional_diskspace_unit" value="{$result['additional_diskspace_unit']}" size="6" /><else>{$result['additional_diskspace_fee']} &#8364; / {$result['additional_diskspace_unit']}</if> MB</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_fee']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="interval_fee" value="{$result['interval_fee']}" size="18" /><else>{$result['interval_fee']}</if> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_length']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="interval_length" value="{$result['interval_length']}" size="10" /> <select class="dropdown_noborder" name="interval_type">$interval_type</select><else>{$result['interval_length']} {$lng['panel']['intervalfee_type'][$result['interval_type']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><select class="dropdown_noborder" name="interval_payment">$interval_payment_options</select><else>$interval_payment</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['payment_every']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="payment_every" value="{$result['payment_every']}" size="5" /><else>{$result['payment_every']}</if> x {$lng['service']['interval_length']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['setup_fee']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="setup_fee" value="{$result['setup_fee']}" size="18" /><else>{$result['setup_fee']}</if> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['taxclass']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><select class="dropdown_noborder" name="taxclass">$taxclasses_option</select><else>{$taxclasses[$result['taxclass']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['active']}?</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'>$service_active_options<else>$service_active</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['start_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display<if $override_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true && $userinfo['edit_billingdata'] == '1'><input type="text" name="servicestart_date" value="{$result['servicestart_date']}" /><else>{$result['servicestart_date']}</if></td>
			</tr>
			<if $result['serviceend_date'] != 0>
			<tr>
				<td class="main_field_name">{$lng['service']['end_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap">{$result['serviceend_date']}</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['service']['lastinvoiced_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap"><if $result['lastinvoiced_date'] != '0000-00-00'>{$result['lastinvoiced_date']}<else>{$lng['panel']['never']}</if></td>
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
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="contract_date" value="{$result['contract_date']}" /><else>{$result['contract_date']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['contract_number']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="contract_number" value="{$result['contract_number']}" /><else>{$result['contract_number']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top">{$lng['customer']['additional_service_description']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><textarea name="additional_service_description" cols="20" rows="5">{$result['additional_service_description']}</textarea><else>{$result['additional_service_description']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['term_of_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="term_of_payment" value="{$result['term_of_payment']}" /><else>{$result['term_of_payment']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['calc_tax']}?</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'>$calc_tax_options<else>$calc_tax</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['payment_method']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><select class="dropdown_noborder" name="payment_method">{$payment_method}</select><else>{$lng['customer']['payment_methods'][(int)$result['payment_method']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top">{$lng['customer']['bankaccount_holder']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><textarea name="bankaccount_holder" cols="20" rows="3">{$result['bankaccount_holder']}</textarea><else>{$result['bankaccount_holder']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_number']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="bankaccount_number" value="{$result['bankaccount_number']}" /><else>{$result['bankaccount_number']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_blz']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="bankaccount_blz" value="{$result['bankaccount_blz']}" /><else>{$result['bankaccount_blz']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['bankaccount_bank']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $userinfo['edit_billingdata'] == '1'><input type="text" name="bankaccount_bank" value="{$result['bankaccount_bank']}" /><else>{$result['bankaccount_bank']}</if></td>
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
