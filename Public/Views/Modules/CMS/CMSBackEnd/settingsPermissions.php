<?php
/*
	@FileName: pages.php
	@Description: WebFeels CMS BackEnd Settings Permissions View
*/

?>

<script>
$(document).ready(function(){ 

	/*$("button#updatePermissions").click(function(){
	
		var permisionString = ""; // todo: convert this lame string stuff into JSON? -- maybe not.
		
		$("input.permVal").each(function(index) {
		 	status = $(this).is(':checked')? 1:0;
		 	permisionString += $(this).attr("id") + "-" + status + ";";
		 	
		});
		
		console.log(permisionString);
		
	}); DEPRECATED */
	
});
</script>

<div class="content page">

<h1>Vista de Permissões</h1>
<p>Nesta página as permissões globais de cada grupo de utilizadores no sistema.</p>
<p>Os utilizadores pertencentes ao grupo Administrador têm todas as permissões e por razões de integridade é impossível de modificar individualmente as suas permissões.</p>

<br />

<form method="post" action="">
	<table id="permissionTable">
	   <thead>
	      <tr>
	         <th>Permissão</th>
	         <?php 
	         	foreach ($roles as $role)
	         		print "<th>{$role}</th>";
	         ?>
	      </tr>
	   </thead>
	   <tbody>
	   <?php
		  foreach ($perms as $permId => $perm) {
		   		print "<tr>
		   			<td>{$perm}</td>";
		   				foreach ($roles as $roleId => $role) {
		   					$permIdx = ($roleId+2) . "-" . ($permId+1);		 			
		   					print "<td><input type='checkbox' class='permVal' name='{$permIdx}' ";
		   					
		   					if (in_array($permIdx, $defPermissions))
		   						print "checked";
		   						
		   					print " /></td>";
		   				}	
		  		print "</tr>
		   		";
		}   	
	    ?> 
	   </tbody>
	</table>
	<br />
	<button id="updatePermissions">Actualizar Permissões</button>
</form>

<br />


</div>