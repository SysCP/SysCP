$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="9" class="maintitle"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['admins']}</b></td>
		</tr>
		<tr>
			<td class="field_display_border_left">ID&nbsp;&nbsp;<a href="admin_admins.php?page=admins&amp;sortby=loginname&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_admins.php?page=admins&amp;sortby=loginname&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">{$lng['customer']['name']}&nbsp;&nbsp;<a href="admin_admins.php?page=admins&amp;sortby=name&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_admins.php?page=admins&amp;sortby=name&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">{$lng['admin']['customers']}<br />{$lng['admin']['domains']}</td>
			<td class="field_display">Space<br />Traffic</td>
			<td class="field_display">MySQL<br />FTP</td>
			<td class="field_display">eMails<br />Subdomains</td>
			<td class="field_display">Accounts<br />Forwarders</td>
			<td class="field_display">Active&nbsp;&nbsp;<a href="admin_admins.php?page=admins&amp;sortby=deactivated&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_admins.php?page=admins&amp;sortby=deactivated&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">&nbsp;</td>
		</tr>
		$admins
		<if 0 < $pages>
		<tr>
			<td colspan="9" class="field_display">{$paging}</td>
		</tr>
		</if>
		<tr>
			<td colspan="9" class="field_display_border_left"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['admin_add']}</a></td>
		</tr>
	</table>
	<br />
	<br />
$footer