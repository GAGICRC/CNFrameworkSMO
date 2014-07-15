<?php
/*
	@FileName: articles.php
	@Description: WebFeels CMS BackEnd Articles View
*/

?>

<div class="content page">

<h1>Artigos Publicados</h1>

<?php 	
	if (empty($articles)) {
		print "<p><i>Não existem artigos. <a href='/CMSBackEnd/articleNew'>Crie um arigo!</a></i></p></div>";
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
  			<td>
  				<button class='actionBtn edit' id='articlesEdit/{$article->id}' title='Editar'></button>
  				<button class='actionBtn archive' id='publicationToStatus4/{$article->id}' title='Arquivar'></button>
  			</td>
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
	if (count($articles) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='articles/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>

</div>