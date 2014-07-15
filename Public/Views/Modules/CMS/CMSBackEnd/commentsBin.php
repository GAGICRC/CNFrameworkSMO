<?php
/*
	@FileName: commentsBin.php
	@Description: WebFeels CMS BackEnd Comments Bin View
*/

?>

<div class="content page">

<h1>Lixeira de Comentários</h1>

<?php 	
	if (empty($comments)) {
		print "<p><i>Não existem comentários no lixo!</i></a></p></div>";
			return;
	}	
?>

<button class="actionBtn" id="emptyBin/3">Esvaziar Lixo!</button>

<?php 
	foreach ($comments as $comment) {
		print "
<h2>Artigo: {$comment->title}</h2>
<table>
   <thead>
      <tr>
         <th>ID</th>
         <th>Data</th>
         <th>Conteúdo</th>
         <th>Acções</th>
      </tr>
   </thead>
   <tbody>";
   
   	foreach ($comment->comments as $theComment) {
   		print "<tr>
   				<td>{$theComment->id}</td>
   			<td>{$theComment->date}</td>
   			<td>{$theComment->content}</td>
            <td>
            	<button class='actionBtn edit' id='commentsEdit/{$theComment->id}' title='Editar'></button>
            	<button class='actionBtn archive' id='publicationToStatus3/{$theComment->id}' title='Restaurar'></button>
            </td>
   		</tr>
   		";
   	}
   
   		
   print "</tbody>
</table>
";
}

?>

</div>