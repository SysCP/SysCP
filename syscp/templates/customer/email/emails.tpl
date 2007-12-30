$header
	<form action="$filename?s=$s&amp;page=$page" method="post">
		<input type="hidden" name="token" value="{$userinfo['formtoken']}" />
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td  class="maintitle_search_left"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['email']['emails']}</b></td>
				<td class="maintitle_search_right" colspan="5">{$searchcode}</td>
			</tr>
			<if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && 15 < $emails_count && $emaildomains_count !=0 >
			<tr>
				<td class="field_display_border_left" colspan="6"><a href="$filename?page={$page}&amp;action=add&amp;s=$s">{$lng['emails']['emails_add']}</a></td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left">{$lng['emails']['emailaddress']}&nbsp;&nbsp;{$arrowcode['m.email_full']}</td>
				<td class="field_display">{$lng['emails']['forwarders']}&nbsp;&nbsp;{$arrowcode['m.destination']}</td>
				<td class="field_display">{$lng['emails']['account']}</td>
				<td class="field_display">{$lng['emails']['catchall']}</td>
				<td class="field_display_search" colspan="2">{$sortcode}</td>
			</tr>
			$accounts
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="6" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<if ($userinfo['emails_used'] < $userinfo['emails'] || $userinfo['emails'] == '-1') && $emaildomains_count !=0 >
			<tr>
				<td class="field_display_border_left" colspan="6"><a href="$filename?page={$page}&amp;action=add&amp;s=$s">{$lng['emails']['emails_add']}</a></td>
			</tr>
			</if>
		</table>
	</form>
	<br />
	<br />
$footer