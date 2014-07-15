<?php
/*
	@FileName: articlesDrafts.php
	@Description: WebFeels CMS BackEnd Articles Drafts View
*/

?>

<div class="content page">

<h1>Rascunhos de Artigos</h1>

<?php 	
	if (empty($articles)) {
		print "<p><i>Não existem rascunhos de artigos!</i></p></div>";
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
   	foreach ($articles as $article) {
   		print "
   		<tr>
   			<td>{$article->id}</td>
  			<td>{$article->date}</td>
  			<td>{$article->title}</td>
  			<td>{$article->content}</td>
  			<th>
  				<button class='actionBtn edit' id='articlesEdit/{$article->id}' title='Editar'></button>
  				<button class='actionBtn publish' id='publicationToStatus4/{$article->id}' title='Publicar'></button>
  				<button class='actionBtn delete' id='publicationToStatus5/{$article->id}' title='Apagar'></button>
  			</th>
  		</tr>
   		";
   	}
    ?> 
   </tbody>
</table>

<?php 
	if ($currentPage > 1) {	
		$currentPage--;
		print "<button class='actionBtn' id='articles/{$currentPage}' title='Editar'>< Públicações Anteriores</button>";
	}
	if (count($article) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='articles/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>

</div>