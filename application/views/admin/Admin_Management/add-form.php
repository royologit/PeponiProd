
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add New Admin
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
              <h3 class="box-title">Admin Information</h3>
            </div>
            <!-- /.box-header -->
            <?php $admin_controller = 'AdminController/'; ?>
            <div class="box-body">
                <form action="<?php echo base_url().$this->config->item('admin_softlink').$title.'/add' ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
                  <?php include('form.php'); ?>
                </form>
            </div>

    </section>
    <!-- /.content -->
