$header
	<br />
	<br />
	<br />
	<form method="post" action="$filename" name="loginform">
		<input type="hidden" name="action" value="$action" />
		<if $message != ''>
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" ><b>{$message}</b></td>
			</tr>
		</table>
		<br />
		<br />
		</if>
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_40">
			<tr>
				<td class="maintitle" colspan="3"><b><img src="images/title.gif" alt="" />&nbsp;SysCP&nbsp;-&nbsp;{$lng['login']['presend']}</b></td>
			</tr>
			<tr>
				<td rowspan="2" class="field_name_center"><img src="images/login.gif" alt="" /></td>
				<td class="field_name"><font size="-1">{$lng['login']['username']}:</font></td>
				<td class="field_display"><input type="text" class="text" name="loginname" value="" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="field_name"><font size="-1">{$lng['login']['email']}:</font></td>
				<td class="field_display"><input type="text" class="text" name="loginemail" /></td>
			</tr>
			<tr>
				<td class="field_name_center" colspan="3"><input type="hidden" name="send" value="send" /><input type="submit" class="bottom" value="{$lng['login']['remind']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
	<br />
	<br />
	<br />
$footer