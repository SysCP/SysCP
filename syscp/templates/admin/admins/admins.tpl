$header
	<form action="$filename?s=$s&amp;page=$page" method="post">
		<input type="hidden" name="token" value="{$userinfo['formtoken']}" />
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="3"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['admins']}</b></td>
				<td class="maintitle_search_right" colspan="7">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}<br />{$arrowcode['loginname']}</td>
				<td class="field_display">{$lng['customer']['name']}<br />{$arrowcode['name']}</td>
				<td class="field_display">{$lng['admin']['customers']}<br />{$lng['admin']['domains']}</td>
				<td class="field_display">{$lng['customer']['diskspace']}<br />{$lng['customer']['traffic']}</td>
				<td class="field_display">{$lng['customer']['mysqls']}<br />{$lng['customer']['ftps']}</td>
				<td class="field_display">{$lng['customer']['tickets']}</td>
				<td class="field_display">{$lng['customer']['emails']}<br />{$lng['customer']['subdomains']}</td>
				<td class="field_display">{$lng['customer']['accounts']}<br />{$lng['customer']['forwarders']}</td>
				<td class="field_display">{$lng['admin']['deactivated']}<br />{$arrowcode['deactivated']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$admins
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="10" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="10"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['admin_add']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer