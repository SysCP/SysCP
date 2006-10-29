	<form method="post" action="{url module=templates action=add}">
		<input type="hidden" name="page" value="{$Config->get('env.page')}" />
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_40">
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.templates.template_add}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.templates.language}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="language">
						{html_options options=$lang_list selected=$User.def_language}
					</select>
				</td>
			</tr>
			<tr>
			<td class="main_field_confirm" colspan="2">
				<input type="hidden" name="prepare" value="prepare" />
				<input class="bottom" type="submit" value="{l10n get=SysCP.globallang.next}" />
			</td>
			</tr>
		</table>
	</form>