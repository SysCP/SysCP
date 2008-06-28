<tr>
	<td class="field_name_border_left">{$row['loginname']}<if $mode !== 1><br />{$row['adminname']}</if></td>
	<td class="field_name">{$row['name']}&nbsp;{$row['firstname']}<br />{$row['company']}</td>
	<td class="field_name">{$row['contract_number']}<br /><if $row['contract_date'] == '0'>{$lng['panel']['never']}<else>{$row['contract_date']}</if></td>
	<td class="field_name"><if $row['servicestart_date'] == '0'>{$lng['panel']['never']}<else>{$row['servicestart_date']}</if><br /><if $row['lastinvoiced_date'] == '0'>{$lng['panel']['never']}<else>{$row['lastinvoiced_date']}</if></td>
	<td class="field_name">{$row['invoice_fee']}</td>
	<td class="field_name"><a href="$filename?s=$s&amp;page=invoice&amp;id={$row['userid']}&amp;mode=$mode">{$lng['panel']['edit']}</a> <a href="$filename?s=$s&amp;page=invoice&amp;action=preview&amp;id={$row['userid']}&amp;mode=$mode" target="_blank">{$lng['billing']['preview']}</a> <a href="$filename?s=$s&amp;page=invoice&amp;action=fixinvoice&amp;id={$row['userid']}&amp;mode=$mode">{$lng['invoice']['fix']}</a></td>
</tr>
