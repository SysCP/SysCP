		<tr>
			<td class="main_field_name"><b><if $customer['name'] != '' && $customer['firstname'] != ''>{$customer['name']}, {$customer['firstname']}</if><if $customer['name'] != '' && $customer['firstname'] != '' && $customer['company'] != ''> | </if><if $customer['company'] != ''>{$customer['company']}</if> (<a href="admin_customers.php?s=$s&amp;page=customers&amp;action=su&amp;id={$customer['customerid']}" target="_blank">{$customer['loginname']}</a>):</b> 
				{$lng['customer']['mysqls']}: <b>{$customer['mysqls_used']} -&gt; {$customer['mysqls_used_new']}</b> ||
				{$lng['customer']['emails']}: <b>{$customer['emails_used']} -&gt; {$customer['emails_used_new']}</b> ||
				{$lng['customer']['accounts']}: <b>{$customer['email_accounts_used']} -&gt; {$customer['email_accounts_used_new']}</b> ||
				{$lng['customer']['forwarders']}: <b>{$customer['email_forwarders_used']} -&gt; {$customer['email_forwarders_used_new']}</b> ||
				{$lng['customer']['ftps']}: <b>{$customer['ftps_used']} -&gt; {$customer['ftps_used_new']}</b> ||
				{$lng['customer']['subdomains']}: <b>{$customer['subdomains_used']} -&gt; {$customer['subdomains_used_new']}</b>
			</td>
		</tr>
