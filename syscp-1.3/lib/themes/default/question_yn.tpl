    <form action="{$url}" method="post">
    <input type="hidden" name="send" value="send">
    {foreach from=$params item=item key=key}
    	<input type="hidden" name="{$key}" value="{$item}"/>
    {/foreach}
    {$hiddenparams}
    <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
     <tr class="tblhead">
      <td colspan="2" class="title">{l10n get=question.question}</td>
     </tr>
     <tr>
      <td class="maintable">{$question}</td>
      <td class="maintable" nowrap>
      	<input type="submit" name="submitbutton" value="{l10n get=panel.yes}">
      	<input type="button" value="{l10n get=panel.no}" onclick="history.back();"></td>
     </tr>
    </table>
