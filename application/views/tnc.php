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
           Terms & Conditions
        </h2>
    </div>
    <div class="col-md-4 col-xs-3" style="padding-right:0;">
        <div id="gb-right-1" class="green-bar pull-right"></div>
    </div>
</div>

<div class="container">
    <div style="margin-top:20px;">
        <?php echo $tnc->tnc_content; ?>
    </div>
</div>


<?php include('footer.php');?>

</body>

</html>
