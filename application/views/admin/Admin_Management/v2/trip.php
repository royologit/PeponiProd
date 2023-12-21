<div class="content-wrapper" style="min-height:800px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php // echo str_replace('_',' ',$title) ?>
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
      
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="dttable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Trip Name</th>
                            <th>Price</th>
                            <th>Trip Schedule</th>
                            <th>Airlines</th>
                            <th>Participant</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php 
                  $no = 0;
                  foreach( $result as $trip) { 

                  ?>
                        <tr data-id="<?php echo $trip->product_id?>">
                            <td><?php  echo $trip->product_name ?></td>
                            <td>IDR <?php  echo number_format($trip->product_price,null,",",".") ?></td>
                            <td><?php  echo $trip->product_duration ?></td>
                            <td><?php  echo $trip->product_airlines ?></td>
                            <td id="trip-<?=$no?>"><?php echo $trip->participant ?></td>
                            <td><a index="<?=$no?>" class="btn btn-success detail-btn" data-toggle="modal" data-target="#participant-list" data-method="POST" data-action="<?php echo base_url() ?>dashboard/v2/Trip_Participant" data-form="product_id=<?= $trip->product_id ?>">View Participant</a></td>
                        </tr>
                <?php $no++;
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

<div class="modal fade" id="participant-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-participant" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Participant List</h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
                <table id="subtable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone/WA</th>
                            <th>Line ID</th>
                            <th>Email</th>
                            <th>Trip Schedule</th>
                            <th>Voucher Code</th>
                            <th>Note</th>
                            <th>Price</th>
                            <th>Total Payment/Total Invoice</th>
                            <th>Payment</th>
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
                              <th>Quantity</th>
                              <th>Price</th>
                              <th>Total</th>
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
  </div>
</div>
