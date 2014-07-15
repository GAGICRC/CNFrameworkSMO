<?php
/*
	@FileName: page.php
	@Description: WebFeels CMS BackEnd Comments View
*/

?>

<div class="content page">

<h1>Comentários Publicados</h1>

<?php 	
	if (empty($comments)) {
		print "<p><i>Não existem comentários!</i></a></p></div>";
			return;
	}		
?>

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
            	<button class='actionBtn delete' id='publicationToStatus5/{$theComment->id}' title='Apagar'></button>
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