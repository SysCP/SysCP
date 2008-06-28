$header
	<form action="$filename?s=$s" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="5" ><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['other']}</b></td>
				<td class="maintitle_search_right" colspan="9">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}<br />{$arrowcode['c.loginname']}</td>
				<td class="field_display">{$lng['customer']['name']}<br />{$arrowcode['c.name']}</td>
				<td class="field_display">{$lng['customer']['firstname']}<br />{$arrowcode['c.firstname']}</td>
				<td class="field_display">{$lng['customer']['company']}<br />{$arrowcode['c.company']}</td>
				<td class="field_display">{$lng['billing']['caption_setup']}<br />{$arrowcode['o.caption_setup']}</td>
				<td class="field_display">{$lng['billing']['caption_interval']}<br />{$arrowcode['o.caption_interval']}</td>
				<td class="field_display">{$lng['service']['quantity']}<br />{$arrowcode['o.quantity']}</td>
				<td class="field_display">{$lng['service']['interval_fee']}<br />{$arrowcode['o.interval_fee']}</td>
				<td class="field_display">{$lng['service']['interval_length']}<br />{$arrowcode['o.interval_length']}</td>
				<td class="field_display">{$lng['service']['setup_fee']}<br />{$arrowcode['o.setup_fee']}</td>
				<td class="field_display">{$lng['service']['active']}<br />{$arrowcode['o.service_active']}</td>
				<td class="field_display">{$lng['service']['start_date']}<br />{$arrowcode['o.servicestart_date']}</td>
				<td class="field_display">{$lng['service']['lastinvoiced_date']}<br />{$arrowcode['o.lastinvoiced_date']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$otherservices
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="14" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="14"><a href="$filename?action=add&amp;s=$s">{$lng['billing']['other_add']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer