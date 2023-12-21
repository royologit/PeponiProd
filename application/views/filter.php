<?php include('header.php'); ?>

    <!-- Half Page Image Background Carousel Header -->
    <div class="section-jump" id="section-home"></div>
    <header id="myCarousel" class="section-jump carousel slide" style="height:180px;">
    </header>
    <!-- Page Content -->

    <div id="section-packages" class="section-jump row" style="margin: -60px 0px 20px 0; width:100%">
        <div class="col-md-4 col-xs-3" style="padding-left:0;">
          <div id="gb-left-1" class="green-bar" style="margin-right:-300px;"></div>
        </div>
        <div class="col-md-4 col-xs-6 text-center">
          <h2>
            <?php echo $filter_title->row()->package_name ?>
          </h2>
        </div>
        <div class="col-md-4 col-xs-3" style="padding-right:0;">
          <div id="gb-right-1" class="green-bar pull-right"></div>
        </div>
    </div>

    <div class="container">
        <div class="row" style="margin-top:20px;">
          <div class="col-md-12">
            <div class="package-list hidden-xs text-center">
              <?php foreach($products->result() as $product): ?>
              <div class="fav-destination-slide-item package-item package-item-<?php echo $product->package_id; ?>" >
                <a href="<?php echo base_url(); ?>package/<?php echo $product->product_id; ?>"><img src="<?php echo base_url().$product->product_cover_image; ?>" style="width:100%;"></a>
                <h3><a href="<?php echo base_url(); ?>package/<?php echo $product->product_id; ?>"><?php echo $product->product_name; ?></a></h3>
                <h3><?php echo currency_format($product->product_price); ?>/<?php echo substr($product->product_duration,26,2); ?> Days</h3>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="package-list-small hidden-lg hidden-md hidden-sm text-center">
              <?php foreach($products->result() as $product): ?>
              <div class="fav-destination-slide-item package-item package-item-<?php echo $product->package_id; ?>" >
                <a href="<?php echo base_url(); ?>package/<?php echo $product->product_id; ?>"><img src="<?php echo base_url().$product->product_cover_image; ?>" style="width:100%;"></a>
                <h3><a href="<?php echo base_url(); ?>package/<?php echo $product->product_id; ?>"><?php echo $product->product_name; ?></a></h3>
                <h3><?php echo currency_format($product->product_price); ?>/<?php echo substr($product->product_duration,26,2); ?> Days</h3>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
    </div>


<?php include('footer.php');?>

</body>

</html>
