$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="10" class="maintitle"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['customers']}</b></td>
		</tr>
		<if ($userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1') && 15 < $userinfo['customers_used']>
		<tr>
			<td colspan="10" class="field_display_border_left"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['customer_add']}</a></td>
		</tr>
		</if>
		<tr>
			<td class="field_display_border_left">ID&nbsp;&nbsp;<a href="admin_customers.php?page=customers&amp;sortby=loginname&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_customers.php?page=customers&amp;sortby=loginname&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<if $userinfo['customers_see_all']>
			<td class="field_display">{$lng['admin']['admin']}&nbsp;&nbsp;<a href="admin_customers.php?page=customers&amp;sortby=adminid&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_customers.php?page=customers&amp;sortby=adminid&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			</if>
			<td class="field_display">{$lng['customer']['name']}</td>
			<td class="field_display">Domains</td>
			<td class="field_display">Space Traffic</td>
			<td class="field_display">MySQL<br />FTP</td>
			<td class="field_display">eMails<br />Subdomains</td>
			<td class="field_display">Accounts<br />Forwarders</td>
			<td class="field_display">Active&nbsp;&nbsp;<a href="admin_customers.php?page=customers&amp;sortby=deactivated&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_customers.php?page=customers&amp;sortby=deactivated&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">&nbsp;</td>
		</tr>
		$customers
		<if 0 < $pages>
		<tr>
			<td colspan="10" class="field_display">{$paging}</td>
		</tr>
		</if>
		<if $userinfo['customers_used'] < $userinfo['customers'] || $userinfo['customers'] == '-1'>
		<tr>
			<td colspan="10" class="field_display_border_left"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['customer_add']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer