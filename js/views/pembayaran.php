<?php include('header.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <div class="container container-details" style="margin-top:80px;">
        <h1 class="price-text"><b>Halaman Pembayaran</b></h1>
        <h3><b>Invoice  - <?= $invoice->id ?></b></h3>
        <p><?= $invoice->description ?></p>
        <form action="<?php echo base_url(); ?>proses_pembayaran" method="post">
            <div class="col-md-12">
                <h3><b>Metode Pembayaran</b></h3>
                <div class="col-md-8">
                    <select name="method" id="method" class="form-control text-hightlight" >
                        <option value="BNI" fee="4500" types="nominal" add="0" selected>BNI</option>
                        <option value="MANDIRI" fee="4500" types="nominal" add="0">MANDIRI</option>
                        <option value="PERMATA" fee="4500" types="nominal" add="0">PERMATA</option> 
                        <option value="BSI" fee="4500" types="nominal" add="0">BSI</option>
                        <option value="BJB" fee="4500" types="nominal" add="0">BJB</option>
                        <option value="CREDIT_CARD" fee="2.9" add="2000" types="both">CREDIT CARD</option>
                    </select>
                </div>
            </div>
            
            <div class="col-md-12">
                <h3><b>Rincian Pembayaran</b></h3>
                <div class="col-md-8">
                    <table class='table table-responsive borderless'>
                        <tr>
                            <th>Tagihan</th>
                            <td><?php echo currency_format($invoice->total); ?></td>
                        </tr>
                        <tr>
                            <th>Payment Fee</th>
                            <td class='red-text' id="payment_fee">Rp. 0</td>
                        </tr>
                        <tr>
                            <th>Total Tagihan </th>
                            <td class='price-text' id="total_tagihan"><?php echo currency_format($invoice->total); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <input type="hidden" name="invoice_id" value="<?php echo $invoice->id; ?>">
            <input type="hidden" name="total" id="total" value="<?php echo $invoice->total; ?>">
            <input type="hidden" name="grandtotal" id="grandtotal" value="">
            <button type="submit" class="btn btn-peponi" style="width:50%">LANJUTKAN</button>
        </form>
    </div>


   

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

<script src="<?php echo base_url(); ?>js/main.js?v=25.65" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script>
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return num_parts.join(",");
    }
    payment($('#method'));
    $(document).on("change","#method",function(){
        payment($(this));
    });
    function payment(option) {
        var total = $('#total').val();
        var type = option.find('option:selected').attr('types');
        var add = option.find('option:selected').attr('add');
        var fee =option.find('option:selected').attr('fee');
        var grand = parseInt(total);
        var grand_fee = 0;
        if(type=="nominal") grand_fee+=parseInt(fee);
        else if(type=="percent") grand_fee+=(total*parseFloat(fee)/100);
        else if(type=="both") grand_fee+=(total*parseFloat(fee)/100)+parseInt(add);
        grand+=grand_fee;
        $('#payment_fee').html("Rp. "+thousands_separators(grand_fee));
        $('#total_tagihan').html("Rp. "+thousands_separators(grand));
        $('#grandtotal').val(grand);
    }
</script>

</body>

</html>