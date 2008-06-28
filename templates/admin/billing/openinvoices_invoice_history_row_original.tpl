<tr>
	<td class="field_name"><if $history_row['quantity'] != '1'>{$history_row['quantity']} x </if>{$history_row['caption']}</td>
	<td class="field_name">{$history_row['interval']}</td>
	<td class="field_name">{$history_row['total_fee']}</td>
	<td class="field_name">{$history_row['tax']}</td>
	<td class="field_name">{$history_row['taxrate_percent']}%</td>
	<td class="field_name">{$history_row['total_fee_taxed']}</td>
	<td class="field_name">{$lng['invoice']['original_value']}</td>
</tr>
