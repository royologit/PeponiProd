<!DOCTYPE html>
<html lang="en">
<?php
    if (isset($orderData)) {
        $productName = ($orderData->product_name == null) ? $orderData->private_name : $orderData->product_name;
        $tripType = ($orderData->product_name == null) ? "Private Trip" : "Open Trip";
        $isPrivate = ($orderData->product_name == null) ? 1 :0;
        $productId = ($orderData->product_id == null) ? $orderData->private_id : $orderData->product_id;

        $orderData->order_name;
        $orderData->order_phone;
        $orderData->order_line_id;
        $orderData->order_email;

        $orderData->order_id;
        $productName;
        $orderData->package_name;
        $detailPeserta = "";
        $jumlahPeserta = 0;
        foreach ($orderDetail as $index => $eachDetail) {
            if ((count($orderDetail)-1 == $index)) {
                $detailPeserta .= $eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name;
            } else {
                $detailPeserta .= $eachDetail->order_detail_quantity . " " . $eachDetail->age_group_name . ", ";
            }
            $jumlahPeserta += $eachDetail->order_detail_quantity;
        }
        date("d M Y", strtotime($invoiceData->due_date)) . ", 13.00";
        $downPayment = "Rp. " .CONVERT_TO_CURRENCY($invoiceData->price) . " x ".$invoiceData->quantity." orang";
        $totalInvoice = $invoiceData->total;
    }
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $tripType ?></title>
	<style>
        @font-face {
            font-family: 'Tw Cen MT';
            src: url('<?= base_url()?>asset/fonts/TwCenMT-Regular.woff2') format('woff2'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Regular.woff') format('woff'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Regular.ttf') format('truetype'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Regular.svg#TwCenMT-Regular') format('svg');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Tw Cen MT';
            src: url('<?= base_url()?>asset/fonts/TwCenMT-Italic.woff2') format('woff2'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Italic.woff') format('woff'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Italic.ttf') format('truetype'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Italic.svg#TwCenMT-Italic') format('svg');
            font-weight: normal;
            font-style: italic;
        }

        @font-face {
            font-family: 'Tw Cen MT';
            src: url('<?= base_url()?>asset/fonts/TwCenMT-Bold.woff2') format('woff2'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Bold.woff') format('woff'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Bold.ttf') format('truetype'),
                url('<?= base_url()?>asset/fonts/TwCenMT-Bold.svg#TwCenMT-Bold') format('svg');
            font-weight: bold;
            font-style: normal;
        }

        @font-face {
            font-family: 'Tw Cen MT';
            src: url('<?= base_url()?>asset/fonts/TwCenMT-BoldItalic.woff2') format('woff2'),
                url('<?= base_url()?>asset/fonts/TwCenMT-BoldItalic.woff') format('woff'),
                url('<?= base_url()?>asset/fonts/TwCenMT-BoldItalic.ttf') format('truetype'),
                url('<?= base_url()?>asset/fonts/TwCenMT-BoldItalic.svg#TwCenMT-BoldItalic') format('svg');
            font-weight: bold;
            font-style: italic;
        }

		body{font-family: 'Tw Cen MT';font-size: 14pt;color: #000;line-height:15pt;letter-spacing:0.2px;margin:35px 50px 0;}
		img{max-width: 100%;height: auto;}
	    header{position: relative;display: block;margin-bottom: 50px;vertical-align: middle;}
	    header img.logo{width: 200px;height: auto;display: inline-block;}
	    header ul{display: inline-block;float: right;margin-right: 15px;padding:0;}
	    header ul li{display: inline-block;margin:0 5px;}
	    header ul li img{width:30px;}
	    header ul li a:hover{opacity: .75;transition: .4s all;-webkit-transition: .4s all;-moz-transition: .4s all;-o-transition: .4s all;}
		@media screen and (max-width: 480px){
		    header img.logo{width: 200px;height: auto;display: block;margin: 15px auto;text-align: center;}
		    header ul{display: block;float: unset;margin: 0 auto;text-align: center;}
		    header ul li{display: inline-block;margin:0 5px;}
		    header ul li img{width:30px;}
		}
	    .green{color: #089b58}
        li a[href]{text-decoration: none; color: #fff !important;}
	    h1{font-size: 15px;position: relative;margin: 25px 0 20px 0;}
	    h1 span{background-color: #089b58;padding:4px 5px;color: #fff;border-right: 5px solid #fff;}
	    h1:after{content:'';display: block;border-top: 3px solid #089b58;margin-top: -11.5px;}
		    b{font-weight: bold;}
		    i{font-style: italic;}
		    hr{border:1.5px solid #d9dddc;}
	    p{margin:10px 0 20px 0;}
	    small{font-size: 12pt;line-height: 11pt;}
	    ol{padding-left: 20px;margin: 5px 0 10px 0;}
	    footer{background-color: #089b58;color: #fff;margin:150px -50px 0 -50px;padding:35px 50px;}
        footer{ margin-top: 50px}
	    footer a{color: #fff !important;text-decoration: none;}
	    footer a:hover{opacity: .75;transition: .4s all;-webkit-transition: .4s all;-moz-transition: .4s all;-o-transition: .4s all;}
        .btn-bayar{
            background-color: rgba(255,199,15,255);
            padding: 2vh;
            margin-top: 2vh;
            font-weight: 700;
            border: none;
            border-radius: 100px;
            color: black;
            padding-left: 5vh;
            padding-right: 5vh;
            font-size: 2vh;
            cursor: pointer;
        }
    </style>
</head>
<body>

	<header>
		<img src="<?= base_url() ?>asset/img/logo.png" alt="" class="logo">
		<ul>
			<li><a href="https://www.instagram.com/peponitravel/"><img src="<?= base_url() ?>asset/img/icon-instagram.png" alt=""></a></li>
			<li><a href="https://www.facebook.com/peponitravel/"><img src="<?= base_url() ?>asset/img/icon-facebook.png" alt=""></a></li>
		</ul>
	</header>
    <p>Halo, salam dari Peponi Travel.<br>
        Berikut adalah rincian pembayaran Anda yang akan jatuh tempo.
	<h1><span>Rincian Tagihan</span></h1>
    <p>
		No Tagihan        : <?= $invoiceData->id ?><br />
		Informasi Tagihan : <?= $invoiceData->description ?><br />
        Nama Pemesan      : <?= $orderData->order_name ?> <br />
		Jumlah Peserta    : <?= $jumlahPeserta ?><br />
		Tagihan (per orang): Rp. <?= CONVERT_TO_CURRENCY(intval($invoiceData->total)/$jumlahPeserta) ?><br />
<?php if ($invoiceData->discount > 0) {
    ?>
        Diskon            : Rp. <?= CONVERT_TO_CURRENCY($invoiceData->discount) ?><br />
<?php
} ?>
        Total Tagihan     : Rp. <?= CONVERT_TO_CURRENCY($invoiceData->total-$invoiceData->discount) ?><br />
	</p>
	<p>
		Mohon segera melakukan pembayaran tagihan sebelum <i class="green"><b><?= date("d M Y \J\a\m 13:00 T", strtotime($invoiceData->due_date)) ?></b></i><br />
		*Anda mungkin harus melakukan reservasi ulang apabila gagal melakukan pembayaran pada batas waktu ini
	</p>
	<hr>

	<p>
        Pembayaran melalui Halaman Pembayaran Peponi Travel dengan menekan tombol dibawah ini<br />
		<a href="<?=$url_invoice?>" style="cursor:pointer!important"><button class="btn-bayar" style="cursor:pointer!important">Bayar Sekarang</button></a>
	</p>
<!--
	Penting:
	<ol>
		<li>Mohon isi berita acara pembayaran transfer dengan "Nama Lengkap" sesuai yang Anda daftarkan</li>
		<li>Mohon kirimkan bukti transfer ke Customer Service Peponi Travel untuk ditukarkan dengan tanda terima</li>
        <li>Mohon abaikan pesan ini apabila Anda telah melakukan pembayaran</li>
	</ol>-->

	<footer>
		<p><b>Butuh Bantuan ?</b></p>
		<p>
			Hubungi Customer Service kami melalui:<br />
			Whatsapp <a href="tel:081289315151">0812-8931-5151</a> | E-mail <a href="mailto:cs@peponitravel.com">cs@peponitravel.com</a>
		</p>
		<p><a href="http://www.peponitravel.com">www.peponitravel.com</a></p>
	</footer>



</body>
</html>
