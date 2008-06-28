$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="mode" value="$mode" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['invoice']['change_state']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['number']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['invoice_number']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['invoice_date']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['invoice_date']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['invoice']['state']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="state">$invoice_states_option</select></td>
			</tr>
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['contactdata']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['name']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="name" value="{$contact['name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['firstname']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="firstname" value="{$contact['firstname']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['title']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="title" value="{$contact['title']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['company']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="company" value="{$contact['company']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['street']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="street" value="{$contact['street']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['zipcode']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="zipcode" value="{$contact['zipcode']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['city']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="city" value="{$contact['city']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['country']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="country" value="{$contact['country']}" /></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer