<html>
<head>
<link href="themes/default/main.css" rel="stylesheet" type="text/css">
<title>SysCP</title>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<!--
    We request you retain the full copyright notice below including the link to www.syscp.org.
    This not only gives respect to the large amount of time given freely by the developers
    but also helps build interest, traffic and use of SysCP. If you refuse
    to include even this then support on our forums may be affected.
    The SysCP Team : 2003-2006
    Template made by Kirill Bauer.
// -->
  <div style="position:absolute; top:10px; right:10px">SysCP {$Config->get('env.version')} &copy; 2003-2006 by <a href="http://www.syscp.org/" target="_blank">the SysCP Team</a></div>
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td width="800"><img src="themes/default/header.gif" width="800" height="89"></td>
      <td background="themes/default/header_r.gif">&nbsp;</td>
    </tr>
  </table>
  <table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
   <tr>
     <td width="240" valign="top" background="themes/default/background_l.gif">
     {foreach from=$navigation item=item}
       <br />
       <table border="0" width="200" cellspacing="0" cellpadding="0" align="center">
         <tr>
           <td background="themes/default/menu_h.gif" height="26" class="menu" align="center">
           {if $item.isLink}
           	<a href="{$item.url}" target="{$item.target}">{$item.lang}</a>
           	{else}
           	{$item.lang}
           	{/if}
           </td>
         </tr><tr>
           <td background="themes/default/menu_m.gif" class="menu">
           {foreach from=$item.childs item=subitem}
           	&nbsp;&nbsp;&nbsp;&raquo; <a href="{$subitem.url}" target="{$subitem.target}">{$subitem.lang}</a><br />
           {/foreach}
</td>
         </tr><tr>
           <td background="themes/default/menu_f.gif" height="11"></td>
         </tr>
       </table>
       {/foreach}

     </td>
     <td background="themes/default/background_m.gif" width="2">&nbsp;</td>
     <td background="themes/default/background_r.gif" class="maintable" valign="top"><br /><br />
     {include file=$body_template}
       </td>
    </tr>
  </table>
</body>
</html>