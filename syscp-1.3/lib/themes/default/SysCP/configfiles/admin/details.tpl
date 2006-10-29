	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_60">
		<tr>
			<td class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{$configfiles.$distribution.daemons.$daemon.label}
			</td>
		</tr>
		{if $commands != ''}<tr>
			<td class="field_display_border_left">
				{l10n get=SysCP.configfiles.commands}<br />
				<textarea class="textarea_border" rows="6" cols="70" readonly="readonly">{$commands}</textarea>
			</td>
		</tr>{/if}
		{if $files != ''}<tr>
			<td class="field_display_border_left">
				{l10n get=SysCP.configfiles.files}<br />
				{foreach from=$files item=file}
				<p>
				<b>{$file.filename}:</b>
				<br />
				<textarea class="textarea_border" rows="{if $file.numbrows <= 8 }{$file.numbrows}{else}8{/if}" cols="70" readonly="readonly">{$file.content}</textarea>
				</p>
				{/foreach}
			</td>
		</tr>{/if}
		{if $restart != ''}<tr>
			<td class="field_display_border_left">
				{l10n get=SysCP.configfiles.restart}<br />
				<textarea class="textarea_border" rows="3" cols="70" readonly="readonly">{$restart}</textarea>
			</td>
		</tr>{/if}
	</table>
