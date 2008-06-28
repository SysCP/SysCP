$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<input type="hidden" name="mode" value="$mode" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['invoice']['fix']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['billing']['number']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="invoice_number" value="{$invoice_number_preset}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['invoice']['state']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="state">$invoice_states_option</select></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['invoice']['fix']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer