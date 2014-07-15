<?php
/*
	@FileName: pages.php
	@Description: WebFeels CMS BackEnd Page Drafts View
*/

?>

<div class="content page">

<h1>Rascunhos de Páginas</h1>

<?php 	
	if (empty($pages)) {
		print "<p><i>Não existem páginas no rascunho!<i></p></div>";
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
  				<button class='actionBtn publish' id='publicationToStatus4/{$page->id}' title='Publicar'></button>
  				<button class='actionBtn delete' id='publicationToStatus5/{$page->id}' title='Apagar'></button>
  			</td>
  		</tr>
   		";
   	}
    ?> 
   </tbody>
</table>

</div>