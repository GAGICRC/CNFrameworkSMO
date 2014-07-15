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
	<?php  // Carregar iefix.css
		if (preg_match('/MSIE ([0-9]{1,}[\.0-9]{0,})/', $_SERVER['HTTP_USER_AGENT'])) {
		    echo '<link rel="stylesheet" href="/Views/Globals/iefix.css" />';
		}   
	?>
	<script src="/Views/js/jquery.js"></script>
	<script>
		$(document).ready(function(){ 
			// Clique em todos os botões com classe actionBtn...
			$("button.actionBtn, img.actionBtn").click(function(){
				window.location = "/CMS/CMSBackEnd/" + $(this).attr("id");
			});
		});
		
	</script>
</head>

<div class="content master">

<div class="stdtop onbg">
</div>

<div class="clear"></div>


