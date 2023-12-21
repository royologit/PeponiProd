<table id="dttable" class="table table-bordered table-hover">
    <thead>
    <tr>
        <?php foreach ($Management_List as $db_column => $column): ?>
            <?php $check_id = strpos($db_column, 'id'); ?>
            <?php if (!$check_id) : ?>
                <th><?php echo $column; ?></th>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if (!isset($hide_action) && !$hide_action): ?>
        <th>Action</th>
        <?php endif; ?>
        <!--
        <th>Rendering engine</th>
        <th>Browser</th>
        <th>Platform(s)</th>
        <th>Engine version</th>
        <th>CSS grade</th>
        <th>Action</th>
        -->
    </tr>
    </thead>
    <tbody>
    <?php $id = ''; ?>
    <?php $admin_controller = 'AdminController/'; ?>
    <?php
    $Managements = is_array($Managements) ? $Managements : $Managements->result();
    foreach ($Managements as $Management):
        ?>
        <tr>
            <?php foreach ($Management_List as $db_column => $column):  ?>
                <?php $check_id = strpos($db_column, 'id'); ?>
                <?php $check_image = strpos($db_column, 'image'); ?>
                <?php if ($check_image) : ?>
                    <?php if ($db_column == 'product_image'): ?>
                        <td>
                            <a href="<?php echo base_url() . $this->config->item('admin_softlink') . $title . '/add_images/' . $Management->product_id; ?>"
                               class="btn btn-success">Add Image</a>
                            <p></p>
                            <?php foreach ($tr_product_image->result() as $tpi): ?>
                                <?php if ($Management->product_id == $tpi->product_id): ?>
                                    <img width="100" src="<?php echo base_url() . $tpi->product_image; ?>">
                                    <a style="color:red;font-size:24px;font-family:'arial'"
                                       href="<?php echo base_url() . $this->config->item('admin_dir_controller') . 'AdminController/delete/Tr_Product_Image_Management/' . $tpi->tr_product_image_id; ?>">x</a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    <?php else: ?>
                        <td><img width="100" src="<?php echo base_url() . $Management->$db_column; ?>"></td>
                    <?php endif; ?>
                <?php elseif($db_column == 'voucher_amount'): ?>
                    <td><?php echo currency_format($Management->$db_column); ?></td>
                <?php elseif($db_column == 'voucher_deactivated_at'): ?>
                    <td><?php echo $Management->$db_column ? "Inactive" : "Active"; ?></td>
                <?php elseif($db_column == 'voucher_product'): ?>
                    <td>
                    <?php foreach ($voucher_detail->result() as $detail): ?>
                        <?php if ($Management->voucher_id == $detail->voucher_id): ?>
                            <?php echo $detail->product_name; ?>
                            <br/>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </td>
                <?php else : ?>
                    <?php if ($check_id) : ?>
                        <?php $id = $Management->$db_column; ?>
                    <?php else: ?>
                        <td><?php echo $Management->$db_column; ?></td>
                    <?php endif; ?>
                <?PHP endif; ?>
                
            <?php endforeach; ?>
            <?php if (!isset($hide_action) && !$hide_action): ?>
            <td>
                <a href="<?php echo base_url() . $this->config->item('admin_softlink') . $title . '/edit/' . $id; ?>"
                   class="btn btn-success">Edit</a>
                <?php if (!isset($hide_delete_btn) || !$hide_delete_btn): ?>
                    <a href="<?php echo base_url() . $this->config->item('admin_softlink') . $title . '/delete/' . $id; ?>"
                       onclick="return confirm('Are you sure?');"
                       class="btn btn-danger">Delete</a>
                <?php endif; ?>
                <?php if($table == 'package') :?>
                    <?php if($Management->package_push == 0){?>
                        <a href="<?php echo base_url() . $this->config->item('admin_softlink') . 'package/push/' . $id.'/1'; ?>"
                        onclick="return confirm('Are you sure?');"
                        class="btn btn-primary">Push</a>
                    <?php }elseif($Management->package_push == 1){?>
                        <a href="<?php echo base_url() . $this->config->item('admin_softlink') . 'package/push/' . $id.'/0'; ?>"
                        onclick="return confirm('Are you sure?');"
                        class="btn btn-warning">Take Down</a>
                    <?php }?>
                <?php endif; ?>
                <?php if($table == 'product') :?>
                        <?php if($Management->product_push == 0){?>
                            <a href="<?php echo base_url() . $this->config->item('admin_softlink') . 'product/push/' . $id.'/1'; ?>"
                            onclick="return confirm('Are you sure?');"
                            class="btn btn-primary">Push</a>
                        <?php }elseif($Management->product_push == 1){?>
                            <a href="<?php echo base_url() . $this->config->item('admin_softlink') . 'product/push/' . $id.'/0'; ?>"
                            onclick="return confirm('Are you sure?');"
                            class="btn btn-warning">Take Down</a>
                        <?php }?>
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>


    <!--
  <?php
    for ($i = 0; $i < 100; $i++):
        ?>
  <tr>
    <td>Trident</td>
    <td>Internet
      Explorer 4.0
    </td>
    <td>Win 95+</td>
    <td> 4</td>
    <td>X</td>
    <td>
      <a href="<?php echo base_url(); ?>b4ck3nd/admin_management/edit/<?php echo 1; ?>" class="btn btn-success">Edit</a>
      <a href="<?php echo base_url(); ?>b4ck3nd/delete/admin_management/<?php echo 1; ?>" class="btn btn-danger">Delete</a>
    </td>
  </tr>
<?php endfor; ?>
  -->
    </tbody>
</table>
<script>
  $(function () {
    $('#dttable').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "aaSorting": []
    });
  });
</script>
