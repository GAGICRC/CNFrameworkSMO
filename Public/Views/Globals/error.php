<?php
/*
	@FileName: error.php
	@Description: WebFeels Global Error View
*/

?>

<div class="content page">

<h1>Oops! Ocorreu um erro...</h1>

	<img class="errorMsg" src="/Views/img/beer.jpg" width="170px"/>
	<div class="errorMsg">
		<p><strong>Erro <?php print $errorid; ?></strong>: <?php print $errormsg; ?></p>
		<p><i>Go have a beer, relax, comeback, and don't mess up again.</i></p>
	</div>
</div>