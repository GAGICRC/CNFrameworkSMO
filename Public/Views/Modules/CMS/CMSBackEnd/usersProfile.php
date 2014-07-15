<?php
/*
	@FileName: userProfile.php
	@Description: WebFeels CMS BackEnd User Profile View
*/

?>

<div class="content page">

<?php 
if (isset($error))
	print "<p>{$error}</p>";
?>

<h1>O meu perfil</h1>

<p>Gira nesta página os detalhes do seu perfil WebFeels!</p>

<h2>Dados Pessoais</h2>
<form action="" id="personalData" method="POST">
	<fieldset>	       
				<div>
					<label for="name">Nome:</label>
					<input type="text" id="name" name="name" value="<?php print $user->name; ?>" />
					<p class="error">Nome inválido!</p>
				</div>
				  
				<div>
					<label for="username">Alcunha:</label>
					<input type="text" id="username" name="username" value="<?php print $user->username; ?>" />
					<p class="error">Alcunha Inválida!</p>
				</div>  
				       
				<div>
					<label for="email">Email:</label>
					<input type="text" id="email" name="email" value="<?php print $user->email; ?>" />
					<p class="error">Email inválido!</p>
				</div>
				
				<div>
					<label for="passwd">Password:</label>
					<input type="password" id="passwd" name="passwd" />
					<p class="error">A password é insegura!</p>
				</div>
								
				<button name="personalData">Actualizar Dados</button>
		</fieldset>
</form>

<h2>Alterar Palavra-Chave</h2>
<form action="" id="passwdChange" method="POST">
	<fieldset>
				<div>
					<label for="passwd">Nova Palavra-Chave:</label>
					<input type="password" id="passwd" name="passwd" />
					<p class="error">A password é insegura!</p>
				</div>
				
				<div>
					<label for="passwdConf">Confirmação:</label>
					<input type="password" id="passwdConf" name="passwdConf" />
					<p class="error">A password é insegura!</p>
				</div>
								
				<button name="passwdChange">Actualizar Palavra-Chave</button>
		</fieldset>
</form>


</div>