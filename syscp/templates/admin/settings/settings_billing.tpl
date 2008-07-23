<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
	<tr>
		<td class="maintitle_apply_left">
			<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingsettings']}</b>
		</td>
		<td class="maintitle_apply_right" nowrap="nowrap"><a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
		</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['serversettings']['billing']['activate_billing']['title']}:</b><br />{$lng['serversettings']['billing']['activate_billing']['description']}</td>
		<td class="main_field_display" nowrap="nowrap">$billing_activate_billing</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['serversettings']['billing']['highlight_inactive']['title']}:</b><br />{$lng['serversettings']['billing']['highlight_inactive']['description']}</td>
		<td class="main_field_display" nowrap="nowrap">$billing_highlight_inactive</td>
	</tr>
	<tr>
		<td class="main_field_name"><b>{$lng['serversettings']['billing']['invoicenumber_count']['title']}:</b><br />{$lng['serversettings']['billing']['invoicenumber_count']['description']}</td>
		<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="billing_invoicenumber_count" value="{$settings['billing']['invoicenumber_count']}" /></td>
	</tr>
	<tr>
		<td class="maintitle_apply_right" nowrap="nowrap" colspan="2">
			<if $_part == 'billing'>
				<input type="hidden" name="part" value="billing" />
			</if>
			<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
		</td>
	</tr>
</table>