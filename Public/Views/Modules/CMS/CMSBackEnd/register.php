<?php
/*
	@FileName: register.php
	@Description: WebFeels CMS BackEnd Register Page
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

<h1>Registe-se no Webfeels!</h1>
<div class="loginCenter">
<?php 
if (isset($error))
	print "<p>{$error}</p>";
?>

<form method="post" action="">

	<fieldset>
		<legend>Preencha os seus dados</legend>


		<div>
			<label for="name">Nome Completo</label>
			<input type="text" name="name" value="" />
		</div>
	
		<div>
			<label for="username">Utilizador</label>
			<input type="text" name="username" value="" />
		</div>
		
		<div>
			<label for="email">E-Mail</label>
			<input type="text" name="email" value="" />
		</div>
		
		<div>
			<label for="passwd">Palavra-Chave</label>
			<input type="password" name="passwd" value="" />
		</div>
		
		<div>
			<label for="passwdConf">Confirmação da Palavra-Chave</label>
			<input type="password" name="passwdConf" value="" />
		</div>
				
	</fieldset>

	<button>Registar ></button>
	
</form>
</div>


</div>