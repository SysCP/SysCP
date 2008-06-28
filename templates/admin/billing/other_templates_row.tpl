<tr>
	<td class="field_name_border_left">{$row['caption_setup']}</td>
	<td class="field_name">{$row['caption_interval']}</td>
	<td class="field_name"><if $row['valid_from'] == '0'>{$lng['panel']['never']}<else>{$row['valid_from']}</if></td>
	<td class="field_name"><if $row['valid_to'] == '0'>{$lng['panel']['never']}<else>{$row['valid_to']}</if></td>
	<td class="field_name">{$row['interval_fee']}</td>
	<td class="field_name">{$row['interval_length']} {$lng['panel']['intervalfee_type'][$row['interval_type']]}</td>
	<td class="field_name">{$row['setup_fee']}</td>
	<td class="field_name"><a href="$filename?s=$s&amp;action=edit&amp;id={$row['templateid']}">{$lng['panel']['edit']}</a> <a href="$filename?s=$s&amp;action=delete&amp;id={$row['templateid']}">{$lng['panel']['delete']}</a></td>
</tr>
