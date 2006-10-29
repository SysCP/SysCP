	<form method="post" action="{url module=index action=changeLanguage}">
		<table cellpadding="5" cellspacing="4" border="0" align="center" class="maintable_60">
			<tr>
				<td class="none" rowspan="4">
					<img src="{$imagedir}changelanguage.gif" alt="" />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="maintitle">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.index.changelanguage}
				</td>
			</tr>
			<tr>
				<td class="main_field_name">{l10n get=SysCP.index.language}:</td>
				<td class="main_field_display" nowrap="nowrap">
					<select class="dropdown_noborder" name="def_language">
						{html_options options=$lang_list selected=$User.def_language}
					</select>
				</td>
			</tr>
			<tr>
				<td class="main_field_confirm" colspan="2">
					<input type="hidden" name="send" value="send" />
					<input class="bottom" type="submit" value="{l10n get=SysCP.index.changelanguage}" />
				</td>
			</tr>
		</table>
	</form>