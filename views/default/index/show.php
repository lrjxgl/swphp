<!DOCTYPE html>
<?php self::inc("head.php");?>
<body>
	<div class="header">
		<div class="header-back"></div>
		<div class="header-title">Swphp</div>
	</div>
	<div class="header-row"></div>
	<div class="main-body bg-white">
		<div class="pd-10">
			<div class="d-title ">
				<?=$data["title"];?>
			</div>

			<div class="d-content">
				<?=$data["content"]?>
			</div>
		</div>
</div>
<?php self::inc("footer.php");?>
</body>
</html>
