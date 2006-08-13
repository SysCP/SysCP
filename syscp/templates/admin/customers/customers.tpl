$header
	<form action="$filename?s=$s&amp;page=$page" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="3" ><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customers']}</b></td>
				<td class="maintitle_search_right" colspan="7">{$searchcode}</td>
			</tr>
			<if ($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1') && 15 < $userinfo['customers_used']>
			<tr>
				<td colspan="10" class="field_display_border_left"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['customer_add']}</a></td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}<br />{$arrowcode['c.loginname']}</td>
				<if $userinfo['customers_see_all']>
				<td class="field_display">{$lng['admin']['admin']}<br />{$arrowcode['a.loginname']}</td>
				</if>
				<td class="field_display">{$lng['customer']['name']}&nbsp;&nbsp;{$arrowcode['c.name']}<br />{$lng['customer']['firstname']}&nbsp;&nbsp;{$arrowcode['c.firstname']}</td>
				<td class="field_display">Domains</td>
				<td class="field_display">Space<br />Traffic</td>
				<td class="field_display">MySQL<br />FTP</td>
				<td class="field_display">eMails<br />Subdomains</td>
				<td class="field_display">Accounts<br />Forwarders</td>
				<td class="field_display">{$lng['admin']['deactivated']}<br />{$arrowcode['c.deactivated']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$customers
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="10" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<if $userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1'>
			<tr>
				<td colspan="10" class="field_display_border_left"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['customer_add']}</a></td>
			</tr>
			</if>
		</table>
	</form>
	<br />
	<br />
$footer