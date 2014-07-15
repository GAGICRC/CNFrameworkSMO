<?php
/*
	@FileName: articleArchive.php
	@Description: WebFeels CMS BackEnd Article Archive View
*/

?>

<div class="content page">

<h1>Artigos por Moderar</h1>

<?php 	
	if (empty($articles)) {
		print "<p><i>Não existem artigos arquivados!</i></a></p></div>";
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
				<button class='actionBtn publish' id='publicationToStatus1/{$article->id}' title='Aprovar'></button>
				<button class='actionBtn delete' id='publicationToStatus5/{$article->id}' title='Apagar'></button>
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
	if (count($article) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='articles/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>


</div>