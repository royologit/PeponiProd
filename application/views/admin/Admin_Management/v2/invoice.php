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
              <a class="btn btn-success pull-right" data-toggle="modal" data-target="#create-invoice">Add New</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="dttable" class="table table-bordered table-hover" data-detail-url="<?php echo base_url()?>dashboard/v2/Invoice_Detail">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order Name</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php  foreach ($result as $invoice) {
    ?>
                        <tr data-id="<?php echo $invoice->id?>">
                            <td><?php echo $invoice->id ?></td>
                            <td><?php echo $invoice->order_name ?></td>
                            <td><?php echo $invoice->title ?></td>
                            <td><?php echo $invoice->description ?></td>
                            <td><?php echo $invoice->quantity ?></td>
                            <td><?php echo $invoice->price ?></td>
                            <td><?php echo $invoice->discount ?></td>
                            <td><?php echo ($invoice->price - $invoice->discount) ?></td>
                            <td><?php echo ($invoice->status == 0)? "Unpaid": "Paid"; ?>
                            <td><?php echo $invoice->due_date ?></td>
                            <td><?php echo $invoice->created_date ?></td>
                            <td><?php echo $invoice->updated_date ?></td>
                            <td><?php if ($invoice->status == 0) {
        ?><a class="btn btn-primary add-payment" data-refresh="true" data-action="<?php echo base_url()?>dashboard/v2/Add_Payment" data-method="POST" invoice_id="<?php echo $invoice->id?>" data-form='invoice_id=<?php echo $invoice->id?>'>Add Payment</a>&nbsp<?php
    } ?>
                                <a class="btn btn-primary download-invoice" href="<?php echo base_url()?>dashboard/v2/Download_Invoice?invoice_id=<?php echo $invoice->id?>" target="_blank">Download PDF</a>&nbsp
                                <a class="btn btn-success" data-toggle="modal" data-target="#update-invoice" data-json='<?php echo json_encode($invoice) ?>'>Edit</a>&nbsp
                                <a class="btn btn-success btn-invoice-remove" data-method="POST" data-action="<?php echo base_url(); ?>dashboard/v2/Delete_Invoice" data-form="invoice_id=<?php echo $invoice->id ?>">Delete</a>
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
          "aaSorting": []
        });
      });
    </script>
</div>

<div class="modal fade" id="create-invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Create Invoice</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Invoice_Create", 'id="form-invoice-create"'); ?>
      <div class="modal-body">
        <div class="col-xs-12 custom-input">
            <div class="col-sm-12">
              <label for="order_id" class="col-sm-3 control-label">Order</label>
              <div class="col-sm-9">
                <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                <!-- <select name="order_id" class="form-control" style=""> -->
                <input name="order_id" type="text" class="form-control" placeholder="" value="" list="orders" id="order">
                <datalist id="orders">
        <?php
                foreach ($order_list as $order) {
                    ?>
                    <option data-value="<?php echo $order->order_id ?>" value="<?php echo $order->order_id ?>" ><?php echo $order->order_name ?> - <?php echo $order->product_name ?></option>
        <?php
                } ?>
                </datalist>
                <input name="order_id" type="hidden" class="form-control" placeholder="" value="" id="order-hidden">
                <!-- </select> -->
              </div>
            </div>
            <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Title</label>
              <div class="col-sm-9">
                <input type="text" name="title" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="detail" class="col-sm-3 control-label">Quantity</label>
              <div class="col-sm-9">
                  <input type="number" name="quantity" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="detail" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-9">
                <textarea name="description" class="form-control"></textarea>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Price</label>
              <div class="col-sm-9">
                  <div class="input-icon input-icon-left">
                    <i>IDR</i>
                        <input type="text" name="price" class="form-control" placeholder="0.0" value="">
                  </div>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Discount</label>
              <div class="col-sm-9">
                <input type="text" name="discount" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Total</label>
              <div class="col-sm-9">
                  <div class="input-icon input-icon-left">
                    <i>IDR</i>
                        <input type="text" name="total" class="form-control" placeholder="0.0" value="">
                  </div>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-9">
                <select name="status" class="form-control" style="">
                    <option value="0" selected>Unpaid</option>
                    <option value="1">Paid</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="due_date" class="col-sm-3 control-label">Due Date</label>
              <div class="col-sm-9">
                <input type="text" class="form-control datepick" data-date-format="yyyy-mm-dd" name="due_date" class="form-control" placeholder="" value="">
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


<div class="modal fade" id="update-invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Edit Invoice</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Update_Invoice", 'id="form-invoice-update"'); ?>
      <div class="modal-body">
        <div class="col-xs-12 custom-input">
            <div class="col-sm-12">
              <label for="order_id" class="col-sm-3 control-label">Order</label>
              <input type="hidden" name="id" value="" />
              <div class="col-sm-9">
                <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                <select name="order_id" class="form-control" style="">
        <?php
                foreach ($order_list as $order) {
                    ?>
                    <option value="<?php echo $order->order_id ?>"><?php echo $order->order_name ?> - <?php echo $order->product_name ?></option>
        <?php
                } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Title</label>
              <div class="col-sm-9">
                <input type="text" name="title" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Quantity</label>
              <div class="col-sm-9">
                <input type="text" name="quantity" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="detail" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-9">
                <textarea name="description" class="form-control"></textarea>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Price</label>
              <div class="col-sm-9">
                  <div class="input-icon input-icon-left">
                    <i>IDR</i>
                        <input type="text" name="price" class="form-control" placeholder="0.0" value="">
                  </div>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Discount</label>
              <div class="col-sm-9">
                <input type="text" name="discount" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Total</label>
              <div class="col-sm-9">
                  <div class="input-icon input-icon-left">
                    <i>IDR</i>
                        <input type="text" name="total" class="form-control" placeholder="0.0" value="">
                  </div>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="price" class="col-sm-3 control-label">Status</label>
              <div class="col-sm-9">
                <select name="status" class="form-control" style="">
                    <option value="0">Unpaid</option>
                    <option value="1">Paid</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="due_date" class="col-sm-3 control-label">Due Date</label>
              <div class="col-sm-9">
                <input type="text" class="form-control datepick" data-date-format="yyyy-mm-dd" name="due_date" class="form-control" placeholder="" value="">
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
