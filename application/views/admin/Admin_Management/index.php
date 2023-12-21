
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo str_replace('_',' ',$title) ?>
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo bower_url(); ?>/AdminLTE/#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <?php $admin_controller = 'AdminController/'; ?>
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Registered <?php echo str_replace('_Management','',$title) ?>s</h3>
                <?php if ((!isset($hide_action) && !$hide_action) && (!isset($hide_add_btn) && !$hide_add_btn)): ?>
                    <a href="<?php echo base_url().$this->config->item('admin_softlink').$title.'/add' ?>" class="btn btn-success pull-right">Add New</a>
                <?php endif; ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <?php include('datatable.php'); ?>

            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
