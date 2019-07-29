<!DOCTYPE html>
<head>
	<title>Hi View</title>
	<meta charset="utf-8" />
	<link href="/static/index/css/app.css" rel="stylesheet" />
</head>
<div class="title">
	<?=$message;?>
</div>
<div>
	<?php 
		if($list):
		foreach($list as $item):
	?>
		<a href="index.php?a=show&id=<?=$item["id"]?>"><?=$item["title"]?></a>
	<?php 
		endforeach;
		endif;
	?>
</div>
<div class="content">
	View is simple Dom Xml;
</div>
