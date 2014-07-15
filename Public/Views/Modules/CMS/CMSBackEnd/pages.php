<?php
/*
	@FileName: pages.php
	@Description: WebFeels CMS BackEnd Pages View
*/

?>

<div class="content page">

<h1>Páginas Publicadas</h1>

<?php 	
	if (empty($pages)) {
		print "<p><i>Não existem páginas. <a href='/CMSBackEnd/pageNew'>Crie uma página!</a></i></p></div>";
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
  				<button class='actionBtn archive' id='publicationToStatus4/{$page->id}' title='Arquivar'></button>
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
	if (count($pages) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='pages/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>

</div>