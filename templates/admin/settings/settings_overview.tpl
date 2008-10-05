		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" align="center" colspan="3">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['configfiles']['serverconfiguration']}</b>
					[<a href="$filename?page=overview&amp;part=all&amp;s=$s">{$lng['admin']['configfiles']['overview']}</a>]
				</td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['panelsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=panel&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['accountsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=accounts&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['systemsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=system&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['webserversettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['webserver']}:&nbsp;<select class="dropdown_noborder" name="panel_webserver_selected">$webserver_selected</select></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=webserver&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['statisticsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=statistic&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['mailserversettings']}</b>
 				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=mail&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['nameserversettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap"></td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=nameserver&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['loggersettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$loggingenabled</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['logger']['enabled'] == '1'><a href="$filename?page=overview&amp;part=logging&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['dkimsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$dkimenabled</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['dkim']['use_dkim'] == '1'><a href="$filename?page=overview&amp;part=dkim&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ticketsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$ticketsystemenabled</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['ticket']['enabled'] == '1'><a href="$filename?page=overview&amp;part=ticket&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['sslsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$ssl_enabled</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['system']['use_ssl'] == '1'><a href="$filename?page=overview&amp;part=ssl&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['billingsettings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$billing_activate_billing</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['billing']['activate_billing'] == '1'><a href="$filename?page=overview&amp;part=billing&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['aps']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['activated']}:&nbsp;$aps_activate_aps</td>
				<td class="main_field_display_small" nowrap="nowrap"><if $settings['aps']['aps_active'] == '1'><a href="$filename?page=overview&amp;part=aps&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></if></td>
			</tr>
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['security_settings']}</b>
				</td>
				<td class="main_field_display_small" nowrap="nowrap">{$lng['admin']['expert_settings']}</td>
				<td class="main_field_display_small" nowrap="nowrap"><a href="$filename?page=overview&amp;part=security&amp;s=$s">{$lng['admin']['configfiles']['serverconfiguration']}</a></td>
			</tr>
			<tr>
				<td class="maintitle_apply_right" nowrap="nowrap" colspan="3">
					<input class="bottom" type="reset" value="{$lng['panel']['reset']}" />&nbsp;<input class="bottom" type="submit" value="{$lng['panel']['save']}" />
				</td>
			</tr>
		</table>
