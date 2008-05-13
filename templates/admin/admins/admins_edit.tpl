$header
	<form method="post" action="$filename">
		<input type="hidden" name="s" value="$s" />
		<input type="hidden" name="page" value="$page" />
		<input type="hidden" name="action" value="$action" />
		<input type="hidden" name="id" value="$id" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2"><b><img src="images/title.gif" alt="" />&nbsp;{$lng['admin']['admin_edit']}</b></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['username']}:</td>
				<td class="main_field_display" nowrap="nowrap">{$result['loginname']}</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['name']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="name" value="{$result['name']}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email" value="{$result['email']}" /></td>
			</tr>
            <if $result['adminid'] == $userinfo['userid']><tr>
				<td class="main_field_name" colspan="2">{$lng['error']['youcanteditallfieldsofyourself']}</td>
			</tr></if>
            <if $result['adminid'] != $userinfo['userid']><tr>
				<td class="main_field_name">{$lng['login']['language']}:</td>
				<td class="main_field_display" nowrap="nowrap"><select class="dropdown_noborder" name="def_language">$language_options</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['change_serversettings']}</td>
				<td class="main_field_display" nowrap="nowrap">$change_serversettings</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['customers']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="customers" value="{$result['customers']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['customers_see_all']}</td>
				<td class="main_field_display" nowrap="nowrap">$customers_see_all</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['domains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="domains" value="{$result['domains']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['domains_see_all']}</td>
				<td class="main_field_display" nowrap="nowrap">$domains_see_all</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['diskspace']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="diskspace" value="{$result['diskspace']}" maxlength="6" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['traffic']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="traffic" value="{$result['traffic']}" maxlength="3" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['subdomains']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="subdomains" value="{$result['subdomains']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['emails']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="emails" value="{$result['emails']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['accounts']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_accounts" value="{$result['email_accounts']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['forwarders']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_forwarders" value="{$result['email_forwarders']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['email_quota']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="email_quota" value="{$result['email_quota']}" maxlength="3" />&nbsp;<select class="dropdown_noborder" name="email_quota_type">$quota_type_option</select></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['ftps']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="ftps" value="{$result['ftps']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['tickets']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="tickets" value="{$result['tickets']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['customer']['mysqls']}: *</td>
				<td class="main_field_display" nowrap="nowrap"><input type="text" name="mysqls" value="{$result['mysqls']}" maxlength="9" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['admin']['deactivated_user']}?</td>
				<td class="main_field_display" nowrap="nowrap">$deactivated</td>
			</tr>
			<tr>
				<td class="main_field_name">{$lng['login']['password']} ({$lng['panel']['emptyfornochanges']}):</td>
				<td class="main_field_display" nowrap="nowrap"><input type="password" name="admin_password" value="" /></td>
			</tr></if>
			<tr>
				<td class="main_field_confirm" colspan="2"><input type="hidden" name="send" value="send" /><input class="bottom" type="submit" value="{$lng['panel']['save']}" /></td>
			</tr>
		</table>
	</form>
	<br />
    <table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
        <tr>
            <td class="main_field_name">*: {$lng['admin']['valuemandatory']}</td>
        </tr>
    </table>
    <br />
	<br />
$footer