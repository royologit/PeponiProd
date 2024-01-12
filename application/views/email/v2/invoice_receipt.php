<!DOCTYPE html>
<html lang="en">
<?php
    if (isset($orderData)) {
        $productName = ($orderData->product_name == null) ? $orderData->private_name : $orderData->product_name;
        $tripType = ($orderData->product_name == null) ? "Private Trip" : "Open Trip";
        $isPrivate = ($orderData->product_name == null) ? 1 :0;
        $productId = ($orderData->product_id == null) ? $orderData->private_id : $orderData->product_id;

        if ($invoiceData->invoice_type == "down payment") {
            $invoice_type = "Down Payment (DP)";
        } else {
            $invoice_type = $invoiceData->invoice_type;
        }
        $orderData->order_name;
        $orderData->order_phone;
        $orderData->order_line_id;
        $orderData->order_email;
        
        $result = array();
        $CountJP = 0;

		foreach ($orderDetail as $item) {
			$result[] = $item->order_detail_quantity . ' ' . $item->age_group_name;
            $CountJP += $item->order_detail_quantity; 
		}

		$jumlahPeserta =  implode(', ', $result);

        $orderData->order_id;
        $productName;
        $orderData->package_name;
        $detailPeserta = "";
        
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
        /* @font-face {
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
        } */

        @font-face{
            font-family: 'nunito';
            src: url('<?= base_url()?>asset/fonts/Nunito-VariableFont_wght.ttf') format('truetype')
        }
		body{font-family: 'nunito';font-size: 14pt;color: #000;line-height:15pt;letter-spacing:0.2px;margin:35px 50px 0;}
		img{max-width: 100%;height: auto;}
	    header{position: relative;display: block;vertical-align: middle;}
	    header img.logo{width: 250px;height: auto;display: inline-block; position: relative; right: 45px;}
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
	    .green{color: #089b58; font-weight: bold;}
        li a[href]{text-decoration: none; color: #fff !important;}
	    h1{font-size: 15px;position: relative;margin: 25px 0 20px 0;}
	    h1 span{background-color: #089b58;padding:4px 5px;color: #fff;border-right: 5px solid #fff;}
	    h1:after{content:'';display: block;border-top: 3px solid #089b58;margin-top: -11.5px;}
		    b{font-weight: bold;}
		    i{font-style: italic;}
		    hr{border:1.5px solid #d9dddc;}
	    p{margin:10px 0 20px 0;}
        span b{color: #099656}
	    small{font-size: 12pt;line-height: 11pt;}
	    ol{padding-left: 20px;margin: 5px 0 10px 0;}
	    footer{background-color: #089b58;color: #fff;margin:150px -50px 0 -50px;padding:35px 50px;}
        footer{ margin-top: 50px}
	    footer a{color: #fff !important;text-decoration: none;}
	    footer a:hover{opacity: .75;transition: .4s all;-webkit-transition: .4s all;-moz-transition: .4s all;-o-transition: .4s all;}
        .rincian-reservasi{
            background-color: #EAEAEA;
			border-radius: 10px;
			padding: 10px;
        }
        .logo-footer{
			width: 100px;
			float: right;
			padding-top: 10px;
		}
        .tagihan-berikutnya{
            border-style: solid;
            border-width: 5px;
            border-radius: 10px;
            border-color: #EAEAEA;
            padding: 10px;
        }
        .bayar-sekarang{
			background-color: #089b58; 
			color: white !important;
			padding: 10px;
			border-radius: 10px;
			text-decoration: none !important;
		}
        .klik-disini{
			color: #089b58 !important;
			text-decoration: underline !important;
		}
	</style>
</head>
<body>

	<header>
		<img src="<?= base_url() ?>asset/img/logov2.png" alt="" class="logo">
	</header>
    <p>Terima kasih atas pembayaran yang telah Anda lakukan.<br>
        Berikut ini adalah Tanda Terima (Receipt) pembayaran Anda.
    </p>

    <p>
        <b>Rincian Pembayaran</b>
    </p>
    <p>
        Jenis Pembayaran: <?= ucwords($invoiceData->invoice_type) ?><br/>
        Total jumlah yang telah dibayarkan: <span class="green">Rp. <?= CONVERT_TO_CURRENCY($invoiceData->total) ?></span><br/>
    </p>
    <hr>
    <p>
        <b>Rincian Reservasi</b>
    </p>
    <div class="rincian-reservasi">
        <b>Pemesan</b><br/>
        Nama Lengkap: <?= $orderData->order_name ?><br />
		No. Telp / Whatsapp: <?= $orderData->order_phone ?> <br />
		E-Mail: <?= $orderData->order_email ?>
        <br/><br/>
        <b>Pesanan</b><br/>
        No Pesanan: <?= $orderData->order_id ?><br />
		Nama Trip: <?= $productName ?><br />
		Destinasi: <?= $orderData->package_name ?><br />
		Biaya Trip: Rp. <?= CONVERT_TO_CURRENCY($orderData->order_price) ?><br />
		Jumlah Peserta: <?= $jumlahPeserta ?>
    </div>
    <br/>
    <?php if($next_invoice != null) {  ?>
    <div class="tagihan-berikutnya">
        <b>Tagihan Anda Berikutnya</b><br/><br/>
        No Tagihan: <?= $next_invoice->id ?><br/>
        Jenis Pembayaran: <?= ucwords($next_invoice->invoice_type) ?><br/>
        Tagihan (per orang): Rp. <?= CONVERT_TO_CURRENCY(intval($next_invoice->total)/$CountJP) ?><br/>
        <?php if ($next_invoice->discount > 0) { ?>
            Diskon: Rp. <?= CONVERT_TO_CURRENCY($next_invoice->discount) ?><br />
        <?php } ?>
        Total Tagihan: Rp. <?= CONVERT_TO_CURRENCY($next_invoice->total-$next_invoice->discount) ?><br/>
        Jatuh Tempo pada <b><?= date("d M Y \J\a\m 13:00 T", strtotime($invoiceData->due_date)) ?></b><br/>
        <br/>
        <p>
            <a href="<?=$next_invoice->url_invoice?>" class="bayar-sekarang">Bayar Sekarang</a>
        </p>
        <p>
            Apabila link diatas tidak berfungsi, mohon klik <a href="<?=$next_invoice->url_invoice?>" class="klik-disini">di sini</a> untuk melanjutkan pembayaran
        </p>
    </div>
    <?php } ?>
	<footer>
    <img src="<?= base_url() ?>images/logo_footer.png" class="logo-footer">
	    <p><b>Butuh Bantuan ?</b></p>
		<p>
			Hubungi Customer Service kami melalui Whatsapp <a href="tel:081289315151">0812-8931-5151</a> 
		</p>
		<p>Pelajari terms & condition di sini: <a href="https://peponitravel.com/terms-conditions#TC" target="_blank">www.peponitravel.com/terms-conditions</a></p>
	</footer>



</body>
</html>
