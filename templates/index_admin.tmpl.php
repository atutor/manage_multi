
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table summary="<?php echo _AT("list_of_subsites"); ?>" class="data" rules="cols" align="left" style="width: 95%;">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><?php echo _AT('site_url'); ?></th>
	<th scope="col"><?php echo _AT('created_date'); ?></th>
	<th scope="col"><?php echo _AT('version'); ?></th>
	<th scope="col"><?php echo _AT('status'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="5">
		<input type="submit" name="enable" value="<?php echo _AT('enable'); ?>" />
		<input type="submit" name="disable" value="<?php echo _AT('disable'); ?>" />
		<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
	</td>
</tr>
</tfoot>
<tbody>
<?php //if (mysql_num_rows($result) == 0) { ?>
<?php if (count($rows_subsites) == 0) { ?>
	<tr>
		<td colspan="5"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php } else {
        foreach($rows_subsites as $row){ 
        
		//while ($row = mysql_fetch_assoc($result)): 
		?>
			<tr class="AT_subsites_row">
				<td><input type="radio" name="site_url" value="<?php echo $row['site_url']; ?>" id="<?php echo $row['site_url']; ?>" /></td>
				<td><?php echo $row['site_url']; ?></td>
				<td><?php echo $row['created_date']; ?></td>
				<td><?php echo $row['version']; ?></td>
				<td><?php
					if($row['enabled']){
						echo _AT('enabled');
					}else{
						echo '<span style="font-weight: bold;">' . _AT('disabled') . '</span>';
					}
				?></td>
			</tr>
		<?php 
		}
		//endwhile; 
		?>
	<?php } ?>
</tbody>
</table>
</form>
