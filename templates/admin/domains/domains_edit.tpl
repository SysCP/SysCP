$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<input type="hidden" name="send" value="send" />
		<if $override_billing_data_edit === true><input type="hidden" name="override_billing_data_edit" value="1" /></if>
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['domain_edit']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['customer']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $result['name'] != '' && $result['firstname'] != ''>{$result['name']}, {$result['firstname']}</if><if $result['name'] != '' && $result['firstname'] != '' && $result['company'] != ''> | </if><if $result['company'] != ''>{$result['company']}</if> ({$result['loginname']})</td>
			</tr>
			<tr>
				<td class="main_field_name">Domain:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['domain']}</td>
			</tr>
			<if $alias_check == '0'>
			<tr>
				<td class="main_field_name">{$lng['domains']['aliasdomain']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="alias">$domains</select></td>
			</tr>
			</if>
			<if $userinfo['change_serversettings'] == '1'>
			<tr>
				<td class="main_field_name">DocumentRoot:<br />({$lng['panel']['emptyfordefault']})</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="documentroot" value="{$result['documentroot']}" size="60" /></td>
			</tr>
			<tr>
				<td class="main_field_name">IP/Port:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ipandport">$ipsandports</select></td>
			</tr>
			<tr>
				<td class="main_field_name">Nameserver:</td>
				<td class="main_field_display" nowrap="nowrap">$isbinddomain</td>
			</tr>
			<tr>
				<td class="main_field_name">Zonefile:<br />({$lng['panel']['emptyfordefault']})</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="zonefile" value="{$result['zonefile']}" size="60" /></td>
			</tr>
			</if>
			<if $settings['system']['use_ssl'] == 1>
				<if $show_ssl_ipsandports == 1>
				<tr>
					<td class="main_field_name">SSL:</td>
					<td class="main_field_display" nowrap="nowrap">$ssl</td>
				</tr>
				<tr>
					<td class="main_field_name">SSL Redirect:</td>
					<td class="main_field_display" nowrap="nowrap">$ssl_redirect</td>
				</tr>
				<tr>
					<td class="main_field_name">SSL IP/Port:</td>
					<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ssl_ipandport">$ssl_ipsandports</select></td>
				</tr>
				<else>
				<tr>
					<td class="main_field_name" colspan="2">{$lng['panel']['nosslipsavailable']}</td>
				</tr>
				</if>
			</if>
			<tr>
				<td class="main_field_name">{$lng['admin']['wwwserveralias']}:</td>
				<td class="main_field_display" nowrap="nowrap">$wwwserveralias</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['emaildomain']}:</td>
				<td class="main_field_display" nowrap="nowrap">$isemaildomain</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['email_only']}:</td>
				<td class="main_field_display" nowrap="nowrap">$isemail_only</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['subdomainforemail']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="subcanemaildomain">$subcanemaildomain</select></td>
			</tr>
			<if $settings['dkim']['use_dkim'] == '1'>
 			<tr>
				<td class="main_field_name">DomainKeys:</td>
				<td class="main_field_display" nowrap="nowrap">$dkim</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['admin']['domain_edit']}:</td>
				<td class="main_field_display" nowrap="nowrap">$caneditdomain</td>
			</tr>
			<if $userinfo['change_serversettings'] == '1' || $userinfo['caneditphpsettings'] == '1'>
			<tr>
				<td class="main_field_name">OpenBasedir:</td>
				<td class="main_field_display" nowrap="nowrap">$openbasedir</td>
			</tr>
			<tr>
				<td class="main_field_name">Safemode:</td>
				<td class="main_field_display" nowrap="nowrap">$safemode</td>
			</tr>
			</if>
			<if ((int)$settings['system']['mod_fcgid'] == 1) && ($userinfo['change_serversettings'] == '1' || $userinfo['caneditphpsettings'] == '1')>
			<tr>
				<td class="main_field_name">{$lng['admin']['phpsettings']['title']}</td>
				<td class="main_field_display" nowrap="nowrap"><select name="phpconfig">$phpconfigs</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['mod_fcgid_starter']['title']}</td>
				<td class="main_field_display" nowrap="nowrap"><input size="60" name="mod_fcgid_starter" value="$mod_fcgid_starter"/></td>
			</tr>
			</if>
			<if $userinfo['change_serversettings'] == '1'>
			<tr>
				<td class="main_field_name">Speciallogfile:</td>
				<td class="main_field_display" nowrap="nowrap"><b>$speciallogfile</b></td>
			</tr>
			<tr>
				<td class="main_field_name" valign="top">{$lng['admin']['ownvhostsettings']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea class="textarea_border" rows="12" cols="60" name="specialsettings">{$result['specialsettings']}</textarea></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['domains']['add_date']}: ({$lng['panel']['dateformat']})</td>
				<td class="main_field_display" nowrap="nowrap">{$result['add_date']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['domains']['registration_date']}: ({$lng['panel']['dateformat']})</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="registration_date" value="{$result['registration_date']}" size="10" /></td>
			</tr>
			</if>
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
				<td class="main_field_display" nowrap="nowrap"><if $result['lastinvoiced_date'] != 0>{$result['lastinvoiced_date']}<else>{$lng['panel']['never']}</if></td>
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
	<br />
$footer