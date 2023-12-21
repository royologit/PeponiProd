<?php
 $column = array('order_id'            => 'Order ID',
                'order_type_name'     => 'Trip Type',
                'product_name'        => 'Trip Name',
                'order_name'          => 'Name',
                'order_phone'         => 'Phone/WA',
                'order_email'         => 'Email',
                'order_line_id'       => 'Line ID',
                'order_participant'	  => 'Participant',
                'voucher_code'        => 'Voucher Code',
                'order_price'         => 'Price',
                'payment_method_name' => 'Payment Method',
                'trip_schedule'    => 'Trip Schedule',
                'order_note'          => 'Private Note',
                'created_at'	 	  => 'Created At');

?>
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
              <a class="btn btn-success pull-right" data-toggle="modal" data-target="#create-order">Add New</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="dttable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                    <?php foreach ($column as $key => $title) {
    ?>
                            <th><?php echo $title ?></th>
                    <?php
} ?>
                            <th style="width: 360px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                 //echo "<pre>" . var_export($result, true) . "</pre>";
                foreach ($result as $order) {
                    ?>
                        <tr>
                    <?php foreach ($column as $key => $title) {
                        if ($key == "product_name" and $order->product_id == null) {
                            echo "<td>". $order->private_name ."</td>";
                        } elseif ($key == "order_price") {
                            echo "<td>". currency_format($order->order_price, false) . "</td>";
                        } elseif ($key == "order_start_date") {
                            echo "<td>". date("d M Y", strtotime($order->order_start_date)) . " - " . date("d M Y", strtotime($order->order_end_date)) . "</td>";
                        } elseif ($key == "order_end_date") {
                        } else {
                            echo "<td>". $order->{$key} . "</td>";
                        } ?>
                    <?php
                    } ?>
                            <td style="width: 260px">
                                <a class="btn btn-primary participant-invoice" data-action="<?php echo base_url()?>dashboard/v2/Participant_Invoice" data-method="POST" data-form='order_id=<?php echo $order->order_id?>'>Payment</a>
                                <a class="btn btn-success" data-toggle="modal" data-target="#update-order" data-json='<?php echo json_encode($order) ?>'>Edit</a>&nbsp
                                <a class="btn btn-success btn-order-remove" data-method="POST" data-action="<?php echo base_url(); ?>dashboard/v2/Delete_Order" data-form="order_id=<?php echo $order->order_id ?>">Delete</a>
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
          "scrollX": true,
          "scrollY": "auto",
          "aaSorting": []
        });
      });
    </script>
</div>

<div class="modal fade" id="create-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Create Order</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Create_Order", 'id="form-order-create"'); ?>
      <div class="modal-body">
        <div class="col-xs-12 custom-input">
            <div class="col-sm-12">
              <label for="order_id" class="col-sm-3 control-label">Trip Type</label>
              <div class="col-sm-9">
                <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                <select name="product_type" class="form-control" style="">
                    <option value="open_trip">Open Trip</option>
                    <option value="private_trip">Private Trip</option>
                </select>
              </div>
            </div>
            <!-- Open Trip -->
            <div class="col-sm-12 open-trip section nopadding" id="open-trip-section">
                <div class="col-sm-12">
                  <label for="title" class="col-sm-3 control-label">Trip Name</label>
                  <div class="col-sm-9">
                      <select name="order[product_id]" class="form-control open_trip_product" style="">
                <?php foreach ($products as $product) {
                    ?>
                        <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name ?></option>
                <?php
                } ?>
                      </select>
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_name" class="col-sm-3 control-label">Guest Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_name]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_phone" class="col-sm-3 control-label">Guest Phone/WA</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_phone]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_email" class="col-sm-3 control-label">Guest Email</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_email]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12 age-group nopadding">
            <?php foreach ($age_group as $age) {
                    ?>
                    <div class="col-sm-12">
                      <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?></label>
                      <div class="col-sm-9">
                        <input type="number" name="age[<?php echo $age->age_group_id?>]" class="form-control age_change" data-id="<?= $age->age_group_id?>" placeholder="" value="0" />
                      </div>
                    </div>
            <?php
                } ?>
                </div>
                <div class="col-sm-12">
                  <label for="order_price" class="col-sm-3 control-label">Override Price</label>
                      <div class="col-sm-9">
                          <div class="input-icon input-icon-left">
                            <i>IDR</i>
                                <input type="text" name="order[order_price]" class="form-control field-price open-price" placeholder="0.0" value="">
                          </div>
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="title" class="col-sm-3 control-label">Payment Method</label>
                  <div class="col-sm-9">
                      <select name="order[payment_method_id]" class="form-control" style="">
                <?php foreach ($payment_method as $method) {
                    ?>
                        <option value="<?php echo $method->payment_method_id ?>"><?php echo $method->payment_method_name ?></option>
                <?php
                } ?>
                      </select>
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="note" class="col-sm-3 control-label">Note</label>
                  <div class="col-sm-9">
                    <textarea name="order[order_note]" class="form-control"></textarea>
                  </div>
                </div>
            </div>
            <!-- Private Trip -->
            <div class="col-sm-12 private-trip section nopadding" id="private-trip-section">
                <div class="col-sm-12">
                  <label for="title" class="col-sm-3 control-label">Private Trip Name</label>
                  <div class="col-sm-9">
                      <select name="private_trip[id]" class="form-control private_trip_id" style="">
                <?php foreach ($private_trip as $product) {
                    ?>
                        <option value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
                <?php
                } ?>
                        <option value="custom">Custom Trip</option>
                      </select>
                  </div>
                </div>
                <!-- Custom Package -->
                <div class="col-sm-12 custom-package nopadding">
                    <div class="col-sm-12">
                      <label for="private_trip" class="col-sm-3 control-label">Destination</label>
                      <div class="col-sm-9">
                          <select name="private_trip[package_id]" class="form-control" style="">
                    <?php foreach ($package_list as $package) {
                    ?>
                            <option value="<?php echo $package->package_id ?>"><?php echo $package->package_name ?></option>
                    <?php
                } ?>
                          </select>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label for="price" class="col-sm-3 control-label">Trip Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="private_trip[name]" class="form-control" placeholder="" value="">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label for="detail" class="col-sm-3 control-label">Description</label>
                      <div class="col-sm-9">
                        <textarea name="private_trip[description]" class="form-control"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12">
                        <label for="start_date" class="col-sm-3 control-label">Range Date</label>
                        <div class="col-sm-9 nopadding">
                            <div class="col-sm-5">
                                <input type="text" name="private_trip[start_date]" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
                            </div>
                            <div class="col-sm-1">-</div>
                            <div class="col-sm-6">
                                <input type="text" name="private_trip[end_date]" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-12">
                      <label for="person" class="col-sm-3 control-label">Person</label>
                          <div class="col-sm-9">
                            <input type="text" name="private_trip[person]" class="form-control" placeholder="" value="">
                          </div>
                    </div> -->
                    <!-- <div class="col-sm-12">
                      <label for="price" class="col-sm-3 control-label">Price</label>
                          <div class="col-sm-9">
                              <div class="input-icon input-icon-left">
                                <i>IDR</i>
                                    <input type="text" name="private_trip[price]" class="form-control custom-price" placeholder="0.0" value="">
                              </div>
                          </div>
                    </div> -->
                    <?php
                    foreach ($age_group as $age) {
                        ?>
                            <div class="col-sm-12">
                              <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?> Price</label>
                              <div class="col-sm-9">
                                  <div class="input-icon input-icon-left">
                                    <i>IDR</i>
                                        <input type="text" name="private_trip[age][<?php echo $age->age_group_id?>]" data-group-id="<?= $age->age_group_id?>" class="form-control form-age" placeholder="0.0" value="">
                                  </div>
                              </div>
                            </div>
                    <?php
                    } ?>
                    <div class="col-sm-12">
                      <label for="title" class="col-sm-3 control-label">Order Period</label>
                      <div class="col-sm-5">
                        <input type="text" name="period[order_date_start]" class="datepick form-control" placeholder="Start Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="">
                      </div>
                      <div class="col-sm-4">
                        <input type="text" name="period[order_date_end]" class="datepick form-control" placeholder="End Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="">
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label for="detail" class="col-sm-3 control-label">Down Payment</label>
                      <div class="col-sm-9">
                          <div class="input-icon input-icon-left">
                            <i>IDR</i>
                                <input type="number" step="10000" name="period[down_payment]" class="form-control" placeholder="0.0" value="">
                          </div>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <label for="price" class="col-sm-3 control-label">Period (Tahap Pembayaran)</label>
                      <div class="col-sm-9 period-field">
                          <div class="col-sm-12 nopadding">
                              <div class="col-sm-3 control-label period-margin text-center">Label</div>
                              <div class="col-sm-3 control-label period-margin text-center">Due Date</div>
                              <div class="col-sm-4 control-label period-margin text-center">Price</div>
                          </div>
                          <div class="col-sm-12 nopadding period-row">
                              <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][label][]" class="form-control" placeholder="" value="" /></div>
                              <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][duedate][]" class="form-control datepick" placeholder="" value="" /></div>
                              <div class="col-sm-4 period-margin">
                                  <div class="input-icon input-icon-left">
                                    <i>IDR</i>
                                        <input type="text" name="period[period_json][price][]" class="form-control" placeholder="0.0" value="" />
                                  </div>
                              </div>
                              <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div>
                          </div>
                      </div>
                      <div class="col-sm-9 col-sm-offset-3">
                          <a class="btn btn-info period-add-new">Add Row</a>
                      </div>
                    </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_name" class="col-sm-3 control-label">Guest Name</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_name]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_phone" class="col-sm-3 control-label">Guest Phone/WA</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_phone]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="order_email" class="col-sm-3 control-label">Guest Email</label>
                      <div class="col-sm-9">
                        <input type="text" name="order[order_email]" class="form-control" placeholder="" value="">
                      </div>
                </div>
                <div class="col-sm-12 age-group nopadding">
            <?php foreach ($age_group as $age) {
                        ?>
                    <div class="col-sm-12">
                      <label for="age" class="col-sm-3 control-label"><?php echo $age->age_group_name ?></label>
                      <div class="col-sm-9">
                        <input type="number" name="age[<?php echo $age->age_group_id?>]" class="form-control age_change" data-id="<?php echo $age->age_group_id?>" placeholder="" value="0" />
                      </div>
                    </div>
            <?php
                    } ?>
                </div>
                <div class="col-sm-12">
                  <label for="order_price" class="col-sm-3 control-label">Override Price</label>
                      <div class="col-sm-9">
                        <div class="input-icon input-icon-left">
                          <i>IDR</i>
                            <input type="text" name="order[order_price]" class="form-control field-price private-price" placeholder="0.0" value="">
                        </div>
                      </div>
                </div>
                <div class="col-sm-12">
                  <label for="title" class="col-sm-3 control-label">Payment Method</label>
                  <div class="col-sm-9">
                      <select name="order[payment_method_id]" class="form-control" style="">
                <?php foreach ($payment_method as $method) {
                        ?>
                        <option value="<?php echo $method->payment_method_id ?>"><?php echo $method->payment_method_name ?></option>
                <?php
                    } ?>
                      </select>
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="note" class="col-sm-3 control-label">Note</label>
                  <div class="col-sm-9">
                    <textarea name="order[order_note]" class="form-control"></textarea>
                  </div>
                </div>
            </div>
        </div>
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


<div class="modal fade" id="update-order" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Edit Order</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Update_Order", 'id="form-order-update"'); ?>
    <div class="modal-body">
      <div class="col-xs-12 custom-input">
          <input type="hidden" name="order[order_id]" value="" />
          <div class="col-sm-12">
            <label for="order_id" class="col-sm-3 control-label">Trip Type</label>
            <div class="col-sm-9">
              <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
              <select name="product_type" class="form-control" style="">
                  <option value="open_trip">Open Trip</option>
                  <option value="private_trip">Private Trip</option>
              </select>
            </div>
          </div>
          <!-- Open Trip -->
          <div class="col-sm-12 section open-trip nopadding" id="open-trip-section">
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Trip Name</label>
                <div class="col-sm-9">
                    <select name="order[product_id]" class="form-control open_trip_product" style="">
              <?php foreach ($products as $product) {
                        ?>
                      <option value="<?php echo $product->product_id ?>"><?php echo $product->product_name ?></option>
              <?php
                    } ?>
                    </select>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="order_name" class="col-sm-3 control-label">Guest Name</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_name]" class="form-control" placeholder="" value="">
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="order_phone" class="col-sm-3 control-label">Guest Phone</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_phone]" class="form-control" placeholder="" value="">
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="order_email" class="col-sm-3 control-label">Guest Email</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_email]" class="form-control" placeholder="" value="">
                    </div>
              </div>
              <div class="col-sm-12 age-group nopadding">
          <?php foreach ($age_group as $age) {
                        ?>
                  <div class="col-sm-12">
                    <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?></label>
                    <div class="col-sm-9">
                      <input type="number" name="age[<?php echo $age->age_group_id?>][val]" class="form-control age_change" data-id="<?php echo $age->age_group_id?>" placeholder="" value="0" />
                      <input type="hidden" name="age[<?php echo $age->age_group_id?>][id]" value="" />
                    </div>
                  </div>
          <?php
                    } ?>
              </div>
              <div class="col-sm-12">
                <label for="order_price" class="col-sm-3 control-label">Override Price</label>
                    <div class="col-sm-9">
                      <div class="input-icon input-icon-left">
                        <i>IDR</i>
                            <input type="text" name="order[order_price]" class="form-control field-price open-price" placeholder="0.0" value="">
                      </div>
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Payment Method</label>
                <div class="col-sm-9">
                    <select name="order[payment_method_id]" class="form-control" style="">
              <?php foreach ($payment_method as $method) {
                        ?>
                      <option value="<?php echo $method->payment_method_id ?>"><?php echo $method->payment_method_name ?></option>
              <?php
                    } ?>
                    </select>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="note" class="col-sm-3 control-label">Note</label>
                <div class="col-sm-9">
                  <textarea name="order[order_note]" class="form-control"></textarea>
                </div>
              </div>
          </div>
          <!-- Private Trip -->
          <div class="col-sm-12 section private-trip nopadding" id="private-trip-section">
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Private Trip Name</label>
                <div class="col-sm-9">
                    <select name="private_trip[id]" class="form-control private_trip_id" style="">
              <?php foreach ($private_trip as $product) {
                        ?>
                      <option value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
              <?php
                    } ?>
                      <option value="custom">Custom Trip</option>
                    </select>
                </div>
              </div>
              <!-- Custom Package -->
              <div class="col-sm-12 custom-package nopadding">
                  <div class="col-sm-12">
                    <label for="private_trip" class="col-sm-3 control-label">Package</label>
                    <div class="col-sm-9">
                        <select name="private_trip[package_id]" class="form-control" style="">
                  <?php foreach ($package_list as $package) {
                        ?>
                          <option value="<?php echo $package->package_id ?>"><?php echo $package->package_name ?></option>
                  <?php
                    } ?>
                        </select>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <label for="price" class="col-sm-3 control-label">Trip Name</label>
                    <div class="col-sm-9">
                      <input type="text" name="private_trip[name]" class="form-control" placeholder="" value="">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <label for="detail" class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-9">
                      <textarea name="private_trip[description]" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="col-sm-12">
                      <label for="start_date" class="col-sm-3 control-label">Range Date</label>
                      <div class="col-sm-9 nopadding">
                          <div class="col-sm-5">
                              <input type="text" name="private_trip[start_date]" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
                          </div>
                          <div class="col-sm-1">-</div>
                          <div class="col-sm-6">
                              <input type="text" name="private_trip[end_date]" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
                          </div>
                      </div>
                  </div>
                  <!-- <div class="col-sm-12">
                    <label for="person" class="col-sm-3 control-label">Person</label>
                        <div class="col-sm-9">
                          <input type="text" name="private_trip[person]" class="form-control" placeholder="" value="">
                        </div>
                  </div>
                  <div class="col-sm-12">
                    <label for="price" class="col-sm-3 control-label">Price</label>
                        <div class="col-sm-9">
                          <div class="input-icon input-icon-left">
                            <i>IDR</i>
                                <input type="text" name="private_trip[price]" class="form-control custom-price" placeholder="0.0" value="">
                          </div>
                        </div>
                  </div> -->
                  <?php
                  foreach ($age_group as $age) {
                      ?>
                          <div class="col-sm-12">
                            <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?> Price</label>
                            <div class="col-sm-9">
                                <div class="input-icon input-icon-left">
                                  <i>IDR</i>
                                      <input type="text" name="private_trip[age][<?php echo $age->age_group_id?>]" data-group-id="<?= $age->age_group_id?>" class="form-control form-age" placeholder="0.0" value="">
                                </div>
                            </div>
                          </div>
                  <?php
                  } ?>
                  <div class="col-sm-12">
                    <label for="title" class="col-sm-3 control-label">Period Range Date</label>
                    <div class="col-sm-5">
                      <input type="text" name="period[order_date_start]" class="datepick form-control" placeholder="Start Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="period[order_date_end]" class="datepick form-control" placeholder="End Order Date" data-date-format="yyyy-mm-dd" placeholder="" value="">
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <label for="detail" class="col-sm-3 control-label">Down Payment</label>
                    <div class="col-sm-9">
                        <div class="input-icon input-icon-left">
                          <i>IDR</i>
                              <input type="number" step="10000" name="period[down_payment]" class="form-control" placeholder="0.0" value="">
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <label for="price" class="col-sm-3 control-label">Period</label>
                    <div class="col-sm-9 period-field">
                        <div class="col-sm-12 nopadding">
                            <div class="col-sm-3 control-label period-margin text-center">Label</div>
                            <div class="col-sm-3 control-label period-margin text-center">Due Date</div>
                            <div class="col-sm-4 control-label period-margin text-center">Price</div>
                        </div>
                        <div class="col-sm-12 nopadding period-row">
                            <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][label][]" class="form-control" placeholder="" value="" /></div>
                            <div class="col-sm-3 period-margin"><input type="text" name="period[period_json][duedate][]" class="form-control datepick" placeholder="" value="" /></div>
                            <div class="col-sm-4 period-margin">
                                <div class="input-icon input-icon-left">
                                  <i>IDR</i>
                                      <input type="text" name="period[period_json][price][]" class="form-control" placeholder="0.0" value="" />
                                </div>
                            </div>
                            <div class="col-sm-1 period-margin period-delete-icon"><i class="fa fa-ban"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-9 col-sm-offset-3">
                        <a class="btn btn-info period-add-new">Add Row</a>
                    </div>
                  </div>
              </div>
              <div class="col-sm-12">
                <label for="order_name" class="col-sm-3 control-label">Guest Name</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_name]" class="form-control" placeholder="" value="">
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="order_phone" class="col-sm-3 control-label">Guest Phone</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_phone]" class="form-control" placeholder="" value="">
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="order_email" class="col-sm-3 control-label">Guest Email</label>
                    <div class="col-sm-9">
                      <input type="text" name="order[order_email]" class="form-control" placeholder="" value="">
                    </div>
              </div>
          <?php foreach ($age_group as $age) {
                      ?>
                  <div class="col-sm-12">
                    <label for="age" class="col-sm-3 control-label"><?php echo $age->age_group_name ?></label>
                    <div class="col-sm-9">
                        <input type="number" name="age[<?php echo $age->age_group_id?>][val]" class="form-control age_change" data-id="<?php echo $age->age_group_id?>" placeholder="" value="0" />
                        <input type="hidden" name="age[<?php echo $age->age_group_id?>][id]" value="" />
                    </div>
                  </div>
          <?php
                  } ?>
              </div>
              <div class="col-sm-12">
                <label for="order_price" class="col-sm-3 control-label">Override Price</label>
                    <div class="col-sm-9">
                        <div class="input-icon input-icon-left">
                          <i>IDR</i>
                              <input type="text" name="order[order_price]" class="form-control field-price private-price" placeholder="0.0" value="">
                        </div>
                    </div>
              </div>
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Payment Method</label>
                <div class="col-sm-9">
                    <select name="order[payment_method_id]" class="form-control" style="">
              <?php foreach ($payment_method as $method) {
                      ?>
                      <option value="<?php echo $method->payment_method_id ?>"><?php echo $method->payment_method_name ?></option>
              <?php
                  } ?>
                    </select>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="note" class="col-sm-3 control-label">Note</label>
                <div class="col-sm-9">
                  <textarea name="order[order_note]" class="form-control"></textarea>
                </div>
              </div>
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

<div class="modal fade" id="payment-list" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Payment List</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
      <div class="modal-body">
          <div class="col-sm-12">
                  <table id="subsubtable" class="table table-bordered table-hover">
                      <thead>
                          <tr>
                              <th>Invoice ID</th>
                              <th>Title</th>
                              <th>Detail</th>
                              <th>Price</th>
                              <th>Status</th>
                              <th>Due Date</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
          </div>
      </div>
      <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
    </form>

<script>
    var mainUrl = "<?= base_url() ?>";
</script>



  </div>
</div>
