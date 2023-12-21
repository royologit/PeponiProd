<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo bower_url(); ?>/AdminLTE/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo bower_url(); ?>/AdminLTE/dist/css/AdminLTE.min.css">
    <script src="<?php echo bower_url(); ?>/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
  <style>
    body{background:#fff; font-family: 'Arial'}
    table{background:white;padding:20px;  }
    label{ font-size: 16px; color: #fff}
    button{ font-size: 17px; margin-top: 15px; padding: 5px; color: rgb(7,144,84)}
    h1{ margin-bottom: 45px}
    td{ padding: 5px;} 
    .logo {
        width: 300px;
        height: auto;
        margin: 0 auto;
    }
    .green {
        background-color: rgb(7,144,84);
    }
    .mytable{ width: 450px; height: 300px; }
    .table-border {margin:0 auto; width: 500px; height: 350px; border: 1px solid #ccc; padding: 10px; border-radius:5px; margin-top:10%;}
    table input{
        /* width: 240px; */
    }
  </style>
</head>
<body>
  <form action="<?php echo base_url().$this->config->item('admin_dir_controller').'AuthController/login' ?>" method="post">
  <div class="table-border green">
      <table class="mytable green">
        <tr><td colspan="2"><center><img class="logo" src="<?= base_url() ?>images/peponi-logo.png"></img></center></td></tr>
        <tr>
          <td><label for="username" class="col-sm-3 control-label">Username:</label></td>
          <td><div class="col-sm-11"><input type="text" name="username" class="form-control" placeholder="" value=""></div></td>
        </tr>
        <tr>
          <td><label for="password" class="col-sm-3 control-label">Password:</label></td>
          <td><div class="col-sm-11"><input type="password" name="password" class="form-control" placeholder="" value=""></div></td>
        </tr>
        <tr>
          <td colspan="2"><div class="col-sm-3 pull-right"><button type="submit" value="Login" class="btn btn-light">Login</button></div></td>
        </tr>
        <tr>
          <td colspan="2" style="color:red"><?php echo form_error('username'); ?></td>
        </tr>
        <tr>
          <td colspan="2" style="color:red"><?php echo form_error('password'); ?></td>
        </tr>
      </table>
  </div>
  </form>
</body>
</html>
