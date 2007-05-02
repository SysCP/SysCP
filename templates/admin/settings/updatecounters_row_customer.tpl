		<tr>
			<td class="main_field_name">
				<b>
					<if $customer['name'] != '' && $customer['firstname'] != ''>{$customer['name']}, {$customer['firstname']}</if><if $customer['name'] != '' && $customer['firstname'] != '' && $customer['company'] != ''> | </if><if $customer['company'] != ''>{$customer['company']}</if> (<a href="admin_customers.php?s=$s&amp;page=customers&amp;action=su&amp;id={$customer['customerid']}" target="_blank">{$customer['loginname']}</a>):
				</b>

				{$lng['customer']['mysqls']}: <span <if $customer['mysqls_used'] == $customer['mysqls_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['mysqls_used']} -&gt; {$customer['mysqls_used_new']}</b></span> ||

				{$lng['customer']['emails']}: <span <if $customer['emails_used'] == $customer['emails_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['emails_used']} -&gt; {$customer['emails_used_new']}</b></span> ||

				{$lng['customer']['accounts']}: <span <if $customer['email_accounts_used'] == $customer['email_accounts_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['email_accounts_used']} -&gt; {$customer['email_accounts_used_new']}</b></span> ||

				{$lng['customer']['forwarders']}: <span <if $customer['email_forwarders_used'] == $customer['email_forwarders_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['email_forwarders_used']} -&gt; {$customer['email_forwarders_used_new']}</b></span> ||

				{$lng['customer']['ftps']}: <span <if $customer['ftps_used'] == $customer['ftps_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['ftps_used']} -&gt; {$customer['ftps_used_new']}</b></span> ||

				{$lng['customer']['subdomains']}: <span <if $customer['subdomains_used'] == $customer['subdomains_used_new']>style="color:green"<else>style="color:red"</if>><b>{$customer['subdomains_used']} -&gt; {$customer['subdomains_used_new']}</b></span>
			</td>
		</tr>
