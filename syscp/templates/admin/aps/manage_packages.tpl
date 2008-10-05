<form method="post" action="$filename">
	<input type="hidden" name="s" value="$s" />
	<input type="hidden" name="action" value="$action" />
	<input type="hidden" name="page" value="$page" />

	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td  class="maintitle" colspan="5"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['aps']['managepackages']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left" width="30%">{$lng['aps']['packagenameandversion']}</td>
			<td class="field_display">{$lng['ticket']['status']}</td>
			<td class="field_display" width="7%">{$lng['aps']['lock']}</td>
			<td class="field_display" width="7%">{$lng['aps']['unlock']}</td>
			<td class="field_display" width="7%">{$lng['aps']['remove']}</td>
		</tr>
		$Packages
		<tr>
			<td class="field_display_border_left" colspan="2">{$lng['aps']['allpackages']}</td>
			<td class="field_display" width="7%" style="text-align:center;"><input type="radio" name="all" value="lock"/></td>
			<td class="field_display" width="7%" style="text-align:center;"><input type="radio" name="all" value="unlock"/></td>
			<td class="field_display" width="7%" style="text-align:center;"><input type="radio" name="all" value="remove"/></td>
		</tr>
		<tr>
			<td  class="maintitle_apply_right" colspan="5"><input class="bottom" type="reset" value="{$lng['panel']['reset']}"/>&nbsp;<input class="bottom" type="submit" name="save" value="{$lng['panel']['save']}"/></td>
		</tr>
	</table>
</form>
<br />
<br />
