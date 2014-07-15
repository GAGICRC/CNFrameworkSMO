<?php
/*
	@FileName: loginMe.php
	@Description: WebFeels CMS BackEnd Login Page
*/

?>

<script>
$(document).ready(function(){ 
	
	function resizeMenu() {
		$('.content .page').height($(window).height()-$('.backendtop.onbg').height()-$('footer.master').height() );
	}
	
	resizeMenu(); // Redimensionamento incial.
	$(window).resize(resizeMenu); // Chama a função resizeMenu() cada vez que a janela é redimensionada.
	
});
</script>

<div class="content page">

<h1>Área Restrita</h1>




<div class="loginCenter">
	<?php 
if (isset($error))
	print "<p class='statusMsg'>{$error}</p>";
?>
<form method="post" action="">

	<fieldset>
		<legend>Autentique-se para continuar...</legend>

		<div>
			<label for="username">Utilizador</label>
			<input type="text" name="username" value="" />
		</div>
		
		<div>
			<label for="passwd">Palavra-Chave</label>
			<input type="password" name="passwd" value="" />
		</div>
				
	</fieldset>

	<button>Entrar ></button>
	
</form>
<br /><br/>
<p>Novo ao WebFeels?</p>
<button class="actionBtn" id="usersRegister">Registe-se Agora!</button>
</div>

<div>
</div>

</div>