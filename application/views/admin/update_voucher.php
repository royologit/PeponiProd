<?php include('layouts/header.php'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height:800px;">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?php echo ucwords($method); ?> Voucher
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url() . $this->config->item('admin_softlink'); ?>"><i
                            class="fa fa-dashboard"></i> Home</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Voucher Information</h3>
                    </div>
                    <!-- /.box-header -->
                    <?php $admin_controller = 'AdminController/'; ?>
                    <form action="<?php echo current_url(); ?>"
                          method="post" class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="voucher_code" class="col-sm-3 control-label">Voucher Code</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_code') ? 'has-error' : ''; ?>">
                                    <input type="text" name="voucher_code" class="form-control" id="voucher_code"
                                           placeholder="Voucher Code"
                                           value="<?php echo set_value('voucher_code', isset($voucher) ? $voucher->voucher_code : ''); ?>">
                                    <?php echo form_error('voucher_code'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="voucher_amount" class="col-sm-3 control-label">Voucher Amount</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_amount') ? 'has-error' : ''; ?>">
                                    <input type="text" name="voucher_amount" class="form-control" id="voucher_amount"
                                           placeholder="Voucher Amount"
                                           value="<?php echo set_value('voucher_amount', isset($voucher) ? $voucher->voucher_amount : ''); ?>">
                                    <?php echo form_error('voucher_amount'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="voucher_quota" class="col-sm-3 control-label">Voucher Quota</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_quota') ? 'has-error' : ''; ?>">
                                    <input type="text" name="voucher_quota" class="form-control" id="voucher_quota"
                                           placeholder="Voucher Quota"
                                           value="<?php echo set_value('voucher_quota', isset($voucher) ? $voucher->voucher_quota : ''); ?>">
                                    <?php echo form_error('voucher_quota'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="voucher_expiration_date" class="col-sm-3 control-label">Voucher Expiration
                                    Date</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_expiration_date') ? 'has-error' : ''; ?>">
                                    <input type="text" name="voucher_expiration_date"
                                           class="form-control input-datepicker" id="voucher_expiration_date"
                                           value="<?php echo set_value('voucher_expiration_date', isset($voucher) ? $voucher->voucher_expiration_date : ''); ?>">
                                    <?php echo form_error('voucher_expiration_date'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="voucher_active" class="col-sm-3 control-label">Voucher Active</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_active') ? 'has-error' : ''; ?>">
                                    <select class="form-control" name="voucher_active">
                                        <option value="1" <?php echo set_value('voucher_active', isset($voucher) ? (!$voucher->voucher_deactivated_at ? '1' : '0') : '0') ? 'selected' : ''; ?>>
                                            Active
                                        </option>
                                        <option value="0" <?php echo set_value('voucher_active', isset($voucher) ? ($voucher->voucher_deactivated_at ? '1' : '0') : '0') ? 'selected' : ''; ?>>
                                            Inactive
                                        </option>
                                    </select>
                                    <?php echo form_error('voucher_active'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="voucher_product" class="col-sm-3 control-label">Product</label>
                                <div class="col-sm-9 <?php echo form_error('voucher_product[]') ? 'has-error' : ''; ?>">
                                    <?php foreach ($products as $index => $product): ?>
                                        <?php
                                        $checked = false;

                                        if (isset($voucherDetails)) {
                                            foreach ($voucherDetails as $voucherDetail) {
                                                if ($voucherDetail->product_id == $product->product_id) {
                                                    $checked = true;
                                                }
                                            }
                                        }
                                        ?>

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="voucher_product[]"
                                                       value="<?php echo $product->product_id; ?>" <?php echo $checked ? "checked" : ""; ?> > <?php echo $product->product_name; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php echo form_error('voucher_product[]'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-default" onclick="window.history.back()">Cancel
                            </button>
                            <button type="submit"
                                    class="btn btn-success pull-right"><?php echo ucwords($method); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

</div>

<script>
  $(function () {
    $('.input-datepicker').datepicker({
      format: 'yyyy-mm-dd',
    });
  });
</script>

<!-- /.content-wrapper -->
<?php include('layouts/footer.php'); ?>
