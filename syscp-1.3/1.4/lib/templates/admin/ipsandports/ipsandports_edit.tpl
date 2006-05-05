$header
    <form method="post" action="{$config->get('env.filename')}">
     <input type="hidden" name="s" value="{$config->get('env.s')}">
     <input type="hidden" name="page" value="{$config->get('env.page')}">
     <input type="hidden" name="action" value="{$config->get('env.action')}">
     <input type="hidden" name="id" value="{$config->get('env.id')}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['admin']['ipsandports']['edit']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['ipsandports']['ip']}:</td>
       <td class="maintable" nowrap><input type="text" name="ip" value="{$result['ip']}" size="15" /></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['ipsandports']['port']}:</td>
       <td class="maintable" nowrap><input type="text" name="port" value="{$result['port']}" size="5" /></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
    </form>
$footer