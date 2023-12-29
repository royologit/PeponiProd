<!-- Footer -->

<footer style="background:url('<?php echo base_url().$footer->row()->footer_image; ?>'); background-size:cover; background-position:10% 80%;">
    <div class="row" id="section-contact" style="padding:0; margin:0;">
        <div class="col-lg-12 text-center" style="margin:20px 0;">
            <img src="<?php echo base_url(); ?>images/logo-footer.png" height="150px;" alt="peponi" style="padding:0; margin:-20px; margin-top:-28px;">
        </div>
        <div class="col-lg-12" style="margin-top: 40px">
            <div class="col-lg-offset-3 col-lg-6">
                <div class="text-center">
                    <!-- <h4>Customer Service 09.00 - 22.00</h4> -->
                </div>
            </div>
        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-6 row" style="margin-top:15px;">
            <?php foreach ($contacts->result() as $contact): ?>
                <div class="col-lg-4 text-center" style="font-size:1.2em;">
                    <div style="display:table;height: 40px; width: 100%">
                        <div style="display:table-cell; vertical-align: middle; margin: 0 auto">
                            <img src="<?php echo base_url() . $contact->contact_image; ?>" height="40px" />
                        </div>
                    </div>
                    <p style="margin: 10px 0;"><?php echo $contact->contact_name; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-lg-3"></div>
        <div class="col-lg-12" style="margin-top: 30px">
            <div class="col-lg-offset-3 col-lg-6 text-center">
                <h4>Stay Tuned</h4>
                <?php foreach($socmeds->result() as $socmed):?>
                    <a href="<?php echo $socmed->media_link; ?>" target="_blank" style="color:white; margin:5px;"><i class="fa fa-2x fa-<?php echo $socmed->media_image; ?>"></i></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-lg-12" style="margin-top: 30px">
            <div class="col-lg-offset-3 col-lg-6 text-center footer-link">
                <div class="col-lg-4">
                    <a href="<?php echo base_url(); ?>terms-conditions" style="color:white; cursor:pointer;"><h4>Terms & Conditions</h4></a>
                </div>
                <div class="col-lg-4">
                    <a onclick="openPopup('career')" style="color:white; cursor:pointer;"><h4>Career</h4></a>
                </div>
                <div class="col-lg-4">
                    <h4>PT Aku Bisa Liburan</h4>
                </div>
            </div>
        </div>
    </div>

</footer>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

<!-- Slickr Carousel -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.min.js"></script>

<!-- ScrollTo-->
<script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>

<!-- Script to Activate the Carousel -->
<script>

var base_url = '<?php echo base_url(); ?>';

$.extend($.scrollTo.defaults, {
  axis: 'y',
  over: {top:-2}
});

$('.scroll-jump').click(function(){
  var section = $(this).attr('id');
  section = '#section-' + section.substr(2,100);
  
  $('html, body').animate({
    scrollTop: $(section).offset().top - 100
  }, 500);
  return false;
  //$(document).scrollTo($(section),500);
});


$('.carousel').carousel({
    interval: 5000 //changes the speed
})

$('.package-list').slick({
  infinite: true,
  slidesToShow: 3,
  slidesToScroll: 3,
  prevArrow:'<img class="arrow-prev" src="<?php echo base_url(); ?>images/slick-prev.png" height="70px" style="margin-left:-60px;" />',
  nextArrow:'<img class="arrow-next" src="<?php echo base_url(); ?>images/slick-next.png" height="70px" style="margin-right:-60px;" />',
});

$('.package-list-small').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  prevArrow:'<img class="arrow-prev" src="<?php echo base_url(); ?>images/slick-prev.png" height="50px" style="margin-left:-20px;" />',
  nextArrow:'<img class="arrow-next" src="<?php echo base_url(); ?>images/slick-next.png" height="50px" style="margin-right:-20px;" />',
});

$('.experience-list').slick({
  infinite: true,
  slidesToShow: 4,
  slidesToScroll: 4,
  prevArrow:'<img class="arrow-prev" src="<?php echo base_url(); ?>images/slick-prev.png" height="70px" style="margin-left:-60px;" />',
  nextArrow:'<img class="arrow-next" src="<?php echo base_url(); ?>images/slick-next.png" height="70px" style="margin-right:-60px;" />',
});

$('.experience-list-small').slick({
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 1,
  prevArrow:'<img class="arrow-prev" src="<?php echo base_url(); ?>images/slick-prev.png" height="50px" style="margin-left:-20px;" />',
  nextArrow:'<img class="arrow-next" src="<?php echo base_url(); ?>images/slick-next.png" height="50px" style="margin-right:-20px;" />',
});

$('.thumbs-list').slick({
  infinite: true,
  slidesToShow: 5,
  slidesToScroll: 5,
  prevArrow:'<img class="arrow-prev" src="<?php echo base_url(); ?>images/slick-prev.png" height="70px" style="margin-left:-60px;" />',
  nextArrow:'<img class="arrow-next" src="<?php echo base_url(); ?>images/slick-next.png" height="70px" style="margin-right:-60px;" />',
});

$('.thumb').click(function(){
  var src = $(this).attr('src');
  $('.preview-thumb').attr('src',src);
});

function openPopup(popup){
  $('.popup-' + popup).fadeIn(200);
  $('.overlay').fadeIn(200);
}

$(document).on('click','.experience-popup',function(){
  var curr_image = $(this).attr('src');
  $('#experience-popup-image').attr('src',curr_image);
  $('.experience-popup-headline').html($(this).parent().find('.experience-headline').val());
  $('.experience-popup-caption').html($(this).parent().find('.experience-description').val());
});

function closePopup(){
  $('.popup').fadeOut(200);
  $('.overlay').fadeOut(200);
}

function filterPackages(id){
  location.href= base_url+'filter/'+id;
  //$('.package-item').show();
  //$('.package-item-'+id).hide();
}

//$('.green-bar').hide();
$('#gb-right-1').animate({'width':'0%'});
$('#gb-left-1').animate({'width':'0%'});
$('#gb-right-2').animate({'width':'0%'});
$('#gb-left-2').animate({'width':'0%'});
$('#gb-right-3').animate({'width':'0%'});
$('#gb-left-3').animate({'width':'0%'});
$(window).on('scroll', function() {
    var y_scroll_pos = window.pageYOffset;
    //var scroll_pos_test = 100;             // set to whatever you want it to be

    if(y_scroll_pos > 200) {
      //$('#gb-left-1').fadeIn();
      //$('#gb-right-1').fadeIn();
      $('#gb-right-1').stop().animate({'width':'100%'});
      $('#gb-left-1').stop().animate({'width':'100%'});
    }
    else{
    $('#gb-right-1').stop().animate({'width':'0%'});
    $('#gb-left-1').stop().animate({'width':'0%'});
    }

    if(y_scroll_pos > 850) {
      $('#gb-right-2').stop().animate({'width':'100%'});
      $('#gb-left-2').stop().animate({'width':'100%'});
    }
    else{
    $('#gb-right-2').stop().animate({'width':'0%'});
    $('#gb-left-2').stop().animate({'width':'0%'});
    }

    if(y_scroll_pos > 1500) {
      $('#gb-right-3').stop().animate({'width':'100%'});
      $('#gb-left-3').stop().animate({'width':'100%'});
    }
    else{
    $('#gb-right-3').stop().animate({'width':'0%'});
    $('#gb-left-3').stop().animate({'width':'0%'});
    }
});

</script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-T4DLHGVTHG"></script>
<script>
  let currentUrlforGTAG = new URL(window.location.href);
  let path = currentUrlforGTAG.pathname;
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-T4DLHGVTHG', {
    'page_title' : path
  });
  
</script>

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'X8ruGsUbb7';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
</script>
<!-- {/literal} END JIVOSITE CODE -->