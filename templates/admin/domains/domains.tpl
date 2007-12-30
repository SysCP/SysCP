$header
	<form action="$filename?s=$s&amp;page=$page" method="post">
		<input type="hidden" name="token" value="{$userinfo['formtoken']}" />
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['domains']}</b></td>
				<td class="maintitle_search_right" colspan="4">{$searchcode}</td>
			</tr>
			<if ($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1') && 15 < $count>
			<tr>
				<td class="field_display_border_left" colspan="5"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['domain_add']}</a></td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left">{$lng['domains']['domainname']}&nbsp;&nbsp;{$arrowcode['d.domain']}</td>
				<td class="field_display">{$lng['admin']['ipsandports']['ip']}&nbsp;&nbsp;{$arrowcode['ip.ip']}&nbsp;:&nbsp;{$lng['admin']['ipsandports']['port']}&nbsp;&nbsp;{$arrowcode['ip.port']}</td>
				<td class="field_display">{$lng['admin']['customer']}&nbsp;&nbsp;{$arrowcode['c.loginname']}</td>
				<td class="field_display_search" colspan="2">{$sortcode}</td>
			</tr>
			$domains
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="5" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<if $userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1'>
			<tr>
				<td class="field_display_border_left" colspan="5"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['domain_add']}</a></td>
			</tr>
			</if>
		</table>
	</form>
	<br />
	<br />
$footer