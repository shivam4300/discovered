<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Discovered | Log in</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/plugins/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/css/admin.css">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/plugins/css/blue.css">
  <link rel="stylesheet" href="<?= base_url(); ?>repo_admin/plugins/css/toastr.min.css">
   <link rel="shortcut icon" type="image/ico" href="<?php echo base_url();?>repo/images/favicon.png" /> 
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="<?php echo base_url(); ?>"><b>Discovered</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in</p>

    <form action="<?= base_url('auth');?>" method="post">
      <div class="form-group has-feedback">
        <input type="email" class="form-control require login_form" placeholder="Email" name="email" value="<?php echo isset($_COOKIE['email'])? $_COOKIE['email']:'';?>">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control require login_form" placeholder="Password" name="password" value="<?php echo isset($_COOKIE['pass'])? $_COOKIE['pass']:'';?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input name="remember_me" type="checkbox" <?php echo isset($_COOKIE['email'])? 'checked':'';?>> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="button"  id="loginMe" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?= base_url(); ?>repo_admin/js/jquery.min.js"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?= base_url(); ?>repo_admin/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?= base_url(); ?>repo_admin/plugins/js/icheck.min.js"></script>
<script src="<?= base_url(); ?>repo_admin/plugins/js/toastr.min.js"></script>

<script src="<?= base_url(); ?>repo_admin/js/pages/auth.js"></script>
<script src="<?= base_url(); ?>repo_admin/js/valid.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
