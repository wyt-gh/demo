<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:85:"D:\phpstudy\PHPTutorial\WWW\tp5exam\public/../application/admin\view\login\login.html";i:1602598831;}*/ ?>
<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>后台登录-X-admin2.2</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/static/admin/css/font.css">
    <link rel="stylesheet" href="/static/admin/css/login.css">
	  <link rel="stylesheet" href="/static/admin/css/xadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/static/admin/lib/layui/layui.js" charset="utf-8"></script>
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="login-bg">
    
    <div class="login layui-anim layui-anim-up">
        <div class="message">考试系统</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post"  class="layui-form" >
            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="captcha" lay-verify="required" placeholder="验证码"  type="text" class="layui-input">
            <hr class="hr15">
            <img class="captcha_img" src="<?php echo url('Login/verify'); ?>" alt="captcha" />
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              $('.captcha_img').on('click',function(){
                $('.captcha_img').attr("src","<?php echo url('Login/verify'); ?>")
              })
              // 监听提交
              form.on('submit(login)', function(data){
                // alert(888)
                // layer.msg(JSON.stringify(data.field),function(){
                //     location.href='index.html'
                // });
                
                $.post('<?php echo url("Login/login"); ?>',{data:data.field},function(data){
                  $('.captcha_img').attr("src","<?php echo url('Login/verify'); ?>")
                  if (data.status == 1) {
                    layer.msg(data.msg)
                    location.href='<?php echo url("Index/index"); ?>'

                  } 
                  if (data.code == 0) {
                    layer.msg(data.msg)
                  }
                  
                })
                return false;
              });
            });
        })
    </script>
    <!-- 底部结束 -->
    <script>
    //百度统计可去掉
    // var _hmt = _hmt || [];
    // (function() {
    //   var hm = document.createElement("script");
    //   hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
    //   var s = document.getElementsByTagName("script")[0]; 
    //   s.parentNode.insertBefore(hm, s);
    // })();
    </script>
</body>
</html>