    <form method="post" action="{url module=domains action=add}">
     <table cellpadding="3" cellspacing="1" border="0" align="center" class="maintable">
      <tr>
       <td colspan="2" class="title">{l10n get=admin.domain_add}</td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=admin.customer}:</td>
       <td class="maintable" nowrap><select name="customerid">
       {html_options options=$customers}</select></td>
      </tr>
      <tr>
       <td class="maintable">Domain:</td>
       <td class="maintable" nowrap><input type="text" name="domain" value="" size="60"></td>
      </tr>
      <tr>
       <td class="maintable">{l10n get=domains.aliasdomain}:</td>
       <td class="maintable" nowrap><select name="alias">
       {html_options options=$domains}</select></td>
      </tr>
      {if $User.change_serversettings == '1'}<tr>
       <td class="maintable" nowrap>DocumentRoot:<font size="-2"><br />({l10n get=panel.emptyfordefault})</td>
       <td class="maintable" nowrap><input type="text" name="documentroot" value="" size="60"></td>
      </tr>
      <tr>
       <td class="maintable" nowrap>IP/Port:</td>
       <td class="maintable" nowrap><select name="ipandport">
       {html_options options=$ipsandports selected=$ipsandports_sel}</select>
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>Nameserver:</td>
       <td class="maintable" nowrap>
       {html_radios name="isbinddomain" options=$isbinddomain selected=$isbinddomain_sel}
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>Zonefile:<font size="-2"><br />({l10n get=panel.emptyfordefault})</td>
       <td class="maintable" nowrap><input type="text" name="zonefile" value="" size="60"></td>
      </tr>{/if}
      <tr>
       <td class="maintable" nowrap>Emaildomain:</td>
       <td class="maintable" nowrap>
       {html_radios name="isemaildomain" options=$isemaildomain selected=$isemaildomain_sel}
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{l10n get=admin.subdomainforemail}:</td>
       <td class="maintable" nowrap>
       {html_radios name="subcanemaildomain" options=$subcanemaildomain selected=$subcanemaildomain_sel}
      </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>{l10n get=admin.domain_edit}:</td>
       <td class="maintable" nowrap>
       {html_radios name="caneditdomain" options=$caneditdomain selected=$caneditdomain_sel}
       </td>
      </tr>
      {if $User.change_serversettings == '1'}<tr>
       <td class="maintable" nowrap>OpenBasedir:</td>
       <td class="maintable" nowrap>
       {html_radios name="openbasedir" options=$openbasedir selected=$openbasedir_sel}
       </td>
      </tr>
      <tr>
       <td class="maintable" nowrap>Safemode:</td>
       <td class="maintable" nowrap>
       {html_radios name="safemode" options=$safemode selected=$safemode_sel}
       </td>
      </tr>
      <tr>
        <td class="maintable" nowrap>
          Apache Access Logfile:<br/>
          <font size="-2">({l10n get=panel.emptyfordefault})</font>
        </td>
        <td class="maintable" nowrap>
          <input type="text" name="access_log" value="" size="60"/>
        </td>
      </tr>
      <tr>
        <td class="maintable" nowrap>
          Apache Error Logfile:<br/>
          <font size="-2">({l10n get=panel.emptyfordefault})</font>
        </td>
        <td class="maintable" nowrap>
          <input type="text" name="error_log" value="" size="60"/>
        </td>
      </tr>
<!--      <tr>
       <td class="maintable" nowrap>Speciallogfile:</td>
       <td class="maintable" nowrap>
       {html_radios name="speciallogfile" options=$speciallogfile selected=$speciallogfile_sel}
       </td>
      </tr> -->
      <tr>
       <td class="maintable" nowrap>{l10n get=admin.ownvhostsettings}:</td>
       <td class="maintable" nowrap><textarea rows="12" cols="60" name="specialsettings"></textarea></td>
      </tr>{/if}
      <tr>
       <td class="maintable" colspan="2" align="right"><input type="hidden" name="send" value="send"><input type="submit" value="{l10n get=panel.save}"></td>
      </tr>
     </table>
    </form>
