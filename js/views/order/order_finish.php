<?php $this->view('header'); ?>

<section class='body-content'>
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#" >Isi Data Pemesanan</a></li>
                <?php if ($orderType != 'private'): ?>
                    <li><a href="#">Rincian & Cara Pembayaran</a></li>
                <?php endif; ?>
                <li><a href="#" class="active">Selesai</a></li>
            </ol>
        </div>
        <div class="row text-center">
            <h3 class='green-text'><b> Terima Kasih</b></h3>
            <p> Data pemesanan anda telah kami terima. Segera lakukan pembayaran untuk menyelesaikan proses pemesanan anda.</p>
            <p> Silahkan cek e-mail anda untuk melihat informasi penting perihal pemesanan.</p>
            <p> Untuk proses selanjutnya, tim <span class='green-text'>Customer Service</span> kami akan segera menghubungi anda dalam waktu 1x24 jam.</p>
        </div>
        <div class="row top-50 text-center">
            <h4 class='green-text'><b>Butuh Bantuan?</b></h4>
            <p>Silahkan hubungi kami:</p>
        </div>
        <div class="row top-20 text-center">
            <p>Line | @peponitravel</p>
            <p>Whatsapp | 0812 - 8931 - 5151</p>
            <p>E-mail | cs@peponitravel.com</p>
        </div>
        <div class="row top-20 text-center">
            <h4 class='green-text'><b>Customer Service Hours</b></h4>
            <p>Senin-Jumat 09.00 - 22.00</p>
            <p>Sabtu 09.00 - 17.00</p>
            <p>Minggu Tutup</p>
        </div>
    </div>
</section>

<?php $this->view('footer'); ?>

</body>

</html>