<?php
/*
	@FileName: header.php
	@Description: WebFeels CMS Global Head View
*/

?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>WebFeels</title>
	<link rel="stylesheet" href="/Views/Globals/style.css" />
	<link rel="stylesheet" href="/Views/Modules/CMS/CMSFrontEnd/CMSFrontEndStyle.css" />
	<?php  // Carregar iefix.css
		if (preg_match('/MSIE ([0-9]{1,}[\.0-9]{0,})/', $_SERVER['HTTP_USER_AGENT'])) {
		    echo '<link rel="stylesheet" href="/Views/Globals/iefix.css" />';
		}   
	?>
	<script src="/Views/js/jquery.js"></script>
	<script>
		$(document).ready(function(){ 
			$("button.actionBtn, img.actionBtn").click(function(){
				window.location = "/" + $(this).attr("id");
			});
		});
	</script>
</head>

<div class="content master" id="page">

<div class="stdtop onbg">
	<a href="/"><div class="logo"></div></a>
	<p id="topmsg"><?php print $wfname; ?></p>
	
	<nav class="topNav">
		<ul>
			<li><a href="/blog/">Blog</a></li>
			<?php 
				foreach ($navMenu as $key => $item) {
					print "<li><a href='/page/{$item->id}'>{$item->title}</a></li>";
				}
			?>
		</ul>
	</nav>
</div>

<div class="clear"></div>


