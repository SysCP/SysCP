$header
	<form method="post" action="$filename">
		<input type="hidden" name="token" value="{$userinfo['formtoken']}" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ipsandports']['edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['ip']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ip" value="{$result['ip']}" size="15" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['port']}:</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="port" value="{$result['port']}" size="5" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['create_listen_statement']}:</td>
				<td class="main_field_display" nowrap="nowrap">$listen_statement</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['create_namevirtualhost_statement']}:</td>
				<td class="main_field_display" nowrap="nowrap">$namevirtualhost_statement</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['create_vhostcontainer']}:</td>
				<td class="main_field_display" nowrap="nowrap">$vhostcontainer</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ownvhostsettings']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea class="textarea_border" rows="12" cols="60" name="specialsettings">{$result['specialsettings']}</textarea></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ipsandports']['create_vhostcontainer_servername_statement']}:</td>
				<td class="main_field_display" nowrap="nowrap">$vhostcontainer_servername_statement</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer