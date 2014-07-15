<?php
/*
	@FileName: pageArchive.php
	@Description: WebFeels CMS BackEnd Archives Pages View
*/

?>

<div class="content page">

<h1>Páginas Arquivadas</h1>

<?php 	
	if (empty($pages)) {
		print "<p><i>Não existem páginas arquivadas!<i></p></div>";
			return;
	}	
?>

<table id="pageTable">
   <thead>
      <tr>
         <th>ID</th>
         <th>Data</th>
         <th>Título</th>
         <th>Conteúdo</th>
         <th>Acções</th>
      </tr>
   </thead>
   <tbody>
   <?php 
   	foreach ($pages as $page) {
   		print "
   		<tr>
   			<td>{$page->id}</td>
  			<td>{$page->date}</td>
  			<td>{$page->title}</td>
  			<td>{$page->content}</td>
  			<td>
  			<button class='actionBtn edit' id='pagesEdit/{$page->id}' title='Editar'></button>
  			<button class='actionBtn publish' id='publicationToStatus1/{$page->id}' title='Publicar'></button>
  			<button class='actionBtn delete' id='publicationToStatus5/{$page->id}' title='Apagar'></button></td>
  		</tr>
   		";
   	}
    ?> 
   </tbody>
</table>

</div>