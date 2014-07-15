<?php
/*
	@FileName: header.php
	@Description: WebFeels CMS BackEnd Head View
*/

?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>WebFeels > Área de Gestão</title>
	<link rel="stylesheet" href="/Views/Globals/style.css" />
	<link rel="stylesheet" href="/Views/Modules/CMS/CMSBackEnd/CMSBackEndStyle.css" />
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

<div class="backendtop onbg">
	<div class="logo"></div>
	<p id="topmsg">| Área de Gestão</p>
	
	<?php if (isset($_headerUsername)) { ?>
	
	<div class="userInfo"><p id="currentUser">Sessão iniciada como <strong><?php print $_headerUsername; ?></strong></p>
	<div class="clear"></div>
		<button class="actionBtn" id="logout" title="Terminar sessão do utilizador atual">Sair</button>
		<button class="actionBtn" id="articlesNew" title="Criar um novo artigo">Novo Artigo</button>
		<button class="actionBtn" id="pagesNew" title="Criar uma nova página">Nova Página</button>
	</div>
	
	<?php } ?>
	
</div>

<div class="clear"></div>


