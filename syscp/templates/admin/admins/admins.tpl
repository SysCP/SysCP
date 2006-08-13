$header
	<form action="$filename?s=$s&amp;page=$page" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="3"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['admins']}</b></td>
				<td class="maintitle_search_right" colspan="6">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}<br />{$arrowcode['loginname']}</td>
				<td class="field_display">{$lng['customer']['name']}<br />{$arrowcode['name']}</td>
				<td class="field_display">{$lng['admin']['customers']}<br />{$lng['admin']['domains']}</td>
				<td class="field_display">Space<br />Traffic</td>
				<td class="field_display">MySQL<br />FTP</td>
				<td class="field_display">eMails<br />Subdomains</td>
				<td class="field_display">Accounts<br />Forwarders</td>
				<td class="field_display">{$lng['admin']['deactivated']}<br />{$arrowcode['deactivated']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$admins
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="9" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="9"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['admin_add']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer