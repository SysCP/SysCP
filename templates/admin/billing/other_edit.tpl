$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<if $override_billing_data_edit === true><input type="hidden" name="override_billing_data_edit" value="1" /></if>
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['other_edit']}</b>
				</td>
				<td class="maintitle_apply_right" nowarp="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$customers[$result['customerid']]}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['caption_setup']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="caption_setup" value="{$result['caption_setup']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['caption_interval']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="caption_interval" value="{$result['caption_interval']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowarp="nowrap">
					<if $enable_billing_data_edit === true><input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /><else><input class="bottom" type="submit" name="enable_billing_data_edit" value="{$lng['panel']['allow_modifications']}" /></if>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['template']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><select class="dropdown_noborder" name="templateid">$other_templates_option</select><else>{$other_templates[$result['templateid']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['taxclass']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><select class="dropdown_noborder" name="taxclass">$taxclasses_option</select><else>{$taxclasses[$result['taxclass']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['quantity']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><input type="text" name="quantity" value="{$result['quantity']}" /><else>{$result['quantity']}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_fee']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><input type="text" name="interval_fee" value="{$result['interval_fee']}" size="18" /><else>{$result['interval_fee']}</if> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_length']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><input type="text" name="interval_length" value="{$result['interval_length']}" size="10" /> <select class="dropdown_noborder" name="interval_type">$interval_type</select><else>{$result['interval_length']} {$lng['panel']['intervalfee_type'][$result['interval_type']]}</if></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="interval_payment">$interval_payment</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['setup_fee']}:</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><input type="text" name="setup_fee" value="{$result['setup_fee']}" size="18" /><else>{$result['setup_fee']}</if> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['active']}?</td>
				<td class="main_field_display" nowrap="nowrap">$service_active</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['start_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display<if $override_billing_data_edit === true>_red</if>" nowrap="nowrap"><if $enable_billing_data_edit === true><input type="text" name="servicestart_date" value="{$result['servicestart_date']}" /><else>{$result['servicestart_date']}</if></td>
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
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer