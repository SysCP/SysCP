$header
    <form method="post" action="$filename">
     <input type="hidden" name="s" value="$s">
     <input type="hidden" name="page" value="$page">
     <input type="hidden" name="action" value="$action">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['admin']['templates']['template_add']}</td>
      </tr>
      <tr>
       <td class="maintable">{$lng['login']['language']}:</td>
       <td class="maintable" nowrap><b>$language</b><input type="hidden" name="language" value="$language"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['action']}:</td>
       <td class="maintable" nowrap><select name="template">$template_options</select></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['subject']}: *</td>
       <td class="maintable" nowrap><input type="text" name="subject" value="" maxlength="255" size="100"></td>
      </tr>
      <tr>
       <td class="maintable">{$lng['admin']['templates']['mailbody']}: *</td>
       <td class="maintable" nowrap><textarea type="text" name="mailbody" rows="20" cols="75"></textarea></td>
      </tr>
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{$lng['panel']['save']}"></td>
      </tr>
     </table>
     <br/><br/>
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{$lng['admin']['templates']['template_replace_vars']}</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2"><b>{$lng['admin']['templates']['createcustomer']}</b></td>
      </tr>
      <tr>
       <td class="maintable"><i>{FIRSTNAME}</i>:</td>
       <td class="maintable">{$lng['admin']['templates']['FIRSTNAME']}</td>
      </tr>
      <tr>
       <td class="maintable"><i>{NAME}</i>:</td>
       <td class="maintable">{$lng['admin']['templates']['NAME']}</td>
      </tr>
      <tr>
       <td class="maintable"><i>{USERNAME}</i>:</td>
       <td class="maintable">{$lng['admin']['templates']['USERNAME']}</td>
      </tr>
      <tr>
       <td class="maintable"><i>{PASSWORD}</i>:</td>
       <td class="maintable">{$lng['admin']['templates']['PASSWORD']}</td>
      </tr>
      <tr>
       <td class="maintable" colspan="2"><b>{$lng['admin']['templates']['pop_success']}</b></td>
      </tr>
      <tr>
       <td class="maintable"><i>{EMAIL}</i>:</td>
       <td class="maintable">{$lng['admin']['templates']['EMAIL']}</td>
      </tr>
     </table>
    </form>
$footer