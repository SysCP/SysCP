<tr>
	<td class="field_name_border_left<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$count}</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>"><if $row['quantity'] != '1'>{$row['quantity']} x </if>{$row['description']['caption']}</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$row['interval']}</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$row['total_fee']}</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$row['tax']}</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$row['taxrate_percent']}%</td>
	<td class="field_name<if isset( $row['deleted'] ) && $row['deleted'] === true>_foggy</if>">{$row['total_fee_taxed']}</td>
	<td class="field_name"><a href="$filename?s=$s&amp;page=$page&amp;action=$action&amp;id={$id}&amp;mode=$mode&amp;editkey={$row['key']}">{$lng['panel']['edit']}</a> <a href="$filename?s=$s&amp;page=$page&amp;action=delete&amp;id={$id}&amp;mode=$mode&amp;key={$row['key']}">{$lng['panel']['delete']}</a></td>
</tr>
