<!DOCTYPE html>
<?php self::inc("head.php");?>
<body>
	<div class="header">
		<div class="header-title">Swphp</div>
	</div>
	<div class="header-row"></div>
	<div class="main-body bg-white">
 
<div>
	<?php if(!empty($ssuser)): ?>
	<div class="title">欢迎您，<?=$ssuser["nickname"]?></div>
	<?php endif;?>
	<?php 
		if($list):
		foreach($list as $item):
	?>
		<a class="row-item" href="index.php?a=show&id=<?=$item["id"]?>">
			<div class="row-item-title"><?=$item["title"]?></div>
		</a>
	<?php 
		endforeach;
		endif;
	?>
</div>
 
</div>
<?php self::inc("ftnav.php");?>
<?php self::inc("footer.php");?>
</body>
</html>
