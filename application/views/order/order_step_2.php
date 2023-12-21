<?php $this->view('header'); ?>

<section class='body-content'>
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#">Isi Data Pemesanan</a></li>
                <li><a href="#" class="active">Rincian & Cara Pembayaran</a></li>
                <li><a href="#">Selesai</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="summary-box">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class='left-15'><b class="left-30"> Rincian Pemesanan </b></h3>
                        </div>
                        <div class="col-sm-4">
                            <!--<a class="pull-right peponi-anchor" href="<?php echo $backUrl; ?>"><h3>Edit</h3></a>-->
                            
                        </div>
                    </div>
                    <div class="summary-content-box bottom-20">
                        <b><?php echo $product->product_name; ?></b>
                        <p> est. berangkat <?php echo $product->product_date; ?></p>

                        <?php foreach ($productPrices as $index => $productPrice): ?>
                            <?php if (isset(${"ageGroup" . $productPrice->age_group_id})): ?>
                                <hr class="peponi-divider">
                                <div class="row">
                                    <div class="col-xs-6"> Jumlah <?php echo $productPrice->age_group_name; ?></div>
                                    <div class="col-xs-6 grey-text"> <?php echo ${"ageGroup" . $productPrice->age_group_id}; ?> orang</div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </div>

                    <?php if ($voucher): ?>
                        <div class="row bottom-20">
                            <div class="col-xs-6"><b class="left-30">Kode Voucher </b></div>
                            <div class="col-xs-6 grey-text"><b><?php echo $voucher; ?></b></div>
                        </div>
                    <?php endif; ?>

                    <div class="row bottom-20">
                        <div class="col-xs-6 left-15"><b class="left-30">Biaya Terendah</b></div>
                        <div class="col-xs-6 price-text"><b><?php echo currency_format($totalPrice); ?></b></div>
                    </div>
                    <div class='red-text'><?php echo form_error('voucher'); ?></div>
                </div>
            </div>
            <div class="col-md-6">
           
                    <div class="summary-box">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class='left-15'><b class="left-30"> Data Pemesanan </b></h3>
                            </div>
                            <div class="col-sm-6 text-right" style="margin-top: 2%;">
                                <!--<a class="pull-right peponi-anchor" href="<?php echo $backUrl; ?>"><h3>Edit</h3></a>-->
                                <button type="button" class="btn btn-primary" id="toggle_see_btn" onclick="toggleSee()"></button>
                            </div>
                        </div>
                        <?php for ($i=0; $i < count($fullname); $i++) {
                            $class = "";
                            if($i>0)$class="other-form";    
                        ?>
                            <div class="summary-content-box bottom-20 <?= $class ?>">
                                <div class="row top-20">
                                    <div class="col-md-12">
                                        <b><?php echo $gender[$i].' '.$fullname[$i]; ?></b>
                                    </div>
                                </div>
                                <hr class="peponi-divider">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-normal">No. Telepon / Whatsapp | <span class="text-hightlight"><?php echo $phone[$i]; ?></span>
                                        </p>
                                    </div>
                                </div>

                                <hr class="peponi-divider">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-normal">E-mail | <span class="text-hightlight"><?php echo $email[$i]; ?></span></p>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-md-12">
                <h3><b>Rincian Pembayaran</b></h3>
                <div class="col-md-8">
                    <table class='table table-responsive borderless'>
                        <tr>
                            <th>Down Payment (DP)</th>
                            <td><?php echo currency_format($pricePerPax); ?> / pax</td>
                        </tr>
                        <tr>
                            <th>Jumlah Pax</th>
                            <td><?php echo $totalPax; ?></td>
                        </tr>
                        <tr>
                            <th>Total Down Payment (DP)</th>
                            <td class='price-text'><?php echo currency_format($totalPax * $pricePerPax); ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Jatuh Tempo</th>
                            <td class='red-text' id="expired-date"></td>
                        </tr>
                    </table>
                    
                    <br>
                    <p>
                    Dengan mengklik tombol ini, Anda mengakui bahwa Anda telah membaca dan
                    menyetujui <u><a href="<?php echo base_url(); ?>terms-conditions"  target="_blank">Terms & Conditions</a></u> dan <u><a href="<?php echo base_url(); ?>terms-conditions"  target="_blank">Privacy Policy</a></u> Peponi
                        </p>
                </div>
            </div>
        </div>

        <?php

        ?>
        <div class="row">
            <div class="row">
                    <div class="col-md-5 top-50">
                        <form method="POST" action="<?php echo current_full_url(); ?>">
                            <input type="hidden" name="paymentMethod" id="payment-method" value=""/>

                            <input type="hidden" name="tpax" value='<?php echo json_encode($totalPax); ?>'/>
                            <input type="hidden" name="fullname" value='<?php echo json_encode($fullname); ?>'/>
                            <input type="hidden" name="phone" value='<?php echo json_encode($phone); ?>'/>
                            <input type="hidden" name="email" value='<?php echo json_encode($email); ?>'/>
                            <input type="hidden" name="gender" value='<?php echo json_encode($gender); ?>'/>
                            <input type="hidden" name="method" value='<?php echo json_encode($email); ?>'/>
                            <input type="hidden" name="voucher" value="<?php echo isset($voucher) ? $voucher : ''; ?>"/>

                            <?php foreach ($productPrices as $index => $productPrice): ?>
                                <?php if (isset(${"ageGroup" . $productPrice->age_group_id})): ?>
                                    <input
                                        type="hidden"
                                        name="<?php echo "ageGroup" . $productPrice->age_group_id; ?>"
                                        value="<?php echo ${"ageGroup" . $productPrice->age_group_id}; ?>"
                                    />
                                <?php endif; ?>
                            <?php endforeach; ?>
        
                            <button type="submit" class="btn btn-peponi">LANJUTKAN</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</section>

<?php $this->view('footer'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.21/moment-timezone-with-data-2012-2022.min.js"></script>

<script type="text/javascript">
  var paymentMethodInput      = $('#payment-method');
  var paymentMethodBtn        = $('.payment-method-btn');
  var activePaymentMethodBtn  = $('.payment-method-option.active').find('a');
  var see_all = 1;
  toggleSee();
  function toggleSee() {
    console.log("masuk");
    if(see_all==0){
        //membuka
        see_all=1;
        $('.other-form').slideDown();
        $('#toggle_see_btn').html("Hide");
    }
    else{
        //menutub
        see_all=0;
        $('.other-form').slideUp();
        $('#toggle_see_btn').html("See All");
    }
  }
  function updatePaymentMethod(event) {
    var paymentMethodId = '';
    if (event) {
      var target        = event.target;
      paymentMethodId   = $(target).data('id');
    } else {
      paymentMethodId   = activePaymentMethodBtn.data('id');
    }

    paymentMethodInput.val(paymentMethodId);
  }

    Date.prototype.addHours= function(h){
        this.setHours(this.getHours()+h);
        return this;
    }
    
  $(function() {
    var zone = 'Asia/Jakarta';
    var nowDate = new Date();
    var expiredDate = nowDate.addHours(3);
    var formatedExpiredDate = moment(expiredDate).tz(zone).format('dddd, D MMMM YYYY [Jam] HH:mm z');
    $('#expired-date').append(formatedExpiredDate);

    updatePaymentMethod();

    paymentMethodBtn.click(function(e){
      updatePaymentMethod(e);
    });
  });
</script>

</body>

</html>
