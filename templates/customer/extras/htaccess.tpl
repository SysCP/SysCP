$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="7"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['menue']['extras']['pathoptions']}</b></td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['panel']['path']}</td>
			<td class="field_name">{$lng['extras']['view_directory']}</td>
			<td class="field_name">{$lng['extras']['error404path']}</td>
			<td class="field_name">{$lng['extras']['error403path']}</td>
			<td class="field_name">{$lng['extras']['error500path']}</td>
			<td class="field_name" colspan="2">&nbsp;</td>
		</tr>
		$htaccess
		<tr>
			<td class="field_name_border_left" colspan="7"><a href="$filename?page=htaccess&amp;action=add&amp;s=$s">{$lng['extras']['pathoptions_add']}</a></td>
		</tr>
	</table>
	<br />
	<br />
$footer