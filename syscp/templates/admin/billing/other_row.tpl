<tr>
	<td class="field_name_border_left">{$row['loginname']}</td>
	<td class="field_name">{$row['name']}</td>
	<td class="field_name">{$row['firstname']}</td>
	<td class="field_name">{$row['company']}</td>
	<td class="field_name">{$row['caption_setup']}</td>
	<td class="field_name">{$row['caption_interval']}</td>
	<td class="field_name">{$row['quantity']}</td>
	<td class="field_name">{$row['interval_fee']}</td>
	<td class="field_name">{$row['interval_length']} {$lng['panel']['intervalfee_type'][$row['interval_type']]}</td>
	<td class="field_name">{$row['setup_fee']}</td>
	<td class="field_name"><if $row['service_active'] == '1'>{$lng['panel']['yes']}<else>{$lng['panel']['no']}</if></td>
	<td class="field_name"><if $row['servicestart_date'] == '0'>{$lng['panel']['never']}<else>{$row['servicestart_date']}</if></td>
	<td class="field_name"><if $row['lastinvoiced_date'] == '0'>{$lng['panel']['never']}<else>{$row['lastinvoiced_date']}</if></td>
	<td class="field_name"><a href="$filename?s=$s&amp;action=edit&amp;id={$row['id']}">{$lng['panel']['edit']}</a> <if $enable_billing_data_edit === true><a href="$filename?s=$s&amp;action=delete&amp;id={$row['id']}">{$lng['panel']['delete']}</a></if></td>
</tr>
