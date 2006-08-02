$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="4"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['mysql']['databases']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left">{$lng['mysql']['databasename']}</td>
			<td class="field_display">{$lng['mysql']['databasedescription']}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		<if ($userinfo['mysqls_used'] < $userinfo['mysqls'] || $userinfo['mysqls'] == '-1') && 15 < $mysqls_count >
		<tr>
			<td class="field_display_border_left" colspan="4"><a href="$filename?page=mysqls&amp;action=add&amp;s=$s">{$lng['mysql']['database_create']}</a></td>
		</tr>
		</if>
		$mysqls
		<if ($userinfo['mysqls_used'] < $userinfo['mysqls'] || $userinfo['mysqls'] == '-1') >
		<tr>
			<td class="field_display_border_left" colspan="4"><a href="$filename?page=mysqls&amp;action=add&amp;s=$s">{$lng['mysql']['database_create']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer