$header
	<form action="$filename?s=$s" method="post">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_search_left" colspan="4" ><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['other_templates']}</b></td>
				<td class="maintitle_search_right" colspan="4">{$searchcode}</td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['billing']['caption_setup']}&nbsp;&nbsp;{$arrowcode['caption_setup']}</td>
				<td class="field_display">{$lng['billing']['caption_interval']}&nbsp;&nbsp;{$arrowcode['caption_interval']}</td>
				<td class="field_display">{$lng['service']['valid_from']}&nbsp;&nbsp;{$arrowcode['valid_from']}</td>
				<td class="field_display">{$lng['service']['valid_to']}&nbsp;&nbsp;{$arrowcode['valid_to']}</td>
				<td class="field_display">{$lng['service']['interval_fee']}&nbsp;&nbsp;{$arrowcode['interval_fee']}</td>
				<td class="field_display">{$lng['service']['interval_length']}&nbsp;&nbsp;{$arrowcode['interval_length']}</td>
				<td class="field_display">{$lng['service']['setup_fee']}&nbsp;&nbsp;{$arrowcode['setup_fee']}</td>
				<td class="field_display_search">{$sortcode}</td>
			</tr>
			$othertemplates
			<if $pagingcode != ''>
			<tr>
				<td class="field_display_border_left" colspan="8" style=" text-align: center; ">{$pagingcode}</td>
			</tr>
			</if>
			<tr>
				<td class="field_display_border_left" colspan="8"><a href="$filename?action=add&amp;s=$s">{$lng['billing']['other_templates_add']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer