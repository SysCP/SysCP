$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="4"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['ftp']['accounts']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left">{$lng['login']['username']}</td>
			<td class="field_display">{$lng['panel']['path']}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		<if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') && 15 < $ftps_count >
		<tr>
			<td class="field_display_border_left" colspan="4"><a href="$filename?page=accounts&amp;action=add&amp;s=$s">{$lng['ftp']['account_add']}</a></td>
		</tr>
		</if>
		$accounts
		<if 0 < $pages>
		<tr>
			<td colspan="4" class="field_display">{$paging}</td>
		</tr>
		</if>
		<if ($userinfo['ftps_used'] < $userinfo['ftps'] || $userinfo['ftps'] == '-1') >
		<tr>
			<td class="field_display_border_left" colspan="4"><a href="$filename?page=accounts&amp;action=add&amp;s=$s">{$lng['ftp']['account_add']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer