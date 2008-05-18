$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ticketsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['enable']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">$ticketsystemenabled</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['noreply_email']}:</b><br />{$lng['serversettings']['ticket']['noreply_email_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_noreply_email" value="{$settings['ticket']['noreply_email']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['noreply_name']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_noreply_name" value="{$settings['ticket']['noreply_name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['reset_cycle']}:</b><br />{$lng['serversettings']['ticket']['reset_cycle_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ticket_reset_cycle">{$ticket_reset_cycle}</select></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['concurrentlyopen']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_concurrently_open" value="{$settings['ticket']['concurrently_open']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['archiving_days']}:</b></td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_archiving_days" value="{$settings['ticket']['archiving_days']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_all']}:</b><br />{$lng['serversettings']['ticket']['worktime_all_desc']}</td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_all}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_begin']}:</b><br />{$lng['serversettings']['ticket']['worktime_begin_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_worktime_begin" value="{$settings['ticket']['worktime_begin']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_end']}:</b><br />{$lng['serversettings']['ticket']['worktime_end_desc']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ticket_worktime_end" value="{$settings['ticket']['worktime_end']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_sat']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_sat}</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ticket']['worktime_sun']}:</b></td>
				<td class="main_field_display" nowrap="nowrap">{$ticket_worktime_sun}</td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer
