<tr>
	<td class="field_name_border_left" valign="top">{$row['description']}</td>
	<td class="field_name" valign="top">{$domains}</td>
	<td class="field_name" valign="top">{$row['binary']}</td>
	<td class="field_name" valign="top">{$row['file_extensions']}</td>
	<td class="field_name" valign="top">
		<if (int)$userinfo['change_serversettings'] != 1><a href="$filename?s=$s&amp;page=$page&amp;action=view&amp;id={$row['id']}">{$lng['panel']['view']}</a></if>
		<if (int)$userinfo['change_serversettings'] == 1><a href="$filename?s=$s&amp;page=$page&amp;action=edit&amp;id={$row['id']}">{$lng['panel']['edit']}</a><br/></if>
		<if $row['id'] != 1 && (int)$userinfo['change_serversettings'] == 1><a href="$filename?s=$s&amp;page=$page&amp;action=delete&amp;id={$row['id']}">{$lng['panel']['delete']}</a><br/></if>
	</td>
</tr>