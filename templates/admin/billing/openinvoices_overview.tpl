$header
	<form action="$filename?mode=$mode&amp;s=$s" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="3" ><b><img src="images/title.gif" alt="" />&nbsp;<if $mode === 1>{$lng['billing']['openinvoices_admin']}<else>{$lng['billing']['openinvoices']}</if></b></td>
				<td class="maintitle_search_right" colspan="3">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left" nowrap="nowrap">{$lng['login']['username']}&nbsp;{$arrowcode['u.loginname']}<if $mode !== 1><br />{$lng['admin']['admin']}&nbsp;{$arrowcode['a.loginname']}</if></td>
				<td class="field_display" nowrap="nowrap">{$lng['customer']['name']}&nbsp;{$arrowcode['u.name']}&nbsp;{$lng['customer']['firstname']}&nbsp;{$arrowcode['u.firstname']}<br />{$lng['customer']['company']}&nbsp;{$arrowcode['u.company']}</td>
				<td class="field_display" nowrap="nowrap">{$lng['customer']['contract_number']}&nbsp;{$arrowcode['u.contract_number']}<br />{$lng['customer']['contract_date']}&nbsp;{$arrowcode['u.contract_date']}</td>
				<td class="field_display" nowrap="nowrap">{$lng['service']['start_date']}&nbsp;{$arrowcode['u.servicestart_date']}<br />{$lng['service']['lastinvoiced_date']}&nbsp;{$arrowcode['u.lastinvoiced_date']}</td>
				<td class="field_display" nowrap="nowrap">{$lng['billing']['invoice_fee']}&nbsp;{$arrowcode['u.invoice_fee']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$users
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="10" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="10"><a href="$filename?page=$page&amp;action=cacheinvoicefees&amp;mode=$mode&amp;s=$s">{$lng['billing']['cacheinvoicefees']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer