	<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable_40">
		<tr>
			<td class="maintitle">
				<img src="{$imagedir}title.gif" alt="" />&nbsp;{l10n get=SysCP.configfiles.serverconfiguration}
			</td>
		</tr>
		{foreach from=$configfiles item=dist_details key=dist_name}
		<tr>
			<td class="field_display_border_left">
				<table cellpadding="5" cellspacing="0" border="0" align="center" class="maintable">
					<tr>
						<td class="maintitle"><img src="{$imagedir}ball.gif" alt="" />&nbsp;{$dist_details.label}</td>
					</tr>
					<tr>
						<td class="field_name_border_left">
							{foreach from=$dist_details.daemons key=daemon_name item=daemon}
							&nbsp;&nbsp;&nbsp;&raquo;&nbsp;
							<a href="{url distribution=$dist_name daemon=$daemon_name module=configfiles action=list}">
								{$daemon.label}
							</a>
							<br />
							{/foreach}
						</td>
					</tr>
				</table>
			</td>
		</tr>
		{/foreach}
	</table>
