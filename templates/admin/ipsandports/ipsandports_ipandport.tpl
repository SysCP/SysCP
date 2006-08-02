<tr>
	<td class="field_name_border_left"><font size="-1">{$row['id']}</font></td>
	<td class="field_name"><font size="-1">{$row['ip']}:{$row['port']}</font></td>
	<td class="field_name"><a href="$filename?s=$s&amp;page=$page&amp;action=edit&amp;id={$row['id']}">{$lng['panel']['edit']}</a></td>
	<td class="field_name"><a href="$filename?s=$s&amp;page=$page&amp;action=delete&amp;id={$row['id']}">{$lng['panel']['delete']}</a></td>
</tr>
