<div class="content-wrapper" style="min-height:800px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php // echo str_replace('_',' ',$title)?>
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
              <h3 class="box-title"></h3>
              <a class="btn btn-success pull-right" data-toggle="modal" data-target="#create-period">Add New</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="dttable" class="table table-bordered table-hover" data-detail-url="<?php echo base_url()?>dashboard/v2/Invoice_Detail">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Trip Name</th>
                            <th>Order Period</th>
                            <th>Down Payment</th>
                            <th>Period (Tahap Pembayaran)</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php  foreach ($result as $period) {
    ?>
                        <tr data-id="<?php echo $period->id?>">
                            <td><?php echo $period->id ?></td>
                            <td><?php echo $period->product_name ? $period->product_name : $period->name  ?></td>
                            <td><?php echo $period->order_date_start ?> - <?php echo $period->order_date_end ?></td>
                            <td><?php echo $period->down_payment ?></td>
                            <td><?php
                                $period_data = json_decode($period->period_json);
    $first = true;
    foreach ($period_data as $each) {
        if (!$first) {
            echo "<br>";
        } else {
            $first = false;
        }
        echo $each->label . " - " . $each->duedate . " - " . $each->price;
    } ?>
                            </td>
                            <td><?php echo $period->created_date ?></td>
                            <td><?php echo $period->updated_date ?></td>
                            <td><a class="btn btn-success" data-toggle="modal" data-target="#update-period" data-json='<?php echo json_encode($period) ?>'>Edit</a>&nbsp
                                <a class="btn btn-success btn-period-remove" data-method="POST" data-action="<?php echo base_url(); ?>dashboard/v2/Delete_Period" data-form="period_id=<?php echo $period->id ?>">Delete</a>
                            </td>
                        </tr>
                <?php
} ?>
                    </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
    <script>
      $(function () {
        $('#dttable').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "autoWidth": true,
          "aaSorting": [],
          order: [[6, 'desc'],[5, 'desc']],
        });
      });
    </script>
</div>

<div class="modal fade" id="create-period" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Create <?php echo $name?></h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Create_Period", 'id="form-period-create"'); ?>
      <div class="modal-body">
          <div class="col-xs-12 custom-input">
              <div class="col-sm-12">
                <label for="product_id" class="col-sm-3 control-label">Trip Name</label>
                <div class="col-sm-9">
                  <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                  <select name="product_id" class="form-control" style="">
          <?php
                  foreach ($product_list as $product) {
                      ?>
                      <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name ?></option>
          <?php
                  } ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Order Period</label>
                <div class="col-sm-5">
                  <input type="text" name="order_date_start" class="datepick form-control" placeholder="Start Order Date"data-date-format="yyyy-mm-dd" placeholder="" value="" readonly>
                </div>
                <div class="col-sm-4">
                  <input type="text" name="order_date_end" class="datepick form-control" placeholder="End Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="" readonly>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="detail" class="col-sm-3 control-label">Down Payment</label>
                <div class="col-sm-9">
                  <input type="number" step="10000" name="down_payment" class="form-control" placeholder="" value="">
                </div>
              </div>
              <div class="col-sm-12">
                <label for="price" class="col-sm-3 control-label">Period (Tahap Pembayaran)</label>
                
              </div>
          </div>
            <div class="col-sm-12 period-field" style="padding-right: 0px;">
                <div class="col-sm-12 nopadding ">
                    <div class="col-sm-2 control-label period-margin text-center">Label</div>
                    <div class="col-sm-2 control-label period-margin text-center">Due Date</div>
                    <div class="col-sm-2 control-label period-margin text-center">Days</div>
                    <div class="col-sm-2 control-label period-margin text-center">Price</div>
                    <div class="col-sm-2 control-label period-margin text-center">Type</div>
                </div>
            </div>
            <div class="col-sm-12 nopadding period-row" style="padding-left: 5%"> 
                 
            </div>
              <a class="btn btn-info period-add-new">Add Row</a>
      </div>
      <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Create"></input>
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal fade" id="update-period" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Edit <?php echo $name ?></h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Update_Period", 'id="form-period-update"'); ?>
      <div class="modal-body">
        <div class="col-xs-12 custom-input">
            <div class="col-sm-12">
              <label for="product_id" class="col-sm-3 control-label">Trip Name</label>
              <input type="hidden" name="id" value="" />
              <div class="col-sm-9">
                <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                <select name="product_id" class="form-control" style="">
        <?php
                foreach ($product_list as $product) {
                    ?>
                    <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name  ?></option>
        <?php
                } ?>
        <?php
                foreach ($private_list as $private) {
                    ?>
                    <option value="<?php echo $private->id ?>"><?php echo $private->name ?></option>
        <?php
                } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12" style="margin-top: 2%;">
              <label for="title" class="col-sm-3 control-label">Order Period</label>
              <div class="col-sm-5">
                <input type="text" name="order_date_start" class="datepick form-control" placeholder="Start Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="" readonly>
              </div>
              <div class="col-sm-4" >
                <input type="text" name="order_date_end" class="datepick form-control" placeholder="End Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="" readonly>
              </div>
            </div>
            <div class="col-sm-12" style="margin-top: 2%;">
              <label for="detail" class="col-sm-3 control-label">Down Payment</label>
              <div class="col-sm-9">
                <input type="number" step="10000" name="down_payment" class="form-control" placeholder="" value="">
              </div>
            </div>

            <div class="col-sm-12" style="margin-top: 2%;">
              <label for="price" class="col-sm-3 control-label">Period (Tahap Pembayaran)</label>
             
            </div>
        </div>
        <div class="row">
              <div class="col-sm-12 period-field" style="padding-right: 0px;">
                  <div class="col-sm-12 nopadding ">
                      <div class="col-sm-2 control-label period-margin text-center">Label</div>
                      <div class="col-sm-2 control-label period-margin text-center">Due Date</div>
                      <div class="col-sm-2 control-label period-margin text-center">Days</div>
                      <div class="col-sm-2 control-label period-margin text-center">Price</div>
                      <div class="col-sm-2 control-label period-margin text-center">Type</div>
                  </div>
              </div>
              <div class="col-sm-12 col-sm-offset-3 text-left" >
                  <a class="btn btn-info period-add-new">Add Row</a>
              </div>
        </div>
      </div>
      <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <input type="submit" class="btn btn-primary" value="Update"></input>
      </div>
    </div>
    </form>
  </div>
</div>
