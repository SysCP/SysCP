$header
	<form action="$filename?s=$s" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle_search_left" colspan="2" ><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['taxclassesnrates']}</b></td>
				<td class="maintitle_search_right" colspan="2">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['billing']['taxclass']}&nbsp;&nbsp;{$arrowcode['c.classname']}</td>
				<td class="field_display">{$lng['billing']['taxrate']}&nbsp;&nbsp;{$arrowcode['r.taxrate']}</td>
				<td class="field_display">{$lng['service']['valid_from']}&nbsp;&nbsp;{$arrowcode['r.valid_from']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$taxrates
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="4" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="4"><a href="$filename?action=add&amp;s=$s">{$lng['billing']['taxrate_add']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer