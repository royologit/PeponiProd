<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 2.3.2
  </div>
  <strong>Copyright &copy; 2014-2015 <a href='<?php echo bower_url(); ?>/AdminLTE/http://almsaeedstudio.com'>Almsaeed Studio</a>.</strong> All rights
  reserved.
</footer>

<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/jQueryUI/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
$.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.5 -->
<script src="<?php echo bower_url(); ?>/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?php echo bower_url(); ?>/AdminLTE/https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="<?php echo bower_url(); ?>/AdminLTE/https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/fastclick/fastclick.js"></script>
<!-- jQuery Datatable -->
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo bower_url(); ?>/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo bower_url(); ?>/AdminLTE/dist/js/app.min.js"></script>

<script src="<?php echo base_url(); ?>js/main.js?v=25.655" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script>
    $('.datepick').datepicker();
    $('body').on('changeDate','.datepick', function(ev){
        $(this).datepicker('hide');
    });
    var base_url = "<?php echo base_url() ?>";
</script>
    <!-- message -->
    <div class="modal fade just-modal" tabindex="2" role="dialog" id="message-modal" style="z-index: 99992;">
      <div class="modal-dialog short-modal" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button> -->
            <h4 class="modal-title" id="message-title">Message</h4>
          </div>

          <div class="modal-body">
            <p id="message-result"></p>
          </div>

          <div class="modal-footer">
                <input type="submit" value="OK" class="btn btn-primary" id="message-button" data-dismiss="modal" aria-label="Close">
          </div>

        </div>
      </div>
    </div>
    <!-- message -->

    <!-- MESSAGE CONFIRM -->
    <div class="modal fade just-modal hotel-modal" tabindex="2" role="dialog" id="message-confirm" style="z-index: 99992;">
      <div class="modal-dialog short-modal" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            <h4 class="modal-title" id="message-title-confirm">Create Bed Type</h4>
          </div>

          <div class="modal-body">
            <p><span id="message-result-confirm"></span></p>
          </div>

            <div class="modal-footer">
                <input type="submit" name="submit" value="Yes, I am Sure" class="btn btn-primary pull-right" id="message-button-confirm">
                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary pull-right" id="message-button-cancel">Cancel</button>
            </div>
        </div>
      </div>
    </div>
    <!-- MESSAGE CONFIRM -->

    <!-- MESSAGE CONFIRMED -->
    <div class="modal fade just-modal hotel-modal" tabindex="2" role="dialog" id="message-confirmed" style="z-index: 99992;">
      <div class="modal-dialog short-modal" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
            <h4 class="modal-title" id="message-title-confirmed">Bed Type Successfully Created</h4>
          </div>

          <div class="modal-body">
            <p><span id="message-result-confirmed"></span></p>
          </div>

        </div>
      </div>
    </div>
    <!-- MESSAGE CONFIRMED -->

</body>
</html>
