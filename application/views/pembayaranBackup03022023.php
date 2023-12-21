<?php include('header.php'); ?>
    <style>
        div.radio-with-Icon {
            display: block;
        }
        div.radio-with-Icon p.radioOption-Item {
            display: inline-block;
            width: 200px;
            height: auto;
            box-sizing: border-box;
            margin: 0px 15px;
            border: none;
        }
        div.radio-with-Icon p.radioOption-Item label {
            display: block;
            height: 100%;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            color: #de1831;
            cursor: pointer;
            opacity: .8;
            transition: none;
            font-size: 13px;
            padding-top: 25px;
            text-align: center;
            margin: 0 !important;
            border:1px solid #cccccc;
        }
        div.radio-with-Icon p.radioOption-Item label:hover, div.radio-with-Icon p.radioOption-Item label:focus, div.radio-with-Icon p.radioOption-Item label:active {
            opacity: .5;
            border: 1px solid #079054;
            color: #fff;
            margin: 0 !important;
        }
        div.radio-with-Icon p.radioOption-Item label::after, div.radio-with-Icon p.radioOption-Item label:after, div.radio-with-Icon p.radioOption-Item label::before, div.radio-with-Icon p.radioOption-Item label:before {
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
            margin: 0 !important;
        }
        div.radio-with-Icon p.radioOption-Item label i.fa {
            display: block;
            font-size: 50px;
        }
        div.radio-with-Icon p.radioOption-Item input[type="radio"] {
            opacity: 0 !important;
            width: 0 !important;
            height: 0 !important;
        }
        div.radio-with-Icon p.radioOption-Item input[type="radio"]:active ~ label {
            opacity: 1;
        }
        div.radio-with-Icon p.radioOption-Item input[type="radio"]:checked ~ label {
            opacity: 1;
            border: none;
            border: 1px solid #079054;
            color: #fff;
        }
        div.radio-with-Icon p.radioOption-Item input[type="radio"]:hover, div.radio-with-Icon p.radioOption-Item input[type="radio"]:focus, div.radio-with-Icon p.radioOption-Item input[type="radio"]:active {
            margin: 0 !important;
        }
        div.radio-with-Icon p.radioOption-Item input[type="radio"] + label:before, div.radio-with-Icon p.radioOption-Item input[type="radio"] + label:after {
            margin: 0 !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <div class="container container-details" style="margin-top:80px;">
        <h1 class="price-text"><b>Halaman Pembayaran</b></h1>
        <h3><b>Invoice  - <?= $invoice->id ?></b></h3>
        <p><?= $invoice->description ?></p>
        <form action="<?php echo base_url(); ?>proses_pembayaran" method="post">
            <div class="col-md-12">
                
                <div class="col-md-8">
                    <div class="radio-with-Icon">
                        <h3><b>Metode Pembayaran</b></h3><br>
                        <h4>Credit Card</h4>
                        <p class="radioOption-Item">
                            <input type="radio" class="method" name="method" value="CREDIT_CARD" add="2000" fee="2.9" types="both" id="BannerType6" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType6" style="padding-top: 7%;">
                                <img style="width: 90%;" src="<?= base_url() ?>asset/img/logo_cc.PNG">
                            </label>
                        </p>
                        

                        <h4>Bank Transfer</h4>
                        
                        <p class="radioOption-Item">
                            <input type="radio" name="method" class="method"  value="BCA" id="BannerType0" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType0" style="padding-top: 7%;">
                                <img style="width: 50%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/2560px-Bank_Central_Asia.svg.png">
                            </label>
                        </p>
                        <p class="radioOption-Item">
                            <input type="radio" name="method" class="method"  value="MANDIRI" id="BannerType1" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType1" style="padding-top: 7%;">
                                <img style="width: 50%;" src="https://cdn.contactcenterworld.com/images/company/pt-bank-mandiri-persero-tbk-1200px-logo.jpg">
                            </label>
                        </p>
                        <p class="radioOption-Item">
                            <input type="radio" value="BNI" class="method" name="method" id="BannerType2" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType2" style="padding-top: 7%;">
                                <img style="width: 50%;" src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png">
                            </label>
                        </p>
                        <p class="radioOption-Item">
                            <input type="radio" value="PERMATA" class="method"name="method" id="BannerType3" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType3" style="padding-top: 7%;">
                                <img style="width: 60%;" src="<?= base_url() ?>asset/img/logo_permata.png">
                            </label>
                        </p>
                        <p class="radioOption-Item">
                            <input type="radio" name="method" class="method" value="BSI" id="BannerType4" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType4" style="padding-top: 7%;">
                                <img style="width: 50%;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/2560px-Bank_Syariah_Indonesia.svg.png">
                            </label>
                        </p>
                        <p class="radioOption-Item">
                            <input type="radio" name="method" class="method" value="BRI" id="BannerType5" fee="5000" types="nominal" value="true" class="ng-valid ng-dirty ng-touched ng-empty" aria-invalid="false" style="">
                            <label for="BannerType5" style="padding-top: 7%;">
                                <img style="width: 60%;" src="<?= base_url() ?>asset/img/bri-logo.svg">
                            </label>
                        </p>
                    
                    
                </div>
                <br>
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
            <button type="submit" class="btn btn-peponi" id="btn-lanjutkan" style="width:50%" disabled>LANJUTKAN</button>
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

<script src="<?php echo base_url(); ?>js/main.js?v=25.654" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script>
    function thousands_separators(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return num_parts.join(",");
    }
    $(document).on("click",".method",function(){
        
        payment($(this));
    });
    function payment(option) {
       
        var total = $('#total').val();
        var type = option.attr('types');
        var add = option.attr('add');
        var fee =option.attr('fee');
        var grand = parseInt(total);
        var grand_fee = 0;
        if(type=="nominal") grand_fee+=parseInt(fee);
        else if(type=="percent") grand_fee+=(total*parseFloat(fee)/100);
        else if(type=="both") grand_fee+=(total*parseFloat(fee)/100)+parseInt(add);
        grand+=grand_fee;
        
        $('#payment_fee').html("Rp. "+thousands_separators(grand_fee));
        $('#total_tagihan').html("Rp. "+thousands_separators(grand));
        $('#grandtotal').val(grand);
        $('#btn-lanjutkan').removeAttr("disabled");
    }
</script>

</body>

</html>