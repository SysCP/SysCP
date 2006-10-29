	<form method="post" action="{url module=templates action=add}">
		<input type="hidden" name="page" value="{$Config->get('env.page')}" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.templates.template_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.templates.language}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<b>{$lang}</b><input type="hidden" name="language" value="{$lang}" /></td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.templates.action}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="template">
						{html_options options=$template_options}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.templates.subject}: *</td>
				<td class="main_field_display" nowrap="nowrap">
					<input type="text" name="subject" value="" maxlength="255" size="75" />
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.templates.mailbody}: *</td>
				<td class="main_field_display" nowrap="nowrap">
					<textarea class="textarea_noborder" type="text" name="mailbody" rows="20" cols="75"></textarea>
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.save}" />
				</td>
			</tr>
		</table>
		<br/><br/>
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.templates.template_replace_vars}
				</td>
			</tr>
			<tr>
				<td class="field_display_border_left" colspan="2"><b>{l10n get=SysCP.templates.createcustomer}</b></td>
			</tr>
			<tr>
				<td class="field_name_border_left"><i>{literal}{FIRSTNAME}{/literal}</i>:</td>
				<td class="field_name">{l10n get=SysCP.templates.FIRSTNAME}</td>
			</tr>
			<tr>
				<td class="field_name_border_left"><i>{literal}{NAME}{/literal}</i>:</td>
				<td class="field_name">{l10n get=SysCP.templates.NAME}</td>
			</tr>
			<tr>
				<td class="field_name_border_left"><i>{literal}{USERNAME}{/literal}</i>:</td>
				<td class="field_name">{l10n get=SysCP.templates.USERNAME}</td>
			</tr>
			<tr>
				<td class="field_name_border_left"><i>{literal}{PASSWORD}{/literal}</i>:</td>
				<td class="field_name">{l10n get=SysCP.templates.PASSWORD}</td>
			</tr>
			<tr>
				<td class="field_display_border_left" colspan="2"><b>{l10n get=SysCP.templates.createemailaccount}</b></td>
			</tr>
			<tr>
				<td class="field_name_border_left"><i>{literal}{EMAIL}{/literal}</i>:</td>
				<td class="field_name">{l10n get=SysCP.templates.EMAIL}</td>
			</tr>
		</table>
	</form>