$header
	<form action="$filename" method="post">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="id" value="$id" />
		<input type="hidden" name="mode" value="$mode" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="edit" />
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle" colspan="8" ><b><img src="images/title.gif" alt="" />&nbsp;{$lng['billing']['invoice']}</b></td>
			</tr>
			<tr>
				<td class="field_display_border_left">{$lng['invoice']['header'][0]}</td>
				<td class="field_display">{$lng['invoice']['header'][1]}</td>
				<td class="field_display">{$lng['invoice']['header'][2]}</td>
				<td class="field_display">{$lng['invoice']['header'][3]}</td>
				<td class="field_display">{$lng['invoice']['header'][4]}</td>
				<td class="field_display">{$lng['invoice']['header'][5]}</td>
				<td class="field_display">{$lng['invoice']['header'][6]}</td>
				<td class="field_display"><a href="$filename?s=$s&amp;page=$page&amp;action=reset&amp;id={$id}&amp;mode=$mode">{$lng['panel']['reset']}</a></td>
			</tr>
			$invoice_rows
			<tr>
				<td class="field_display_border_left" colspan="7" align="right">{$lng['invoice']['subtotal']}: {$total_fee}<if $credit_note != 0><br />{$lng['invoice']['credit_note']}: -  {$credit_note}</if><br />{$lng['invoice']['total']}: {$total_fee_taxed}</td>
				<td class="field_display"><a href="$filename?s=$s&amp;page=$page&amp;action=preview&amp;id={$id}&amp;mode=$mode" target="_blank">{$lng['billing']['preview']}</a><br /><a href="$filename?s=$s&amp;page=$page&amp;action=fixinvoice&amp;id={$id}&amp;mode=$mode">{$lng['invoice']['fix']}</a></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer