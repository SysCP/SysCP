$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['panelsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['login']['language']}:</b><br />{$lng['serversettings']['language']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="panel_standardlanguage">$languages</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['natsorting']['title']}:</b><br />{$lng['serversettings']['natsorting']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$natsorting}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['no_robots']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$no_robots}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['paging']['title']}:</b><br />{$lng['serversettings']['paging']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_paging" value="{$settings['panel']['paging']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['pathedit']['title']}:</b><br />{$lng['serversettings']['pathedit']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="panel_pathedit">$pathedit</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['adminmail']['title']}:</b><br />{$lng['serversettings']['adminmail']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_adminmail" value="{$settings['panel']['adminmail']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['decimal_places']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_decimal_places" value="{$settings['panel']['decimal_places']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['phpmyadmin_url']['title']}:</b><br />{$lng['serversettings']['phpmyadmin_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_phpmyadmin_url" value="{$settings['panel']['phpmyadmin_url']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webmail_url']['title']}:</b><br />{$lng['serversettings']['webmail_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_webmail_url" value="{$settings['panel']['webmail_url']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webftp_url']['title']}:</b><br />{$lng['serversettings']['webftp_url']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="panel_webftp_url" value="{$settings['panel']['webftp_url']}" /></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer
