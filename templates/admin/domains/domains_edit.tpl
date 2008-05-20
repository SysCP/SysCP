$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['domain_edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['customer']}:</td>
				<td class="main_field_display" nowrap="nowrap"><if $result['name'] != '' && $result['firstname'] != ''>{$result['name']}, {$result['firstname']}</if><if $result['name'] != '' && $result['firstname'] != '' && $result['company'] != ''> | </if><if $result['company'] != ''>{$result['company']}</if> ({$result['loginname']})</td>
			</tr>
			<tr>
				<td class="main_field_name">Domain:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['domain']}</td>
			</tr>
			<if $alias_check == '0'>
			<tr>
				<td class="main_field_name">{$lng['domains']['aliasdomain']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="alias">$domains</select></td>
			</tr>
			</if>
			<if $userinfo['change_serversettings'] == '1'>
			<tr>
				<td class="main_field_name">DocumentRoot:<br />({$lng['panel']['emptyfordefault']})</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="documentroot" value="{$result['documentroot']}" size="60" /></td>
			</tr>
			<tr>
				<td class="main_field_name">IP/Port:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ipandport">$ipsandports</select></td>
			</tr>
			<tr>
				<td class="main_field_name">Nameserver:</td>
				<td class="main_field_display" nowrap="nowrap">$isbinddomain</td>
			</tr>
			<tr>
				<td class="main_field_name">Zonefile:<br />({$lng['panel']['emptyfordefault']})</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" class="text" name="zonefile" value="{$result['zonefile']}" size="60" /></td>
			</tr>
			</if>
			<if $settings['system']['use_ssl'] == 1>
			<tr>
				<td class="main_field_name">SSL:</td>
				<td class="main_field_display" nowrap="nowrap">$ssl</td>
			</tr>
			<tr>
				<td class="main_field_name">SSL Redirect:</td>
				<td class="main_field_display" nowrap="nowrap">$ssl_redirect</td>
			</tr>
			<tr>
				<td class="main_field_name">SSL IP/Port:</td>
				<if $show_ssl_ipsandports == 1>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="ssl_ipandport">$ssl_ipsandports</select></td>
				<else>
				<td class="main_field_display" nowrap="nowrap">{$lng['panel']['nosslipsavailable']}</td>
				</if>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['wwwserveralias']}:</td>
				<td class="main_field_display" nowrap="nowrap">$wwwserveralias</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['admin']['emaildomain']}:</td>
				<td class="main_field_display" nowrap="nowrap">$isemaildomain</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['email_only']}:</td>
				<td class="main_field_display" nowrap="nowrap">$isemail_only</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['subdomainforemail']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="subcanemaildomain">$subcanemaildomain</select></td>
			</tr>
			<if $settings['dkim']['use_dkim'] == '1'>
 			<tr>
				<td class="main_field_name">DomainKeys:</td>
				<td class="main_field_display" nowrap="nowrap">$dkim</td>
			</tr>
			</if>
			<tr>
				<td class="main_field_name">{$lng['admin']['domain_edit']}:</td>
				<td class="main_field_display" nowrap="nowrap">$caneditdomain</td>
			</tr>
			<if $userinfo['change_serversettings'] == '1' || $userinfo['caneditphpsettings'] == '1'>
			<tr>
				<td class="main_field_name">OpenBasedir:</td>
				<td class="main_field_display" nowrap="nowrap">$openbasedir</td>
			</tr>
			<tr>
				<td class="main_field_name">Safemode:</td>
				<td class="main_field_display" nowrap="nowrap">$safemode</td>
			</tr>
			</if>
			<if $userinfo['change_serversettings'] == '1'>
			<tr>
				<td class="main_field_name">Speciallogfile:</td>
				<td class="main_field_display" nowrap="nowrap"><b>$speciallogfile</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['ownvhostsettings']}:</td>
				<td class="main_field_display" nowrap="nowrap"><textarea class="textarea_border" rows="12" cols="60" name="specialsettings">{$result['specialsettings']}</textarea></td>
			</tr>
			</if>
		<if $settings['system']['userdns'] == '1'>
		</table>
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td class="maintitle" colspan="3"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['domain_dns_settings']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['dns']['destinationip']}:</td>
				<td class="main_field_name"><input type="radio" name="dns_destip_type" value="0" {$dns_destip_type_0_checked} />&nbsp;{$lng['dns']['standardip']}</td>
				<td class="main_field_name" nowrap="nowrap">
					<table border="0" style="text-align: left;">
						<tr>
							<td><input type="radio" name="dns_destip_type" value="1" {$dns_destip_type_1_checked} />&nbsp;{$lng['dns']['a_record']}</td>
						</tr>
						<tr>
							<td>IPv4:&nbsp;<input type="text" class="text" name="dns_destinationipv4" value="{$dns_destinationipv4}" size="39" /></td>
						</tr>
						<tr>
							<td>IPv6:&nbsp;<input type="text" class="text" name="dns_destinationipv6" value="{$dns_destinationipv6}" size="39" /></td>
						</tr>
						<tr>
							<td><input type="radio" name="dns_destip_type" value="2" {$dns_destip_type_2_checked} />&nbsp;{$lng['dns']['cname_record']}</td>
						</tr>
						<tr>
							<td><input type="text" class="text" name="dns_destinationcname" value="{$dns_destinationcname}" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['dns']['mxrecords']}:</td>
				<td class="main_field_name"><input type="radio" name="dns_destmx_type" value="0" {$dns_destmx_type_0_checked} />&nbsp;{$lng['dns']['standardmx']}</td>
				<td class="main_field_name" nowrap="nowrap">
					<table border="0" style="text-align: left;">
						<tr>
							<td><input type="radio" name="dns_destmx_type" value="1" {$dns_destmx_type_1_checked} />&nbsp;{$lng['dns']['mxconfig']}</td>
						</tr>
						<tr>
							<td>{$lng['dns']['priority10']}:&nbsp;<input type="text" class="text" name="dns_mxentry10" value="{$dns_mxentry10}" /></td>
						</tr>
						<tr>
							<td>{$lng['dns']['priority20']}:&nbsp;<input type="text" class="text" name="dns_mxentry20" value="{$dns_mxentry20}" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['dns']['txtrecords']}:</td>
				<td class="main_field_name">{$lng['dns']['txtexample']}</td>
				<td class="main_field_display"><textarea class="textarea_noborder" rows="12" cols="60" name="dns_txtrecords">{$dns_txtrecords}</textarea></td>
			</tr>
		</if>
			<tr>
				<td class="main_field_confirm" colspan="<if $settings['system']['userdns'] == '1'>3<else>2</if>"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer