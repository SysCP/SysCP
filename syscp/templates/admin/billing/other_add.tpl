$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['other_add']}</b>
				</td>
				<td class="maintitle_apply_right" nowarp="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="customerid">$customers_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['caption_setup']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="caption_setup" value="" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['caption_interval']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="caption_interval" value="" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingdata']}</b>
				</td>
				<td class="maintitle_apply_right" nowarp="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['template']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="templateid">$other_templates_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['taxclass']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="taxclass">$taxclasses_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['quantity']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="quantity" value="1" size="20" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_fee" value="0.00" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_length']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_length" value="0" size="10" /> <select class="dropdown_noborder" name="interval_type">$interval_type</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="interval_payment">$interval_payment</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['setup_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="setup_fee" value="0.00" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['active']}?</td>
				<td class="main_field_display" nowrap="nowrap">$service_active</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['start_date']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="servicestart_date" value="0" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer