$header
    <form action="$yesfile" method="post">
    <input type="hidden" name="s" value="{$config->get('env.s')}">
    <input type="hidden" name="send" value="send">
    $hiddenparams
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr class="tblhead">
      <td colspan="2" class="title">{$lng['question']['question']}</td>
     </tr>
     <tr>
      <td class="maintable">$text</font></td><td class="maintable" nowrap><input type="submit" name="submitbutton" value="{$lng['panel']['yes']}"> <input type="button" value="{$lng['panel']['no']}" onclick="history.back();"></td>
     </tr>
    </table>
$footer