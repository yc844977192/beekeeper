<!DOCTYPE html>
<!-- saved from url=(0040)http://demo.laravel.com/admin/auth/login -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>首次登陆_修改密码</title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/vendor/laravel-admin/font-awesome/css/font-awesome.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/vendor/laravel-admin/AdminLTE/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="http://demo.laravel.com/admin"><b>Beekeeper</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">首次登陆_修改密码</p>
    <form action="http://demo.laravel.com/admin/auth/login" method="post">
      <div class="form-group has-feedback 1">
        <input type="password" class="form-control" placeholder="新密码" name="new_password" id="new_password">
      </div>
      <div class="form-group has-feedback 1">
        <input type="password" class="form-control" placeholder="确认密码" name="confirm_password" id="confirm_password">
      </div>
      <div class="row">
        <div class="col-xs-8">
                    <div class="checkbox icheck">
            <label>
              <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false" style="position: relative;">
              <input type="checkbox" name="remember" value="1" checked="" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;">
              <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
              记住我
            </label>
          </div></div>
        <div class="col-xs-4">
          <button type="button" id="button" class="btn btn-primary btn-block btn-flat">确认</button>
        </div>
      </div>
    </form>

  </div>

</div>

<script src="/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js "></script>

<script src="/vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js"></script>

<script src="/vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js"></script>
<script>
    $("#button").on("click",function () {
        if($("#older_password").val()==''||$("#new_password").val()==''||$("#confirm_password").val()==''){
            alert("不允许为空！");
            return;
        }
        if($("#new_password").val()!=$("#confirm_password").val()){
            alert("新密码和确认密码不相同！");
            return;
        }
        if($("#new_password").val().length<6){
            alert("新密码长度不少于6个字符！");
            return;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'/admin/Dashboard/changePassword',
            type:'post',
            async:false,
            dataType:'json',
            data:{'new_password':$("#new_password").val(),'confirm_password':$("#confirm_password").val()},
            success:function(res){
                console.log(res);
                if(res.status==1){
                    alert("修改成功");
                    window.location.href="/admin";
                }
            }
        })
    })

</script>


</body></html>