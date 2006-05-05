$header
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
    <tr>
     <td colspan="4" class="title">{$lng['admin']['ipsandports']['ipsandports']}</td>
    </tr>
    <tr>
     <td class="maintable" colspan="4" nowrap>
      <form method="post" action="{$config->get('Env.filename')}">
      <input type="hidden" name="s" value="{$config->get('env.s')}">
      <input type="hidden" name="page" value="{$config->get('env.page')}">
      <input type="hidden" name="action" value="{$config->get('env.action')}">
      {$lng['admin']['ipsandports']['default']}:
      <select name="defaultipandport">$ipsandports_default</select>
      <input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}">
      </form>
     </td>
    </tr>
    <tr>
     <td class="maintable">ID</td>
     <td class="maintable">{$lng['admin']['ipsandports']['ipandport']}</td>
     <td class="maintable" colspan="2">&nbsp;</td>
    </tr>
    $ipsandports
    <tr>
     <td class="maintable" colspan="4"><a href="{$config->get('env.filename')}?page={$config->get('env.page')}&action=add&s={$config->get('env.s')}">{$lng['admin']['ipsandports']['add']}</a></td>
    </tr>
   </table>
$footer