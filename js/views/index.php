<?php include('header.php'); ?>

    <!-- Half Page Image Background Carousel Header -->
    <div class="section-jump" id="section-home"></div>
    <header id="myCarousel" class="section-jump carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php foreach($carousels->result() as $index => $carousel): ?>
                <li data-target="#myCarousel" data-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>"></li>
            <?php endforeach; ?>
        </ol>

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <?php $active = 'active'; foreach($carousels->result() as $carousel):  ?>
            <div class="item <?php echo $active; $active = ''; ?>">
                <a href="<?php echo $carousel->carousel_layout ?>">
                	<div class="fill" style="background-image:url('<?php echo $carousel->carousel_image; ?>')"></div>
                </a>
                <div class="carousel-caption">
                </div>
            </div>
            <?php $active = 0; endforeach; ?>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>

    </header>

    <!-- Page Content -->

    <div id="section-packages" class="section-jump row" style="margin:20px 0px; width:100%">
        <div class="col-md-4 col-xs-3" style="padding-left:0;">
          <div id="gb-left-1" class="green-bar" style="margin-right:-300px;"></div>
        </div>
        <div class="col-md-4 col-xs-6 text-center"><h2>Destinasi Favorit</h2></div>
        <div class="col-md-4 col-xs-3" style="padding-right:0;">
          <div id="gb-right-1" class="green-bar pull-right"></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <?php $no=1; foreach($packages->result() as $package): ?>
                <div class="col-md-4 text-center">
                    <a href="<?php echo base_url(); ?>filter/<?php echo $package->package_id; ?>" style="display:block; position:relative; overflow: hidden"><img src="<?php echo base_url().$package->package_image; ?>" style="width:100%;">
                    <div class="fav-destination-text">
                        <div class="wrapper-dest-text">
                        <h1 style="font-size: 30px; color: white"><?php echo $package->package_name; ?></h1>
                        <p style="margin-top: 40px; color: #fff; font-size: 16px;font-style: italic"> See More</p>
                    </div>
                    </div>
                    </a>
                </div>
                <?php if($no%3==0) {?><div style="clear:both"></div><?php } ?>
            <?php $no++; endforeach; ?>
        </div>
    </div>
    <div id="section-about" class="section-jump row" style="margin:20px 0px; width:100%">
        <div class="col-md-5 col-xs-3" style="padding-left:0;">
          <div id="gb-left-2" class="orange-bar"></div>
        </div>
        <div class="col-md-2 col-xs-6 text-center"><h2>About Us</h2></div>
        <div class="col-md-5 col-xs-3" style="padding-right:0;">
          <div id="gb-right-2" class="orange-bar pull-right"></div>
        </div>
    </div>

    <div class="container container-about text-center">
        <?php foreach($abouts->result() as $about): ?>
        <div class="row">

            <div class="col-md-6" style="padding:40px;">
                <p style="font-size:1.8em;">
                  <?php echo $about->about_description; ?>
                </p>
            </div>

            <div class="col-md-6">
                <img src="<?php echo base_url().$about->about_image?>" style="width:80%; margin:0 auto" />
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <div id="section-experiences" class="section-jump row" style="margin:20px 0px; width:100%">
        <div class="col-md-4 col-xs-3" style="padding-left:0;">
          <div id="gb-left-3" class="green-bar"></div>
        </div>
        <div class="col-md-4 col-xs-6 text-center"><h2>Our Experiences</h2></div>
        <div class="col-md-4 col-xs-3" style="padding-right:0;">
          <div id="gb-right-3" class="green-bar pull-right"></div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="experience-list hidden-xs">
                    <?php foreach($experiences->result() as $experience): ?>
                    <div>
                      <input type="hidden" class="experience-headline" value="<?php echo $experience->experience_name; ?>" />
                      <input type="hidden" class="experience-description" value="<?php echo $experience->experience_description;?>" />
                      <img src="<?php echo base_url().$experience->experience_image ?>" class="experience-popup" onclick="openPopup('experience')" style="width:100%; padding:15px; cursor:pointer;" /></div>
                  <?php endforeach; ?>
                </div>
                <div class="experience-list-small hidden-lg hidden-md hidden-sm">
                    <?php foreach($experiences->result() as $experience): ?>
                      <div>
                      <input type="hidden" class="experience-headline" value="<?php echo $experience->experience_name; ?>" />
                      <input type="hidden" class="experience-description" value="<?php echo $experience->experience_description;?>" />
                    <img src="<?php echo base_url().$experience->experience_image ?>" class="experience-popup" onclick="openPopup('experience')" style="width:100%; padding:15px; cursor:pointer;" /></div>
                  <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>

</body>

</html>
