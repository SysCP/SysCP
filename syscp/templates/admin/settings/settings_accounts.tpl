$header
	<form method="post" action="$filename">
		<input type="hidden" name="send" value="send" />
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle_apply_left">
					<b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['accountsettings']}</b>
				</td>
				<td class="maintitle_apply_right" nowrap="nowrap">
                                        <a href="$filename?page=settings&amp;s=$s">{$lng['panel']['backtooverview']}</a>
				</td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['session_timeout']['title']}:</b><br />{$lng['serversettings']['session_timeout']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="session_sessiontimeout" value="{$settings['session']['sessiontimeout']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['maxloginattempts']['title']}:</b><br />{$lng['serversettings']['maxloginattempts']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="login_maxloginattempts" value="{$settings['login']['maxloginattempts']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['deactivatetime']['title']}:</b><br />{$lng['serversettings']['deactivatetime']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="login_deactivatetime" value="{$settings['login']['deactivatetime']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['accountprefix']['title']}:</b><br />{$lng['serversettings']['accountprefix']['description']}</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_accountprefix" value="{$settings['customer']['accountprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['mysqlprefix']['title']}:</b><br />{$lng['serversettings']['mysqlprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['mysqlprefix']}Y)</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_mysqlprefix" value="{$settings['customer']['mysqlprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ftpprefix']['title']}:</b><br />{$lng['serversettings']['ftpprefix']['description']} ({$settings['customer']['accountprefix']}X{$settings['customer']['ftpprefix']}Y)</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customer_ftpprefix" value="{$settings['customer']['ftpprefix']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name"><b>{$lng['serversettings']['ftpdomain']['title']}:</b><br />{$lng['serversettings']['ftpdomain']['description']}</td>
				<td class="main_field_display" nowrap="nowrap">{$ftpatdomain}</td>
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
