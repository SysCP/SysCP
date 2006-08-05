$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="5"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['domains']}</b></td>
		</tr>
		<if ($userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1') && 15 < $userinfo['domains_used']>
		<tr>
			<td class="field_display_border_left" colspan="5"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['domain_add']}</a></td>
		</tr>
		</if>
		<tr>
			<td class="field_display_border_left">Domain&nbsp;&nbsp;<a href="admin_domains.php?page=domains&amp;sortby=domain&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_domains.php?page=domains&amp;sortby=domain&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">{$lng['admin']['ipsandports']['ip']}&nbsp;&nbsp;<a href="admin_domains.php?page=domains&amp;sortby=ip&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_domains.php?page=domains&amp;sortby=ip&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display">{$lng['admin']['customer']}&nbsp;&nbsp;<a href="admin_domains.php?page=domains&amp;sortby=loginname&amp;sortorder=desc&amp;s=$s"><img src="images/order_desc.gif" border="0" alt="" /></a><a href="admin_domains.php?page=domains&amp;sortby=loginname&amp;sortorder=asc&amp;s=$s"><img src="images/order_asc.gif" border="0" alt="" /></a></td>
			<td class="field_display" colspan="2">&nbsp;</td>
		</tr>
		$domains
		<if 0 < $pages>
		<tr>
			<td class="field_display" colspan="5">{$paging}</td>
		</tr>
		</if>
		<if $userinfo['domains_used'] < $userinfo['domains'] || $userinfo['domains'] == '-1'>
		<tr>
			<td class="field_display_border_left" colspan="5"><a href="$filename?page=$page&amp;action=add&amp;s=$s">{$lng['admin']['domain_add']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer