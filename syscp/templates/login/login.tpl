$header
	<br />
	<br />
	<br />
	<form method="post" action="$filename" name="loginform">
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="3"><b><img src="images/title.gif" alt="" />&nbsp;SysCP&nbsp;-&nbsp;Login</b></td>
			</tr>
			<tr>
				<td rowspan="3" class="field_name_center"><img src="images/login.gif" alt="" /></td>
				<td class="field_name"><font size="-1">{$lng['login']['username']}:</font></td>
				<td class="field_display"><input type="text" name="loginname" value="" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="field_name"><font size="-1">{$lng['login']['password']}:</font></td>
				<td class="field_display"><input type="password" name="password" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="field_name"><font size="-1">{$lng['login']['language']}:</font></td>
				<td class="field_display"><select class="dropdown_noborder" name="language">$language_options</select></td>
			</tr>
			<tr>
				<td class="field_name_center" colspan="3"><input type="hidden" name="send" value="send" /><input type="submit" class="bottom" value="{$lng['login']['login']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
	<br />
	<br />
	<br />
$footer