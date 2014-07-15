<?php
/*
	@FileName: settingsGlobal.php
	@Description: WebFeels CMS BackEnd Global Settings View
*/

?>

<div class="content page">

<h1>Definições Globais</h1>

<form method="post" action="">

	<fieldset class="settings">

		<div>
			<label for="pubPeerPage">Publicações por Página (Área Administração):</label>
			<select name="pubPeerPage" id="pubPeerPage">
				<?php 
					for ($i = 10; $i <= 50; $i=$i+10) {
						$status = "";
						if ($i == $options->pubPeerPage) $status = "selected";
						print "<option {$status}>{$i}</option>";
					}
				?>
			</select>
		</div>
		
		<div>
			<label for="defaultPublicationStatus">Estado Padrão das Publicações</label>
			<select name="defaultPublicationStatus" id="defaultPublicationStatus">
				<?php 
					foreach ($possibleStatuses as $status) {
						$SelStatus = "";
						if ($options->defaultPublicationStatus == $status->id) $SelStatus = "selected";
						print "<option value='{$status->id}' {$SelStatus}>{$status->name}</option>";
					}
				?>
			</select>
		</div>
		
		<div>
			<label for="defaultRegStatus">Estado dos Utilizadores Após Registados</label>
			<select name="defaultRegStatus" id="defaultRegStatus">
				<?php 
					$userStatus = array(0 => "Desabilitados", 1 => "Habilitados", 3 => "Pendentes Para Aprovação");
					foreach ($userStatus as $key => $value) {
						$SelStatus = "";
						if ($options->defaultRegStatus == $key) $SelStatus = "selected";
						print "<option value='$key' {$SelStatus}>{$value}</option>";
					}
				?>
			
			</select>
		</div>
		
		<div>
			<label for="userStatusAfterApproval">Estado dos Utilizadores Após Aprovados</label>
			<select name="userStatusAfterApproval" id="userStatusAfterApproval">
				<?php 
					$userStatus = array("Desabilitados","Habilitados");
					foreach ($userStatus as $key => $value) {
						$SelStatus = "";
						if ($options->userStatusAfterApproval == $key) $SelStatus = "selected";
						print "<option value='$key' {$SelStatus}>{$value}</option>";
					}
				?>
			</select>
		</div>
		
		<?php // defaultRole ?>
		
		<div>
			<label for="stdPasswdLen">Tamanho das Palavra-Chaves Padrão</label>
			<input type="text" name="stdPasswdLen" value="<?php print $options->stdPasswdLen; ?>" />
		</div>
		
		<div>
			<label for="maxUploadSize">Tamanho Máximo dos Uploads (KB)</label>
			<input type="text" name="maxUploadSize" value="<?php print $options->maxUploadSize; ?>" />
		</div>
		
		<div>
			<label for="webfeelsEmail">Email do WebFeels</label>
			<input type="text" name="webfeelsEmail" value="<?php print $options->webfeelsEmail; ?>" />
		</div>
		
		<div>
			<label for="sitename">Nome do WebFeels</label>
			<input type="text" name="sitename" value="<?php print $options->sitename; ?>" />
		</div>
		
		<div>
			<label for="initPage">Página Inicial</label>
			<select name="initPage" id="initPage">
			<?php 
				foreach ($possiblePages as $status) {
					$SelStatus = "";
					if ($options->initPage == $status->id) $SelStatus = "selected";
					print "<option value='{$status->id}' {$SelStatus}>{$status->title}</option>";
				}
			?>
			</select>
		</div>
		
		<div>
			<label for="articlesPeerPage">Artigos por Página de Blog</label>
			<input type="text" name="articlesPeerPage" value="<?php print $options->articlesPeerPage; ?>" />
		</div>
			
		<div>
			<label for="defaultCommentStatus">Estado Padrão dos Comentários</label>
			<select name="defaultCommentStatus" id="defaultCommentStatus">
					<?php 
						foreach ($possibleStatuses as $status) {
							$SelStatus = "";
							if ($options->defaultCommentStatus == $status->id) $SelStatus = "selected";
							print "<option value='{$status->id}' {$SelStatus}>{$status->name}</option>";
						}
					?>
			</select>
		</div>	
							
	</fieldset>

	<button>Salvar Definições</button>
	 
</form>

</div>
