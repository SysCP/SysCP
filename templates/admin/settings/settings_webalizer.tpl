$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['webalizersettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webalizer_enabled']['title']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_webalizer_enabled">$webalizer_enabled</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['webalizer_quiet']['title']}:</b><br />{$lng['serversettings']['webalizer_quiet']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="system_webalizer_quiet">$webalizer_quiet</select></td>
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
