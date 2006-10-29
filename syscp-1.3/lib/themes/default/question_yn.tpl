	<form action="{$url}" method="post">
		<input type="hidden" name="send" value="send" />
    {foreach from=$params item=item key=key}
    	<input type="hidden" name="{$key}" value="{$item}"/>
    {/foreach}
    	{$hiddenparams}
		<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_60">
			<tr>
				<td class="maintitle" colspan="2">
					<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.globallang.secquestion}
				</td>
			</tr>
			<tr>
				<td class="field_name_border_left">
					{$question}
				</td>
				<td class="field_name" nowrap="nowrap">
					<input type="submit" class="bottom" name="submitbutton" value="{l10n get=SysCP.globallang.yes}" />&nbsp;
					<input type="button" class="bottom" value="{l10n get=SysCP.globallang.no}" onclick="history.back();" />
				</td>
			</tr>
		</table>
	</form>