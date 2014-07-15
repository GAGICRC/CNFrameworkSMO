<?php
/*
	@FileName: users.php
	@Description: WebFeels CMS BackEnd Users View
*/

?>

<script>
	$(document).ready(function(){ 
		$("button.actionBtnUsersChangeSettings").click(function(){
			id = $(this).attr("id");
			idSplit = id.split("/");
			selectedRole = $("#userRole-" + idSplit[1]).find(":selected").val();
			selectedStatus = $("#userStatus-" + idSplit[1]).find(":selected").val();
			window.location = "/CMS/CMSBackEnd/" + idSplit[0] + "/" + idSplit[1] + "/" + selectedRole + "/" + selectedStatus;
		});
	});
	
</script>

<div class="content page">

<h1>Vista de Utilizadores</h1>

<table id="pageTable">
   <thead>
      <tr>
         <th>ID</th>
         <th>Nome</th>
         <th>Alcunha</th>
         <th>Email</th>
         <th>Papel</th>
         <th>Estado</th>
         <th>Acções</th>
      </tr>
   </thead>
   <tbody>
   <?php   
   
   	foreach ($users as $user) {
   		print "
   		<tr>
   			<td>{$user->id}</td>
  			<td>{$user->name}</td>
  			<td>{$user->username}</td>
  			<td>{$user->email}</td>";
  			
  		if ($user->id == $_SESSION['userId'])
  			print "<td>N/A</td>";
  		else {	
  			print "<td><select id='userRole-{$user->id}'>";
  				foreach ($possibleRoles as $role) {
  					print "<option value='{$role->id}' ";
  					
  					if ($user->id_role == $role->id)
  						print "selected";
  					
  					print " >{$role->name}</option>";
  				}
  			print "</select></td>";
  		}	
  		
  		if ($user->id == $_SESSION['userId'])
  			print "<td>N/A</td>";
  		else {	
  			print "<td><select id='userStatus-{$user->id}'>";
  				foreach ($possibleStatuses as $key => $status) {
  					print "<option value='{$key}' ";
  						if ($user->status == $key)
  							print "selected";
  					print " >{$status}</option>";
  				}
  			print "</select></td>";
  		}	
  			
  		print "	<td>
  					<button class='actionBtnUsersChangeSettings' id='usersChangeSettings/{$user->id}'>GUARDAR</button>
  					<button class='actionBtn' id='usersDelete/{$user->id}' title='Vai apagar o utilizador!'>APAGAR</button>
  					<button class='actionBtn' id='usersPasswdReset/{$user->id}' title='Cria uma password nova, e envia por email para o utilizador'>Reset Password</button>
  				</td>";
  		
  		print"</tr>
   		";
   	}
    ?> 
   </tbody>
</table>


</div>