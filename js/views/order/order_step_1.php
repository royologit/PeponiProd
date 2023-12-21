<?php $this->view('header'); ?>
<style>
  .form-group select {
    height: 40px;
}

.form-group select, .form-group textarea {
    border: 3px solid #d3d3d3;
    font-size: 18px;
}
.error-msg {
  color:red;
}
</style>
<!-- jQuery -->
<script src="<?php echo base_url(); ?>js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

<!-- Slickr Carousel -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>

<!-- ScrollTo-->
<script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>
<section class='body-content'>
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="" class="active">Isi Data Pemesanan</a></li>
                <?php if ($orderType != 'private'): ?>
                    <li><a href="#">Rincian & Cara Pembayaran</a></li>
                <?php endif; ?>
                <li><a href="">Selesai</a></li>
            </ol>
        </div>
        <form id="peponiForm" method="POST" action="<?php echo current_full_url(); ?>">
        <div class="row">
           
                <div class="col-md-6">
                    <?php if ($orderType == 'private'): ?>
                        <h3><b>Rincian Pemesanan Private Trip</b></h3>

                        <div class="row">
                            <div class="form-group col-sm-8">
                                <label for="date-range">Jadwal Trip</label>
                                <input type="text" class="form-control" id="date-range" name="date-range" value="<?php echo set_value('date-range'); ?>">
                                <?php echo form_error('date-range'); ?>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="duration">Durasi</label>
                                <input class="form-control" id="duration" disabled="disabled" value="0 Hari">
                            </div>
                        </div>
                    <?php else: ?>
                        <h3><b>Rincian Pemesanan Open Trip</b></h3>
                    <?php endif; ?>

                    <div class="summary-content-box bottom-20"> <!-- Form Peserta -->

                        <?php foreach ($productPrices as $index => $productPrice): ?>
                            <?php $inputName = "ageGroup" . $productPrice->age_group_id; ?>

                            <div class="row <?php echo($index == 0 ? "top-20" : "") ?>">
                                <div class="col-sm-8">
                                    <b>Jumlah <?php echo $productPrice->age_group_name . " (" . $productPrice->age_group_description . ")"; ?></b>

                                    <?php if ($orderType != 'private'): ?>
                                        <p class="grey-text"><?php echo currency_format($productPrice->product_price); ?> </p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4 pull-right">
                                    <div class="center">
                                        <div class="input-group">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number"
                                                        data-type="minus"
                                                        data-field="<?php echo $inputName; ?>"
                                                        onclick="DeleteForm()"
                                                        
                                                        >
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </button>
                                            </span>
                                            <input
                                                    type="text"
                                                    class="form-control input-number"
                                                    name="<?php echo $inputName; ?>"
                                                    data-price="<?php echo $productPrice->product_price; ?>"
                                                    value="<?php echo set_value($inputName, ($inputData ? (property_exists($inputData, $inputName) ? $inputData->{$inputName} : "0") : "0")); ?>"
                                                    min="0"
                                                    max="100"
                                                    value="0"
                                            />
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-number" onclick="newForm()"
                                                        data-type="plus"
                                                        data-field="<?php echo $inputName; ?>">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if ($index < (count($productPrices) - 1)): ?>
                                <hr class="peponi-divider">
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php echo count($productPrices > 0) ? form_error("ageGroup" . $productPrices[0]->age_group_id) : ''; ?>

                    </div>

                    <?php if ($orderType == 'private'): ?>
                        <p class="small-text"><sup>&#42</sup>Jumlah minimal peserta private trip adalah 6 orang.</p>

                        <div class="form-group">
                            <label for="note">Permintaan Khusus</label>
                            <textarea class="form-control" id="note" name="note" rows="5"
                                      placeholder="Anda dapat mendeskripsikan jenis maskapai, akomodasi, dsb. sesuai yang anda inginkan."><?php echo set_value('note'); ?></textarea>
                        </div>
                    <?php endif; ?>


                    <div class="form-horizontal form-left">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="voucher-input">Kode Voucher</label>
                            <div class="col-sm-8">
                                <div class="input-group" id="voucher-input-group">
                                    <input type="text" autocomplete="off" class="form-control" id="voucher-input"
                                           name="voucher"
                                           value="<?php echo set_value('voucher', ($inputData ? $inputData->voucher : '')); ?>"
                                           placeholder="Masukan Kode Voucher"
                                    >
                                    <span class="input-group-btn">
                                        <input type="button" class="btn btn-default btn-number" value="Check" id="voucher-input-btn"/>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden">
                            <label class="control-label col-sm-4">Discount</label>
                            <label class="control-label col-sm-8" id="voucher-amount-text">- Rp. 0</label>
                            <input type="hidden" id="voucher-amount" value="0"/>
                        </div>

                        <?php if ($orderType != 'private'): ?>
                            <div class="form-group">
                                <label class="control-label col-sm-4 pull-left" for="total-price">Total Harga</label>
                                <label class="control-label col-sm-8 pull-left price-text" for="total-price"
                                       id="total-price">
                                    <?php echo currency_format(0) ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6" style="border-left: 1px solid #d3d3d3">
                    
                </div>
        </div>
        <div id="form-pemesanan">
          <div class="panel-group" id="accordion">
            
          </div>
        </div>
        <div class="text-right">
              <button type="submit" id="btn-peponi" class="btn btn-peponi">LANJUTKAN</button>
            </div>
      </form>
    </div>
    </div>
    <input type="hidden" id="productPrices" value='<?= json_encode($productPrices)?>'>
</section>
<?php
  $this->load->view("footer")
?>
<script src="<?php echo base_url(); ?>js/minus-plus-input.js"></script>
<script src="<?php echo base_url(); ?>js/accounting.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script type="text/javascript" src="https:////cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
<script src="https://ajax.microsoft.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>

<script type="text/javascript">
  var productId          = <?php echo $productId; ?>;
  var peponiForm         = $('#peponiForm');
  var totalPrice         = $('#total-price');
  var participantInput   = $('.input-number');
  var voucherInput       = $('#voucher-input');
  var voucherInputBtn    = $('#voucher-input-btn');
  var voucherAmount      = $('#voucher-amount');
  var voucherAmountText  = $('#voucher-amount-text');
  var voucherInputAlert  = $('#vocuher-input-alert');

  function numberWithThousandSeparator(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function calculateTotalPrice() {
    if (totalPrice) {
      var total = 0;
      participantInput.each(function () {
        var price = $(this).data('price');
        var value = $(this).val();

        total += (price * value);
      });

      total -= voucherAmount.val();

      totalPrice.html("Rp. " + numberWithThousandSeparator(total));
    }
  }

  function clearVoucherInput() {
    voucherAmount.val(0);
    voucherAmountText.parent().addClass('hidden');
    voucherInputBtn.val('Check');

    calculateTotalPrice();
  }

  $(function () {
    $('input[name="date-range"]').daterangepicker({
      autoApply: true
    });

    $('#date-range').on('apply.daterangepicker', function (ev, picker) {
      var startDate = moment(picker.startDate.format('YYYY-MM-DD'));
      var endDate = moment(picker.endDate.format('YYYY-MM-DD'));
      // var duration = endDate.getDate() - startDate.getDate();
      $('#duration').val(endDate.diff(startDate, 'days') + 1 + ' Hari');
    });

    $.validator.addMethod("regex", function (value, element, regexpr) {
      return regexpr.test(value);
    });

    $.validator.addMethod("validVoucher", function (value) {
      if (voucherInput.is(":focus") || voucherInput.attr('valid')) {
        return true;
      }

      if (!value) {
        clearVoucherInput();
        return true;
      }

      $.ajax({
        method: 'POST',
        url: '<?php echo base_url(); ?>voucher/xmlhttp_check_voucher_code',
        data: {
          'voucher_code': value,
          'product_id'  : productId
        },
        beforeSend: function() {
          voucherInput.removeClass('error');
          voucherInput.removeAttr('valid');
          voucherInputAlert.addClass('hidden');
          voucherInputBtn.val('Loading');
        },
        success: function (response) {
          if(response.valid) {
            voucherInput.attr('valid', true);
            voucherInputBtn.val('Valid');
            voucherAmountText.parent().removeClass('hidden');
            voucherAmountText.html("- Rp. " + numberWithThousandSeparator(response.voucher.voucher_amount));
            voucherAmount.val(response.voucher.voucher_amount);

            peponiForm.validate().element("#voucher-input");

            calculateTotalPrice();
          } else {
            clearVoucherInput();
          }
        },
        error: function() {
          clearVoucherInput();
        }
      });
    });

    peponiForm.validate({
      rules: {
        fullname: {
          required: true
        },
        phone: {
          regex: /^0\d{8,13}$/
        },
        email: {
          email: true,
          required: true
        },
        voucher: {
          validVoucher: true
        }
      },
      messages: {
        fullname: {
          required: "Please enter your full name."
        },
        phone: {
          regex: "Please enter a valid phone number (starts with 0)."
        },
        email: {
          email: "Please enter a valid email address.",
          required: "Please enter your email."
        },
        voucher: {
          validVoucher: "Voucher code is invalid."
        }
      },
      errorPlacement: function(error, element) {
        if (element.attr("id") == "voucher-input" ) {
          error.insertAfter("#voucher-input-group");
        } else {
          error.insertAfter(element);
        }
      }
    });

    participantInput.change(function () {
      calculateTotalPrice();
    });

    voucherInput.change(function() {
      $(this).removeAttr('valid');
    });

    voucherInputBtn.click(function() {
      peponiForm.validate().element("#voucher-input");
    });

    voucherInputBtn.trigger('click');
  
  });
  
function newForm() {
    var age = JSON.parse($('#productPrices').val());
    console.log(age);
    var title = "Data Pemesanan";
    var deskripsi = "";
    if($('#form-pemesanan .panel-default').size()<=0){
      title += " Leader"
      deskripsi += "Rincian di bawah ini wajib diisi lengkap untuk proses pemesanan, pengirim E-tiket, dan informasi lainnya. "
    }
    else{
      title += " #"+($('#form-pemesanan .panel-default').size()+1)
    }
    var element=`
          <div class="panel panel-default" >
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse-${$('#form-pemesanan .panel-default').size()}" >
            <div class="panel-heading">
              <h1 class="panel-title" style="padding:2%;font-size:16pt">
               
               <b>${title}</b>
              </h1>
            </div></a>
            <div id="collapse-${$('#form-pemesanan .panel-default').size()}" class="panel-collapse collapse in" style="padding:5%;padding-top:2%">
              <p>${deskripsi}</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap (Sesuai Paspor)</label>
                            <input type="text"  header_id="collapse-${$('#form-pemesanan .panel-default').size()}" labels="nama" autocomplete="off" class="form-control" id="fullname" name="fullname[]"
                                  placeholder="Ex: Amriyanto Wilinata"
                                  value=""
                            >
                            <label class="error-msg error-msg-nama"></label>
                        </div>
                        <div class="form-group">
                            <label for="phone">Nomor Telepon / Whatsapp</label>
                            <input type="text" labels="nomer" header_id="collapse-${$('#form-pemesanan .panel-default').size()}"  autocomplete="off" class="form-control" id="phone" name="phone[]"
                                  placeholder="Ex: 08781234553"
                                  value=""
                            >
                            <label class="error-msg error-msg-nomer"></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                       ` 
                       if($('#form-pemesanan .panel-default').size()<=0){
                        element+=`<div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" labels="email" header_id="collapse-${$('#form-pemesanan .panel-default').size()}"  autocomplete="off" class="form-control" id="email" name="email[]"
                                placeholder="Ex: ampal@email.com"
                                value=""
                            >
                            <label class="error-msg error-msg-email"></label>
                        </div>`
                       }
                      
                        element+= `<p class="small-text" style="margin-bottom: 10px"><sup>&#42</sup>Mohon pastikan alamat email serta data yang anda masukan benar.
                            E-Voucher dan informasi lainnya akan dikirimkan ke alamat email tersebut</p>
                        <div class="form-group">
                            <label for="email">Package</label>
                            <select class="form-control" name="package" id="package[]">`
                            age.forEach(item => {
                                element+=` 
                                    <option value="${item.age_group_id}">${item.age_group_name}</option>
                                `
                            });
                            
                        element+=`</select></div>
                        <p class="small-text"><sup>&#42</sup>Mohon pastikan sudah menambahkan jumlah peserta dan package yang sesuai pada bagian kiri</p>
                    </div>
                  </div>
              </div>
            </div>
          </div>
  `
    $('#form-pemesanan').append(element);
  }
  function DeleteForm() {
    console.log($('#form-pemesanan .panel-default').size());  
    $('.panel-default')[$('#form-pemesanan .panel-default').size()-1].remove();
  }
  $('#peponiForm').submit(function(){
    var error = 0;
    $('#form-pemesanan .error-msg').html("");
    $('#form-pemesanan input').each(function(e){
        if($(this).val()==null||$(this).val()==""||$(this).val()==undefined){
          console.log("kosong");
          $("#"+$(this).attr("header_id")+" .error-msg-"+$(this).attr("labels")).html("Harap isi");
          error=1;
        }
    }); 
    if(error==1){
      return false;
    }
  })
</script>

</body>

</html>
