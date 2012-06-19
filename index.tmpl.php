
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table summary="List of ATutor subsitess" class="data" rules="cols" align="left" style="width: 95%;">
<thead>
<tr>
	<th scope="col">&nbsp;</th>
	<th scope="col"><?php echo _AT('site_id');  ?></th>
	<th scope="col"><?php echo _AT('site_alias');   ?></th>
	<th scope="col"><?php echo _AT('site_url');       ?></th>
	<th scope="col"><?php echo _AT('site_type');       ?></th>
	<th scope="col"><?php echo _AT('site_path'); ?></th>
	<th scope="col"><?php echo _AT('enabled'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
	<td colspan="7">
		<input type="submit" name="edit" value="<?php echo _AT('edit'); ?>" />
		<input type="submit" name="delete" value="<?php echo _AT('delete'); ?>" />
	</td>
</tr>
</tfoot>
<tbody>
<?php if (mysql_num_rows($result) == 0) { ?>
	<tr>
		<td colspan="6"><?php echo _AT('none_found'); ?></td>
	</tr>
<?php } else {
		while ($row = mysql_fetch_assoc($result)): ?>
			<tr onkeydown="document.form['m<?php echo $row['site_id']; ?>'].checked = true;rowselect(this);" onmousedown="document.form['m<?php echo $row['site_id']; ?>'].checked = true;rowselect(this);" id="r_<?php echo $row['site_id']; ?>">
				<td><input type="radio" name="site_id" value="<?php echo $row['site_id']; ?>" id="m<?php echo $row['site_id']; ?>" /></td>
				<td><label for="m<?php echo $row['site_id']; ?>"><?php echo $row['site_id'];      ?></label></td>
				<td><?php echo $row['site_name'];  ?></td>
				<td><?php echo $row['site_URL'];      ?></td>
				<td><?php echo $row['site_type'];      ?></td>
				<td><?php echo $row['directory'];      ?></td>						
				<td><?php
					if($row['enabled'] == 1){
						 echo _AT('enabled');
					}else{
						echo _AT('disabled');
					}		 
				     ?></td>
			</tr>
	 	<?php endwhile; ?>
	<?php } ?>
</tbody>
</table>
</form>
