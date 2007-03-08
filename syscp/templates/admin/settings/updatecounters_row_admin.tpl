		<tr>
			<td class="main_field_name"><b><if $admin['adminid'] != $userinfo['userid']><a href="$filename?s=$s&amp;page=$page&amp;action=su&amp;id={$admin['adminid']}" target="_blank">{$admin['loginname']}</a></if><if $admin['adminid'] == $userinfo['userid']>{$admin['loginname']}</if>:</b> 
				{$lng['admin']['customers']}: <b>{$admin['customers_used']} -&gt; {$admin['customers_used_new']}</b> ||
				{$lng['customer']['domains']}: <b>{$admin['domains_used']} -&gt; {$admin['domains_used_new']}</b> ||
				{$lng['customer']['diskspace']}: <b>{$admin['diskspace_used']} -&gt; {$admin['diskspace_used_new']}</b> ||
				{$lng['customer']['traffic']}: <b>{$admin['traffic_used']} -&gt; {$admin['traffic_used_new']}</b> ||
				{$lng['customer']['mysqls']}: <b>{$admin['mysqls_used']} -&gt; {$admin['mysqls_used_new']}</b> ||
				{$lng['customer']['emails']}: <b>{$admin['emails_used']} -&gt; {$admin['emails_used_new']}</b> ||
				{$lng['customer']['accounts']}: <b>{$admin['email_accounts_used']} -&gt; {$admin['email_accounts_used_new']}</b> ||
				{$lng['customer']['forwarders']}: <b>{$admin['email_forwarders_used']} -&gt; {$admin['email_forwarders_used_new']}</b> ||
				{$lng['customer']['ftps']}: <b>{$admin['ftps_used']} -&gt; {$admin['ftps_used_new']}</b> ||
				{$lng['customer']['subdomains']}: <b>{$admin['subdomains_used']} -&gt; {$admin['subdomains_used_new']}</b>
			</td>
		</tr>
