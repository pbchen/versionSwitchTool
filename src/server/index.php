<?php
include_once("config/config.php");
session_start();
if(isset($_GET['action']) && $_GET['action']=='logout'){
	session_destroy();
	header("location: index.php");
}
else {
	$_SESSION['username']=''; 
	$msg='';
	if(isset($_SESSION['username'])&&$_SESSION['username']==$config['username']
	 || isset($_POST['username'])&&$_POST['username']==$config['username']
	 	&&isset($_POST['password'])&&$_POST["password"]==$config['password']){		
		$_SESSION['username']=$_POST['username'];
		//setcookie("username", $_POST['username'], time()+24*60*60); 
		//echo $_POST['username'].' '.$_POST['password'];
		header("location:dashboard.php");
	} else {
		if(isset($_POST['username'])){
			$msg='用户名或密码错误!';
		}
	}
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Web项目版本切换工具</title>
    
        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
            <link rel="stylesheet" href="bootstrap/css/bootstrap-responsive.min.css" />
        <!-- theme color-->
            <link rel="stylesheet" href="css/blue.css" />
        <!-- tooltip -->    
			<link rel="stylesheet" href="lib/qtip2/jquery.qtip.min.css" />
        <!-- main styles -->
            <link rel="stylesheet" href="css/style.css" />
    
        <!-- Favicons and the like (avoid using transparent .png) -->
            <link rel="shortcut icon" href="favicon.ico" />
            <link rel="apple-touch-icon-precomposed" href="icon.png" />
    
        
        <!--[if lte IE 8]>
            <script src="js/ie/html5.js"></script>
			<script src="js/ie/respond.min.js"></script>
        <![endif]-->
		
    </head>
    <body class="login_page">
		
		<div class="login_box">
			
			<form action="index.php" method="post" id="login_form">
				<div class="top_b">登录到Web项目版本切换工具</div>
				<?php if($msg==''){?>    
				<div class="alert alert-info alert-login">
					请输入用户名和密码.
				</div>
				<?php } else { ?>
				<div class="alert alert-error alert-login">
					<?php echo $msg;?>
				</div>
				<?php }?>
				<div class="cnt_b">
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="username" placeholder="Username" value="" />
						</div>
					</div>
					<div class="formRow">
						<div class="input-prepend">
							<span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="Password" value="" />
						</div>
					</div>
					<!--div class="formRow clearfix">
						<label class="checkbox"><input type="checkbox" /> Remember me</label>
					</div-->
				</div>
				<div class="btm_b clearfix">
					<button class="btn btn-inverse pull-right" type="submit">登录</button>					
				</div>  
			</form>
			
		</div>
		
 
        
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.actual.min.js"></script>
        <script src="lib/validation/jquery.validate.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                
				//* boxes animation
				form_wrapper = $('.login_box');
                $('.linkform a,.link_reg a').on('click',function(e){
					var target	= $(this).attr('href'),
						target_height = $(target).actual('height');
					$(form_wrapper).css({
						'height'		: form_wrapper.height()
					});	
					$(form_wrapper.find('form:visible')).fadeOut(400,function(){
						form_wrapper.stop().animate({
                            height	: target_height
                        },500,function(){
                            $(target).fadeIn(400);
                            $('.links_btm .linkform').toggle();
							$(form_wrapper).css({
								'height'		: ''
							});	
                        });
					});
					e.preventDefault();
				});
				
				//* validation
				$('#login_form').validate({
					onkeyup: false,
					errorClass: 'error',
					validClass: 'valid',
					rules: {
						username: { required: true, minlength: 3 },
						password: { required: true, minlength: 3 }
					},
					highlight: function(element) {
						$(element).closest('div').addClass("f_error");
					},
					unhighlight: function(element) {
						$(element).closest('div').removeClass("f_error");
					},
					errorPlacement: function(error, element) {
						$(element).closest('div').append(error);
					}
				});
            });
        </script>
    </body>
</html>
