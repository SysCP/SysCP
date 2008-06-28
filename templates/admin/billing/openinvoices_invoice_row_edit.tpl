<tr>
	<td class="field_name_border_left">{$count}</td>
	<td class="field_name"><input type="text" name="quantity" value="{$row['quantity']}" size="2" /> x <input type="text" name="caption" value="{$row['description']['caption']}" /></td>
	<td class="field_name"><input type="text" name="interval" value="{$row['interval']}" /></td>
	<td class="field_name"><input type="text" name="total_fee" value="{$row['single_fee']}" size="5" /></td>
	<td class="field_name">{$row['tax']}</td>
	<td class="field_name"><input type="text" name="taxrate_percent" value="{$row['taxrate_percent']}" size="4" /> %</td>
	<td class="field_name">{$row['total_fee_taxed']}</td>
	<td class="field_name"><input type="hidden" name="send" value="send" /><input type="hidden" name="key" value="{$row['key']}" /><input type="submit" name="submitbutton" value="{$lng['panel']['save']}" /><a href="$filename?s=$s&amp;page=$page&amp;action=delete&amp;id={$id}&amp;mode=$mode&amp;key={$row['key']}">{$lng['panel']['delete']}</a></td>
</tr>
