<tr>
	<td class="field_name_border_left">{$row['loginname']}</td>
	<td class="field_name">{$row['name']}</td>
	<td class="field_name">{$row['firstname']}</td>
	<td class="field_name">{$row['company']}</td>
	<td class="field_name">{$row['invoice_number']}</td>
	<td class="field_name">{$row['invoice_date']}</td>
	<td class="field_name">{$lng['invoice']['states'][$row['state']]}</td>
	<td class="field_name">{$row['state_change']}</td>
	<td class="field_name">{$row['total_fee']}</td>
	<td class="field_name">{$row['total_fee_taxed']}</td>
	<td class="field_name"><a href="$filename?s=$s&amp;page=$page&amp;action=edit&amp;mode=$mode&amp;id={$row['id']}">{$lng['invoice']['change_state']}</a> <a href="$filename?s=$s&amp;page=$page&amp;action=pdf&amp;mode=$mode&amp;id={$row['id']}" target="_blank">{$lng['invoice']['pdf']}</a> <a href="$filename?s=$s&amp;page=$page&amp;action=reminder&amp;mode=$mode&amp;id={$row['id']}">{$lng['invoice']['create_reminder']}</a></td>
</tr>
