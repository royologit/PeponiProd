<?php include('layouts/header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height:800px;">
<?php

if(!@include($page.'/'.$function.'.php'))
{
?>
  <div style="text-align:center; width:100%; font-size:1.5em; padding:40px;">
      <i class="fa fa-times-circle-o fa-5x"></i>
      <br/>
      <br/>
      <?php echo $page; ?>
      Not Found
  </div>
<?php
}
?>

</div>
<!-- /.content-wrapper -->
<?php include('layouts/footer.php'); ?>
