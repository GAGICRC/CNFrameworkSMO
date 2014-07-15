<?php
/*
	@FileName: files.php
	@Description: WebFeels CMS BackEnd Files View
*/

?>

<div class="content page">

<h1>Ficheiros Guardados</h1>

<?php 	
	if (empty($files)) {
		print "<p><i>Não existem ficheiros guardados. <a href='/CMSBackEnd/filesUpload'>Envie um agora!</a></i></p></div>";
		return;
	}	
?>

<button class='actionBtn' id='filesUpload' title='Enviar'>Enviar Ficheiro</button>
<br /> <br />

<table id="pageTable">
   <thead>
      <tr>
         <th>ID</th>
         <th>Data</th>
         <th>Nome</th>
         <th>Endereço</th>
         <th>Tamanho</th>
         <th>Acções</th>
      </tr>
   </thead>
   <tbody>
   <?php 
   	foreach ($files as $file) {
   		print "
   		<tr>
   			<td>{$file->id}</td>
  			<td>{$file->date}</td>
  			<td>{$file->name}</td>
  			<td><a href='{$file->storage}' target='_blank'>{$file->storage}</a></td>
  			<td>{$file->size} KB</td>
  			<td>
  				<button class='actionBtn delete' id='filesDelete/{$file->id}' title='Apagar'></button>
  			</td>
  		</tr>
   		";
   	}
    ?> 
   </tbody>
</table>

<br />

<?php 
	if ($currentPage > 1) {	
		$currentPage--;
		print "<button class='actionBtn' id='pages/{$currentPage}' title='Editar'>< Públicações Anteriores</button>";
	}
	if (count($files) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='pages/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>

</div>