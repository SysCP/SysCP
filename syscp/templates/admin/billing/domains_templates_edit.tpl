$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['domains_templates_edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['domains']['topleveldomain']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['tld']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_fee" value="{$result['interval_fee']}" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_length']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="interval_length" value="{$result['interval_length']}" size="10" /> <select class="dropdown_noborder" name="interval_type">$interval_type</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['interval_payment']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="interval_payment">$interval_payment</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['setup_fee']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="setup_fee" value="{$result['setup_fee']}" size="18" /> &#8364;</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['taxclass']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="taxclass">$taxclasses_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['valid_from']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="valid_from" value="{$result['valid_from']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['service']['valid_to']} ({$lng['panel']['dateformat']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="valid_to" value="{$result['valid_to']}" /></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer