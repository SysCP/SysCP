<tr class="" onmouseover="this.className='RowOverSelected';" onmouseout="this.className='';">
	<td class="field_name_border_left">{$row['email_full']}</td>
	<td class="field_name">{$row['destination']}</td>
	<td class="field_name"><if $row['popaccountid'] != 0>{$lng['panel']['yes']}</if><if $row['popaccountid'] == 0>{$lng['panel']['no']}</if></td>
	<td class="field_name"><if $row['iscatchall'] != 0>{$lng['panel']['yes']}</if><if $row['iscatchall'] == 0>{$lng['panel']['no']}</if></td>
	<td class="field_name"><if $row['quota'] != 0>$quota $quota_type</if><if $row['quota'] == 0>{$lng['emails']['noquota']}</if></td>
	<td class="field_name"><a href="$filename?page={$page}&amp;action=edit&amp;id={$row['id']}&amp;s=$s">{$lng['panel']['edit']}</a></td>
	<td class="field_name"><a href="$filename?page={$page}&amp;action=delete&amp;id={$row['id']}&amp;s=$s">{$lng['panel']['delete']}</a></td>
</tr>
