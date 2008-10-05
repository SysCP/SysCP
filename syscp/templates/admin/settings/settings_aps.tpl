<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
	<tr>
		<td class="maintitle_apply_left">
			<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['aps']}</b>
		</td>
		<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
		</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['activate_aps']['title']}:</b><br />{$lng['aps']['activate_aps']['description']}</td>
		<td class="main_field_display" nowrap="nowrap">$aps_activate_aps</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['packages_per_page']['title']}:</b><br />{$lng['aps']['packages_per_page']['description']}</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" name="items_per_page" size="60" value="{$settings['aps']['items_per_page']}"/></td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['aps']['upload_fields']['title']}:</b><br />{$lng['aps']['upload_fields']['description']}</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" name="upload_fields" size="60" value="{$settings['aps']['upload_fields']}"/></td>
	</tr>
	<tr>
		<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
			<if $_part == 'aps'>
				<input type="hidden" name="part" value="aps" />
			</if>
			<input class="bottom" type="reset" value="{$lng['panel']['reset']}" />&nbsp;<input class="bottom" type="submit" value="{$lng['panel']['save']}" />
		</td>
	</tr>
</table>