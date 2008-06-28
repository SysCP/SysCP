$header
	<form action="$filename?mode=$mode&amp;s=$s" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="4" ><b><img src="images/title.gif" alt="" />&nbsp;<if $mode === 1>{$lng['billing']['invoices_admin']}<else>{$lng['billing']['invoices']}</if></b></td>
				<td class="maintitle_search_right" colspan="7">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['login']['username']}<br />{$arrowcode['c.loginname']}</td>
				<td class="field_display">{$lng['customer']['name']}<br />{$arrowcode['c.name']}</td>
				<td class="field_display">{$lng['customer']['firstname']}<br />{$arrowcode['c.firstname']}</td>
				<td class="field_display">{$lng['customer']['company']}<br />{$arrowcode['c.company']}</td>
				<td class="field_display">{$lng['billing']['number']}<br />{$arrowcode['i.invoice_number']}</td>
				<td class="field_display">{$lng['billing']['invoice_date']}<br />{$arrowcode['i.invoice_date']}</td>
				<td class="field_display">{$lng['invoice']['state']}<br />{$arrowcode['i.state']}</td>
				<td class="field_display">{$lng['invoice']['state_change']}<br />{$arrowcode['i.state_change']}</td>
				<td class="field_display">{$lng['invoice']['total_fee']}<br />{$arrowcode['i.total_fee']}</td>
				<td class="field_display">{$lng['invoice']['total_fee_taxed']}<br />{$arrowcode['i.total_fee_taxed']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$customers
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="11" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
		</table>
	</form>
	<br />
	<br />
$footer