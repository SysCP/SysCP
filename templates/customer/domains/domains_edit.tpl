$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['domains']['subdomain_edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['domains']['domainname']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['domain']}</td>
			</tr>
			<if $alias_check == '0'><tr>
				<td class="main_field_name">{$lng['domains']['aliasdomain']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="tendina_nobordo" name="alias">$domains</select></td>
			</tr></if>
			 <if $settings['panel']['pathedit'] != 'Dropdown'><tr>
				<td class="main_field_name">
					{$lng['panel']['pathorurl']}:<br />
					<font size="1">{$lng['panel']['pathDescription']}</font>
				</td>
				<td class="main_field_display" nowrap="nowrap">{$pathSelect}</td>
			</tr></if>
			<if $settings['panel']['pathedit'] == 'Dropdown'><tr>
				<td class="main_field_name">{$lng['panel']['path']}:</td>
				<td class="main_field_display">{$pathSelect}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['panel']['urloverridespath']}:</td>
				<td class="main_field_display"><input type="text" class="text" name="url" value="{$urlvalue}" size="30" /></td>
			</tr></if>
			<if $result['parentdomainid'] == '0' && $userinfo['subdomains'] != '0' ><tr>
				<td class="main_field_name">{$lng['domains']['wildcarddomain']}</td>
				<td class="main_field_display" nowrap="nowrap">$iswildcarddomain</td>
			</tr></if>
			<if ( $result['subcanemaildomain'] == '1' || $result['subcanemaildomain'] == '2' ) && $result['parentdomainid'] != '0' ><tr>
				<td class="main_field_name">Emaildomain:</td>
				<td class="main_field_display" nowrap="nowrap">$isemaildomain</td>
			</tr></if>
                        <if $settings['system']['use_ssl'] == '1'>
                        <tr>
                                <td class="main_field_name">SSL Redirect:</td>
                                <td class="main_field_display" nowrap="nowrap">$ssl_redirect</td>
                        </tr>
                        </if>
			<tr>
				<td class="main_field_name">{$lng['domain']['openbasedirpath']}:</td>
				<td class="main_field_display" nowrap><select name="openbasedir_path">$openbasedir</select></td>
			</tr>
			<if $settings['system']['customerdns'] == '1' && $result['parentdomainid'] == '0'>
			</table>
			<br />
			<br />
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
				<td class="main_field_confirm" colspan="<if $settings['system']['customerdns'] == '1'>3<else>2</if>"><input type="hidden" name="send" value="send" /><input type="submit" class="bottom" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
	<br />
$footer
