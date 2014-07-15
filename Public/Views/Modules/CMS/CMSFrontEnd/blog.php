<?php
/*
	@FileName: blog.php
	@Description: WebFeels CMS Module Blog View
*/

?>

<div class="content page">

<?php
foreach ($articles as $key => $article) {
?>
<article>
	<header>
		<h1><?php print "<a href='/article/{$article->id}'>{$article->title}</a>"; ?></h1>
		<div class="pageInfo">Publicado a <time datetime="<?php print $article->date; ?>"><?php print $article->date; ?></time>, por <?php print $article->author_name; ?>.</div>
	</header>

	<p><?php print $article->content; ?></p>

	<footer>
		<p>
		<?php 
			if ($article->comments_open)
				print "Os comentários a esta publicação estão abertos. <button class='actionBtn' id='article/{$article->id}#comments'>Comente agora!</button>";
			else
				print "Os comentários a esta publicação estão fechados.";
		?>
		</p>
	</footer>
</article>
<?php 
}
?>

<?php 
	if ($currentPage > 1) {	
		$currentPage--;
		print "<button class='actionBtn' id='blog/{$currentPage}' title='Editar'>< Públicações Anteriores</button>";
	}
	if (count($articles) == 10) {
		$currentPage++;
		print "
			<button class='actionBtn' id='blog/{$currentPage}' title='Editar'>Próximas Públicações ></button>
		";
	}	
?>

</div>
