<?php include('layouts/header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Add Product Image
            <small>Control panel</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo bower_url(); ?>/AdminLTE/#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Product Image Information</h3>
                </div>
                <!-- /.box-header -->
                <?php $admin_controller = 'AdminController/'; ?>
                <div class="box-body">
                    <form action="<?php echo base_url().$this->config->item('admin_softlink').$title.'/add_images/'.$product_id; ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <div class="box-body">
                          <div class="form-group">
                            <label for="product_image" class="col-sm-2 control-label">Product Image</label>

                            <div class="col-sm-10">
                              <input type="file" multiple name="product_image[]" class="form-control" id="product_image" placeholder="Product Image">
                            </div>
                          </div>
                        </div>
                        <div class="box-footer">
                          <button type="submit" class="btn btn-default" onclick="window.history.back()">Cancel</button>
                          <button type="submit" class="btn btn-success pull-right"><?php echo 'add'; ?></button>
                        </div>
                    </form>
                </div>

        </section>
        <!-- /.content -->

  </div>
<!-- /.content-wrapper -->
<?php include('layouts/footer.php'); ?>
