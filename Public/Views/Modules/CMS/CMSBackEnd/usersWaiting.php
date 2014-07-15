<?php
/*
	@FileName: usersWaiting.php
	@Description: WebFeels CMS BackEnd Users Waiting Approval View
*/

?>

<div class="content page">

<h1>Registos a aguardar aprovação</h1>

<p>Aprove aqui os registos na plataforma. Não se esqueça de <a href="/CMS/CMSBackEnd/users">definir aqui</a> o papel do utilizadores após concluir as aprovações!</p>

<?php 	
	if (empty($peding)) {
		print "<p><i>Não existem registos a aguardar aprovação!<i></p>";
		exit;
	}	
?>

<br />

<table id="pageTable">
   <thead>
      <tr>
         <th>ID</th>
         <th>Nome</th>
         <th>Alcunha</th>
         <th>Email</th>
         <th>Acções</th>
      </tr>
   </thead>
   <tbody>
   <?php 
   	foreach ($peding as $peding) {
   		print "
   		<tr>
   			<td>{$peding->id}</td>
  			<td>{$peding->name}</td>
  			<td>{$peding->username}</td>
  			<td>{$peding->email}</td>
  			<td><button class='actionBtn' id='usersApprove/{$peding->id}' title='Aprovar'>Aprovar</button></td>
  		</tr>
   		";
   	}
    ?> 
   </tbody>
</table>

</div>