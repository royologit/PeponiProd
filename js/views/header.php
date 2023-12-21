<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Peponi Travel</title>

    <link rel="icon"
      type="image/png"
      href="<?php echo base_url(); ?>images/peponi_icon.PNG" />

    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap-front.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>css/half-slider-front.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/main-front.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/slick-front.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/slick-theme-front.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome-front.min.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

  <div class="overlay" onclick="closePopup()">

  </div>

  <div class="popup popup-backpacker">
      <a class="peponi-green-text" style="float:right; cursor:pointer;" onclick="closePopup()">
        <i class="fa fa-3x fa-close"></i>
      </a>
      <div class="row" style="padding:20px 50px; font-size:1.2em;">
        <h2 class="peponi-green-text">BACKPACKER</h2>
        <p>
          Nikmati jalan-jalan seru dan
          berpetualang dengan membawa
          barang secukupnya saja dengan
          budget yang gak nyakitin dompet-
          mu. Kamu dapat bertemu dengan
          traveler dari negara lain dan
          saling berbagi cerita menarik
          selama liburan.
        </p>
        <a class="peponi-green" style="color:white; padding:10px; cursor:pointer;" onclick="filterPackages('1')">Filter Backpacker Packages</a>
      </div>
  </div>

  <div class="popup popup-studytour">
      <a class="peponi-green-text" style="float:right; cursor:pointer;" onclick="closePopup()">
        <i class="fa fa-3x fa-close"></i>
      </a>
      <div class="row" style="padding:20px 50px; font-size:1.2em;">
        <h2 class="peponi-green-text">STUDY TOUR</h2>
        <p>
          Jika kamu adalah anggota
          organisasi keren kampus atau
          sekolah yang ingin
          mengadakan study tour,
          maka kami siap membantu
          menekan harga hingga sesuai
          dengan budget anak muda.
        </p>
        <a class="peponi-green" style="color:white; padding:10px; cursor:pointer;" onclick="filterPackages('2')">Filter Study Tour Packages</a>
      </div>
  </div>

  <div class="popup popup-suitcaser">
      <a class="peponi-green-text" style="float:right; cursor:pointer;" onclick="closePopup()">
        <i class="fa fa-3x fa-close"></i>
      </a>
      <div class="row" style="padding:20px 50px; font-size:1.2em;">
        <h2 class="peponi-green-text">SUITCASER</h2>
        <p>
          Jalan-jalan mewah dengan
          membawa koper yang berisi
          barang-barang keperluan
          kamu. Nikmati liburan kamu
          di hotel berbintang 4-5
          dengan fasilitas yang keren.
        </p>
        <a class="peponi-green" style="color:white; padding:10px; cursor:pointer;" onclick="filterPackages('3')">Filter SuitCaser Packages</a>
      </div>
  </div>

  <div class="popup popup-career">
      <a class="peponi-green-text" style="float:right; cursor:pointer;" onclick="closePopup()">
        <i class="fa fa-3x fa-close"></i>
      </a>
      <div class="row" style="padding:20px 50px; font-size:1.2em;">
        <h2 class="peponi-green-text">CAREER</h2>
        <p>
          Kirimkan email dengan
          subjek Career with Peponi
          serta lampirkan CV terbaru
          kamu.
        </p>
      </div>
  </div>

  <div class="popup popup-experience " style="width:80%; left:10%; top:15%;">
      <a class="peponi-green-text" style="float:right; cursor:pointer; position:relative; z-index:999" onclick="closePopup()">
        <i class="fa fa-3x fa-close"></i>
      </a>
      <div class="row" style="font-size:1.2em">
        <div class="col-md-8 col-xs-12">
            <img id="experience-popup-image" src="<?php echo base_url(); ?>" style="width:100%" alt="loading..">
        </div>

        <div class="col-md-4 col-xs-12 hidden-xs" style="padding:0px 40px 20px 20px;">
            <h3 class="peponi-green-text experience-popup-headline"></h3>
            <p style="font-size:0.8em;" class="experience-popup-caption"></p>
        </div>
        <div class="col-md-4 col-xs-12 hidden-lg hidden-md hidden-sm">
            <h4 class="peponi-green-text experience-popup-headline"></h4>
            <p style="font-size:0.4em;" class="experience-popup-caption"></p>
        </div>
      </div>
  </div>

    <header class="navbar navbar-inverse navbar-fixed-top bs-docs-nav" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="<?php echo base_url(); ?>./" class="navbar-brand"><img src="<?php echo base_url(); ?>images/peponi-logo.png" height="100px;" alt="peponi" style="padding:0; margin:-20px; margin-top:-28px;"></a>
    </div>
    <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation" style="border:none;">
      <ul class="nav navbar-nav" style="margin-top:10px;">
        <li>
          <a href="<?php echo base_url(); ?>#" id="s-packages" class="scroll-jump">Destinations</a>
        </li>
        <li>
          <a href="<?php echo base_url(); ?>#" id="s-about" class="scroll-jump">About Us</a>
        </li>
        <li>
          <a href="<?php echo base_url(); ?>#" id="s-experiences" class="scroll-jump">Experiences</a>
        </li>
        <li>
          <a href="<?php echo base_url(); ?>#" id="s-contact" class="scroll-jump">Contact Us</a>
        </li>

      </ul>
    </nav>
  </div>
</header>
