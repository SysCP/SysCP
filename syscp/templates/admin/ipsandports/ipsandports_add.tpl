$header
	<form method="post" action="$filename">
	<input type="hidden" name="s" value="$s" />
	<input type="hidden" name="page" value="$page" />
	<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ipsandports']['add']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['ip']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ip" value="" size="15" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['port']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="port" value="" size="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['createvhostcontainer']}:</td>
				<td class="main_field_display" nowrap="nowrap">$vhostcontainer</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ownvhostsettings']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea class="textarea_noborder" rows="12" cols="60" name="specialsettings"></textarea></td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer