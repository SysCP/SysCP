$header
	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
		<tr>
			<td colspan="2" class="maintitle"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['ressourcedetails']}</b></td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['customers']}:</td>
			<td class="field_display">{$overview['number_customers']} ({$userinfo['customers']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['domains']}:</td>
			<td class="field_display">{$overview['number_domains']} ({$userinfo['domains']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['diskspace']}:</td>
			<td class="field_display">{$overview['diskspace_used']} ({$userinfo['diskspace_used']}/{$userinfo['diskspace']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['traffic']}:</td>
			<td class="field_display">{$overview['traffic_used']} ({$userinfo['traffic_used']}/{$userinfo['traffic']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['mysqls']}:</td>
			<td class="field_display">{$overview['mysqls_used']} ({$userinfo['mysqls_used']}/{$userinfo['mysqls']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['emails']}:</td>
			<td class="field_display">{$overview['emails_used']} ({$userinfo['emails_used']}/{$userinfo['emails']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['accounts']}:</td>
			<td class="field_display">{$overview['email_accounts_used']} ({$userinfo['email_accounts_used']}/{$userinfo['email_accounts']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['forwarders']}:</td>
			<td class="field_display">{$overview['email_forwarders_used']} ({$userinfo['email_forwarders_used']}/{$userinfo['email_forwarders']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['ftps']}:</td>
			<td class="field_display">{$overview['ftps_used']} ({$userinfo['ftps_used']}/{$userinfo['ftps']})</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['customer']['subdomains']}:</td>
			<td class="field_display">{$overview['subdomains_used']} ({$userinfo['subdomains_used']}/{$userinfo['subdomains']})</td>
		</tr>
		<tr>
			<td colspan="2" class="maintitle"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['systemdetails']}</b></td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['serversoftware']}:</td>
			<td class="field_display">{$_SERVER['SERVER_SOFTWARE']}</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['phpversion']}:</td>
			<td class="field_display">$phpversion</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['phpmemorylimit']}:</td>
			<td class="field_display">$phpmemorylimit</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['mysqlserverversion']}:</td>
			<td class="field_display">$mysqlserverversion</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['mysqlclientversion']}:</td>
			<td class="field_display">$mysqlclientversion</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['webserverinterface']}:</td>
			<td class="field_display">$webserverinterface</td>
		</tr>
		<tr>
			<td colspan="2" class="maintitle"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['syscpdetails']}</b></td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['cronlastrun']}:</td>
			<td class="field_display">$cronlastrun</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['installedversion']}:</td>
			<td class="field_display">$version</td>
		</tr>
		<tr>
			<td class="field_name_border_left">{$lng['admin']['latestversion']}:</td>
			<td class="field_display"><a href="$lookfornewversion_link">$lookfornewversion_lable</a></td>
		</tr>
<if $lookfornewversion_addinfo != ''>
		<tr>
			<td class="field_name_border_left" colspan="2">$lookfornewversion_addinfo</td>
		</tr>
</if>
	</table>
	<br />
	<br />
$footer