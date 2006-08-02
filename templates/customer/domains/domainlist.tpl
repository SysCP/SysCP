$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td class="maintitle" colspan="4"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['domains']['domainsettings']}</b></td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['domains']['domainname']}</td>
			<td class="field_name">{$lng['panel']['path']}</td>
			<td class="field_name" colspan="2">&nbsp;</td>
		</tr>
		<if ($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1') && 15 < $domains_count && $parentdomains_count != 0 >
		<tr>
			<td class="field_name_border_left" colspan="4"><a href="$filename?page=domains&amp;action=add&amp;s=$s">{$lng['domains']['subdomain_add']}</a></td>
		</tr>
		</if>
		$domains
		<if 0 < $pages>
		<tr>
			<td colspan="4" class="field_name">{$paging}</td>
		</tr>
		</if>
		<if ($userinfo['subdomains_used'] < $userinfo['subdomains'] || $userinfo['subdomains'] == '-1') && $parentdomains_count != 0 >
		<tr>
			<td class="field_name_border_left" colspan="4"><a href="$filename?page=domains&amp;action=add&amp;s=$s">{$lng['domains']['subdomain_add']}</a></td>
		</tr>
		</if>
	</table>
	<br />
	<br />
$footer