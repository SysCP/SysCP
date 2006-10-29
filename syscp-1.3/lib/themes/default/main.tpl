<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type" />
	<link rel="stylesheet" href="{$imagedir}main.css" type="text/css" />
	<title>SysCP</title>
</head>
<body style="margin: 0; padding: 0;" onload="document.loginform.loginname.focus()">
	<!--
	    We request you retain the full copyright notice below including the link to www.syscp.org.
	    This not only gives respect to the large amount of time given freely by the developers
	    but also helps build interest, traffic and use of SysCP. If you refuse
	    to include even this then support on our forums may be affected.
	    The SysCP Team : 2003-2006
	// -->
	<!--
		Templates by Luca Piona (info@havanastudio.ch) and Luca Longinotti (chtekk@gentoo.org)
	// -->
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="800"><img src="{$imagedir}header.gif" width="800" height="90" alt="" /></td>
			<td class="header">&nbsp;</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="240" valign="top" bgcolor="#EBECF5">
			{foreach from=$navigation item=item}
				<br />
				<table cellspacing="0" cellpadding="0" border="0" width="200" align="center">
					<tr>
						<td class="section">
							<b>&nbsp;&nbsp;<img src="{$imagedir}title.gif" alt="" />&nbsp;
								{if $item.isLink}
								<a href="{$item.url}" target="{$item.target}">{$item.lang}</a>
								{else}
								{$item.lang}
								{/if}
							</b>
						</td>
					</tr>
					<tr>
						<td class="subsection">
						<br />
						{foreach from=$item.childs item=subitem}
							&nbsp;&nbsp;<img src="{$imagedir}ball.gif" alt="" />&nbsp;<a href="{$subitem.url}" target="{$subitem.target}">{$subitem.lang}</a>
							<br />
						{/foreach}
						</td>
					</tr>
					<tr>
						<td class="endsection">&nbsp;</td>
					</tr>
				</table>
			{/foreach}
				<br />
			</td>
			<td width="15" class="line_shadow">&nbsp;</td>
			<td valign="top" bgcolor="#FFFFFF">
				<br />
				<br />
{include file=$body_template}
				<br />
				<br />
			</td>
		</tr>
	</table>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<td width="100%" class="footer">
				<br />SysCP {$Config->get('env.version')} &copy; 2003-2006 by <a href="http://www.syscp.org/" target="_blank">the SysCP Team</a>
				<br />&nbsp;
				<br /><a href="http://validator.w3.org/check?uri=referer" target="_blank">
					<img style="border:0;width:80px;height:15px" src="{$imagedir}valid-xhtml10.gif" alt="Valid XHTML 1.0 Transitional" border="0" />
				</a>&nbsp;&nbsp;
				<a href="http://jigsaw.w3.org/css-validator/" target="_blank">
					<img style="border:0;width:80px;height:15px" src="{$imagedir}valid-css.gif" alt="Valid CSS!" border="0" />
				</a>
				<br />&nbsp;
			</td>
		</tr>
	</table>
    {if $Config->get('env.debug') == "true"}
    {debug}
    {/if}
</body>
</html>