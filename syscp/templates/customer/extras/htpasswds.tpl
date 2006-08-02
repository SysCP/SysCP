$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="4"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['extras']['directoryprotection']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left">{$lng['login']['username']}</td>
			<td class="field_display">{$lng['panel']['path']}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		$htpasswds
		<tr>
			<td class="field_display_border_left" colspan="4"><a href="$filename?page=htpasswds&amp;action=add&amp;s=$s">{$lng['extras']['directoryprotection_add']}</a></td>
		</tr>
	</table>
	<br />
	<br />
$footer