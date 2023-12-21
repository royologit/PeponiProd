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
              <a class="btn btn-success pull-right" data-toggle="modal" data-target="#create-private-trip">Add New</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="dttable" class="table table-bordered table-hover" data-detail-url="<?php echo base_url()?>dashboard/v2/Invoice_Detail">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Package Name</th>
                            <th>Description</th>
                            <th>Trip Schedule</th>
                            <!-- <th>Person</th> -->
                            <th>Price</th>
                            <th>Created Date</th>
                            <th>Updated Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                      foreach ($result as $private_trip) {
                          ?>
                        <tr data-id="<?php echo $private_trip->id?>">
                            <td><?php echo $private_trip->id ?></td>
                            <td><?php echo $private_trip->name ?></td>
                            <td><?php echo $private_trip->package_name ?></td>
                            <td><?php echo $private_trip->description ?></td>
                            <td><?php echo date("d M Y", strtotime($private_trip->start_date)) ?> - <?php echo date("d M Y", strtotime($private_trip->end_date)) ?></td>
                            <td>
                            <?php
                            $private_trip->age_price = json_decode($private_trip->age_price);
                          if (isset($private_trip->age_price) && count($private_trip->age_price) > 0) {
                              foreach ($private_trip->age_price as $age_price) {
                                  echo "Biaya ".$age_price[0] . ": IDR " . number_format($age_price[2], 0, ".", ",") . "<br>";
                              }
                          } ?>
                            </td>
                            <!-- <td><?php echo $private_trip->person ?></td>
                            <td><?php echo $private_trip->price ?></td> -->
                            <td><?php echo $private_trip->created_date ?></td>
                            <td><?php echo $private_trip->updated_date ?></td>
                            <td><a class="btn btn-success" data-toggle="modal" data-target="#update-private-trip" data-json='<?php echo json_encode($private_trip) ?>'>Edit</a>&nbsp
                                <a class="btn btn-success btn-private-trip-remove" data-method="POST" data-action="<?php echo base_url(); ?>dashboard/v2/Delete_Private_Trip" data-form="private_trip_id=<?php echo $private_trip->id ?>">Delete</a>
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

<div class="modal fade" id="create-private-trip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Create <?php echo $name ?></h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Create_Private_Trip", 'id="form-private-trip-create"'); ?>
      <div class="modal-body">
        <div class="col-xs-12 custom-input">
            <div class="col-sm-12">
              <label for="order_id" class="col-sm-3 control-label">Package</label>
              <div class="col-sm-9">
                <!-- <input type="text" name="order_id" class="form-control" placeholder="" value=""> -->
                <select name="package_id" class="form-control" style="">
        <?php
                foreach ($package_list as $package) {
                    ?>
                    <option value="<?php echo $package->package_id ?>"><?php echo $package->package_name ?></option>
        <?php
                } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Name</label>
              <div class="col-sm-9">
                <input type="text" name="name" class="form-control" placeholder="" value="">
              </div>
            </div>
            <div class="col-sm-12">
              <label for="detail" class="col-sm-3 control-label">Description</label>
              <div class="col-sm-9">
                <textarea name="description" class="form-control"></textarea>
              </div>
            </div>
            <div class="col-sm-12">
              <label for="start_date" class="col-sm-3 control-label">Trip Schedule</label>
              <div class="col-sm-5">
                <input type="text" name="start_date" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value=""> -
              </div>
              <div class="col-sm-4">
                <input type="text" name="end_date" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
              </div>
            </div>
            <!-- <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Person</label>
              <div class="col-sm-9">
                <input type="text" name="person" class="form-control" placeholder="" value="">
              </div>
            </div> -->
            <?php
            foreach ($age_groups as $age) {
                ?>
                    <div class="col-sm-12">
                      <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?> Price</label>
                      <div class="col-sm-9">
                          <div class="input-icon input-icon-left">
                            <i>IDR</i>
                                <input type="text" name="age[<?php echo $age->age_group_id?>]" data-id="<?= $age->age_group_id?>" class="form-control" placeholder="0.0" value="">
                          </div>
                      </div>
                    </div>
            <?php
            } ?>
            <!-- <div class="col-sm-12">
              <label for="title" class="col-sm-3 control-label">Price</label>
              <div class="col-sm-9">
                <input type="text" name="price" class="form-control" placeholder="" value="">
              </div>
            </div> -->
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


<div class="modal fade" id="update-private-trip" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-v2" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <div class="col-lg-8"><h5 class="modal-title" id="exampleModalLabel">Edit <?php echo $name; ?></h5></div>
        <div class="col-lg-4"><button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
      </div>
    <?php echo form_open("dashboard/v2/Update_Private_Trip", 'id="form-private-trip-update"'); ?>
      <div class="modal-body">
          <div class="col-xs-12 custom-input">
              <input type="hidden" name="id" class="form-control" placeholder="" value="">
              <div class="col-sm-12">
                <label for="order_id" class="col-sm-3 control-label">Package</label>
                <div class="col-sm-9">
                  <select name="package_id" class="form-control" style="">
          <?php
                  foreach ($package_list as $package) {
                      ?>
                      <option value="<?php echo $package->package_id ?>"><?php echo $package->package_name ?></option>
          <?php
                  } ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Name</label>
                <div class="col-sm-9">
                  <input type="text" name="name" class="form-control" placeholder="" value="">
                </div>
              </div>
              <div class="col-sm-12">
                <label for="detail" class="col-sm-3 control-label">Description</label>
                <div class="col-sm-9">
                  <textarea name="description" class="form-control"></textarea>
                </div>
              </div>
              <div class="col-sm-12">
                <label for="start_date" class="col-sm-3 control-label">Trip Schedule</label>
                <div class="col-sm-5">
                  <input type="text" name="start_date" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value=""> -
                </div>
                <div class="col-sm-4">
                  <input type="text" name="end_date" class="datepick form-control" data-date-format="yyyy-mm-dd" placeholder="" value="">
                </div>
              </div>
              <!-- <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Person</label>
                <div class="col-sm-9">
                  <input type="text" name="person" class="form-control" placeholder="" value="">
                </div>
              </div> -->
              <?php
              foreach ($age_groups as $age) {
                  ?>
                      <div class="col-sm-12">
                        <label for="price" class="col-sm-3 control-label"><?php echo $age->age_group_name ?> Price</label>
                        <div class="col-sm-9">
                            <div class="input-icon input-icon-left">
                              <i>IDR</i>
                                  <input type="text" name="age[<?php echo $age->age_group_id?>]" data-id="<?= $age->age_group_id?>" class="form-control" placeholder="0.0" value="">
                            </div>
                        </div>
                      </div>
              <?php
              } ?>
              <!-- <div class="col-sm-12">
                <label for="title" class="col-sm-3 control-label">Price</label>
                <div class="col-sm-9">
                  <input type="text" name="price" class="form-control" placeholder="" value="">
                </div>
              </div> -->
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
