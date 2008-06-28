<tr>
	<td class="field_name_border_left">{$row['classname']}</td>
	<td class="field_name">{$row['taxrate_percent']}%</td>
	<td class="field_name"><if $row['valid_from'] == '0'>{$lng['panel']['never']}<else>{$row['valid_from']}</if></td>
	<td class="field_name"><a href="$filename?s=$s&amp;action=edit&amp;id={$row['taxid']}">{$lng['panel']['edit']}</a> <a href="$filename?s=$s&amp;action=delete&amp;id={$row['taxid']}">{$lng['panel']['delete']}</a></td>
</tr>
