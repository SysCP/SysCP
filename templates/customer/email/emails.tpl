$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="6"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['email']['emails']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left">{$lng['emails']['emailaddress']}</td>
			<td class="field_display">{$lng['emails']['forwarders']}</td>
			<td class="field_display">{$lng['emails']['account']}</td>
			<td class="field_display">{$lng['emails']['catchall']}</td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		<if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && 15 < $emails_count && $emaildomains_count !=0 >
		<tr>
			<td class="field_display_border_left" colspan="6"><a href="$filename?page={$page}&amp;action=add&amp;s=$s">{$lng['emails']['emails_add']}</a></td>
		</tr>
		</if>
		$accounts
		<if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && $emaildomains_count !=0 >
		<tr>
			<td class="field_display_border_left" colspan="6"><a href="$filename?page={$page}&amp;action=add&amp;s=$s">{$lng['emails']['emails_add']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer