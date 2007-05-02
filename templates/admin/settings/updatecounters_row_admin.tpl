		<tr>
			<td class="main_field_name">
				<b>
					<if $admin['adminid'] != $userinfo['userid']><a href="admin_admins.php?s=$s&amp;page=admins&amp;action=su&amp;id={$admin['adminid']}" target="_blank">{$admin['loginname']}</a></if><if $admin['adminid'] == $userinfo['userid']>{$admin['loginname']}</if>:
				</b>

				{$lng['admin']['customers']}: <span <if $admin['customers_used'] == $admin['customers_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['customers_used']} -&gt; {$admin['customers_used_new']}</b></span> ||

				{$lng['customer']['domains']}: <span <if $admin['domains_used'] == $admin['domains_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['domains_used']} -&gt; {$admin['domains_used_new']}</b></span> ||

				{$lng['customer']['diskspace']}: <span <if $admin['diskspace_used'] == $admin['diskspace_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['diskspace_used']} -&gt; {$admin['diskspace_used_new']}</b></span> ||

				{$lng['customer']['traffic']}: <span <if $admin['traffic_used'] == $admin['traffic_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['traffic_used']} -&gt; {$admin['traffic_used_new']}</b></span> ||

				{$lng['customer']['mysqls']}: <span <if $admin['mysqls_used'] == $admin['mysqls_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['mysqls_used']} -&gt; {$admin['mysqls_used_new']}</b></span> ||

				{$lng['customer']['emails']}: <span <if $admin['emails_used'] == $admin['emails_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['emails_used']} -&gt; {$admin['emails_used_new']}</b></span> ||

				{$lng['customer']['accounts']}: <span <if $admin['email_accounts_used'] == $admin['email_accounts_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['email_accounts_used']} -&gt; {$admin['email_accounts_used_new']}</b></span> ||

				{$lng['customer']['forwarders']}: <span <if $admin['email_forwarders_used'] == $admin['email_forwarders_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['email_forwarders_used']} -&gt; {$admin['email_forwarders_used_new']}</b></span> ||

				{$lng['customer']['ftps']}: <span <if $admin['ftps_used'] == $admin['ftps_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['ftps_used']} -&gt; {$admin['ftps_used_new']}</b></span> ||

				{$lng['customer']['subdomains']}: <span <if $admin['subdomains_used'] == $admin['subdomains_used_new']>style="color:green"<else>style="color:red"</if>><b>{$admin['subdomains_used']} -&gt; {$admin['subdomains_used_new']}</b></span>
			</td>
		</tr>
