<!DOCTYPE html>
<?php self::inc("head.php");?>
<body>
	<div class="header">
		<div class="header-back"></div>
		<div class="header-title">Swphp</div>
	</div>
	<div class="header-row"></div>
	<div class="main-body bg-white">
		<form method="post" action="/index.php?m=upload" enctype="multipart/form-data">
			<input type="file" name="upimg" />
			<button type="submit">上传</button>
		</form> 
	</div>
<?php self::inc("footer.php");?>
</body>
</html>
