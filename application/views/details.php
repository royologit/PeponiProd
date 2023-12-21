<?php include('header.php'); ?>

    <!-- Half Page Image Background Carousel Header -->


    <!-- Page Content -->
    <?php if($products->num_rows() > 0):?>
    <?php $product = $products->row(); ?>
    <div class="container container-details" style="margin-top:80px;">
        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <img class="preview-thumb" src="<?php echo base_url().$product->product_cover_image ?>" width="100%" style="padding:20px;" />
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="thumbs-list">
                            <?php foreach($product_images->result() as $pi): ?>
                            <div><img class="thumb" src="<?php echo base_url().$pi->product_image ?>" style="width:100%; padding:15px;" /></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="peponi-green-text"><?php echo $product->product_name; ?></h2>

                <?php if($product->product_highlight): ?>
                    <h3> Highlights </h3>
                    <p> <?php echo $product->product_highlight; ?></p>
                    <div style="width: 50%">
                        <a href="#product-detail" class="a-peponi">Pelajari Selengkapnya </a>
                    </div>
                <?php endif; ?>

                <?php if(count($order_types) > 0): ?>
                    <h3> Pilih Tipe Perjalanan Yang Tersedia </h3>
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php foreach($order_types as $index => $order_type): ?>
                            <li role="presentation" class="order-type-option <?php echo ($index == 0 ? "active" : "") ?>">
                                <a
                                        href="<?php echo "#order-type-" . $order_type->order_type_id ?>"
                                        aria-controls="<?php echo "order-type-" . $order_type->order_type_id ?>"
                                        data-url="<?php echo $order_type->url ?>"
                                        class="order-type-btn"
                                        role="tab"
                                        data-toggle="tab"
                                >
                                    <?php echo $order_type->order_type_name ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach($order_types as $index => $order_type): ?>
                            <div role="tabpanel" class="tab-pane <?php echo ($index == 0 ? "active" : "") ?>" id="<?php echo "order-type-" . $order_type->order_type_id ?>">
                                <?php echo $order_type->order_type_description ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <br />
                    <div style="width: 50%">
                        <a href="<?php echo base_url(); ?>package/<?php echo $product->product_id; ?>/order-open-trip/<?php echo $product->product_id; ?>" id="book-btn" class="btn btn-peponi">BOOK NOW </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <div class="row" id="product-detail" style="padding-top: 50px">
          <div class="col-lg-6">
            <p>
              <h2 class="peponi-green-text">Rundown Tour</h2>
              <?php echo $product->product_rundown_tour; ?>
            </p>
          </div>
        </div>
    </div>
  <?php else:?>

    <div class="container" style="margin-top:120px; min-height:400px;">
      <h3>Sorry, The Package You Searched Was Not Found.</h3>
    </div>
  <?php endif; ?>

<?php include('footer.php');?>

<script type="text/javascript">
    var bookBtn             = $('#book-btn');
    var orderTypeBtn        = $('.order-type-btn');
    var activeOrderTypeBtn  = $('.order-type-option.active').find('a');
    var currentUrl          = "<?php echo current_url(); ?>";

    function updateBookButtonUrl(event) {
        var urlPath = '';
        if (event) {
            var target  = event.target;
            urlPath     = $(target).data('url');
        } else {
            urlPath     = activeOrderTypeBtn.data('url');
        }

        bookBtn.attr('href', currentUrl + '/' + urlPath);
    }

    $(function() {
        updateBookButtonUrl();

        orderTypeBtn.click(function(e){
            updateBookButtonUrl(e);
        });
    });
</script>

</body>

</html>
