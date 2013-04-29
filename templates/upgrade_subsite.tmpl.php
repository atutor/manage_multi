
<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table summary="<?php echo _AT("list_of_subsites"); ?>" class="data" rules="cols" align="left" style="width: 95%;">
<thead>
<tr>
    <th scope="col"><input type="checkbox" id="AT_upgrade_all" /></th>
    <th scope="col"><?php echo _AT('site_url'); ?></th>
    <th scope="col"><?php echo _AT('updated_date'); ?></th>
    <th scope="col"><?php echo _AT('status'); ?></th>
</tr>
</thead>
<tfoot>
<tr>
    <td colspan="4">
        <input type="submit" name="upgrade" value="<?php echo _AT('upgrade'); ?>" />
    </td>
</tr>
</tfoot>
<tbody>
<?php if (count($rows) == 0) { ?>
    <tr>
        <td colspan="3"><?php echo _AT('none_found'); ?></td>
    </tr>
<?php } else {
        foreach ($rows as $row) { ?>
            <tr class="AT_subsites_row">
                 <td class="fl-tabs-center"><input type="checkbox" name="site_url[]" value="<?php echo $row['site_url']; ?>" id="<?php echo $row['site_url']; ?>" <?php if ($row['version'] == VERSION) {echo 'disabled="disabled" '; } ?>/></td>
                <td><?php echo $row['site_url']; ?></td>
                <?php if(isset($row['updated_date']) && $row['updated_date'] != ''){ ?>
                    <td><?php echo $row['updated_date']; ?></td>
                <?php }else{ ?>
                    <td><?php echo _AT('na'); ?></td>
                <?php } ?>
                <td><?php
                    if($row['version'] == VERSION){
                        echo _AT(array('has_latest_version', VERSION));
                    }else{
                        echo _AT(array('need_upgrade', $row['version'], VERSION));
                    }
                ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
</tbody>
</table>
</form>
