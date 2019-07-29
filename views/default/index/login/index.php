<!DOCTYPE html>
<?php self::inc("head.php");?>
<body>
	<div class="header">
		<div class="header-back"></div>
		<div class="header-title">Swphp</div>
	</div>
	<div class="header-row"></div>
	<div class="main-body bg-white">
		<div>
			<div class="input-flex">
				<div class="input-flex-label">手机</div>
				<input type="text" class="input-flex-text" id="telephone" name="telephone" placeholder="请输入手机号码" />
			</div>
			<div class="input-flex">
				<div class="input-flex-label">密码</div>
				<input type="password" class="input-flex-text" id="password" type="text" placeholder="请输入密码" passowrd />
			</div>
			<div class="row-box">
				<div class="btn-row-submit" id="login-submit">登录</div>
				 
			</div>
		</div> 
	</div>
<?php self::inc("footer.php");?>
<script>
		$(function(){
			$(document).on("click","#login-submit",function(){		
				$.post("/index.php?m=login&a=loginSave&ajax=1",{
					telephone:$("#telephone").val(),
					password:$("#password").val()
					 
				},function(data){
					if(data.error==1){
						skyToast(data.message);
					}else{
						skyToast("登陆成功");
						setTimeout(function(){
							goBack();
							
						},700);
					}
				},"json");
			});
		});
		</script>
</body>
</html>
