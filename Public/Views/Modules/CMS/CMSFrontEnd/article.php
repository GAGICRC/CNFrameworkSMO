<?php
/*
	@FileName: article
	@Description: WebFeels CMS Module Article View
*/
 
?>

<div class="content page">

<section>
	<header>
		<h1><?php print $article->title; ?></h1>
		<div class="pageInfo">Publicado a <time datetime="<?php print $article->date; ?>"><?php print $article->date; ?></time>, por <?php print $article->author_name; ?>.</div>
	</header>
	
	<p><?php print $article->content; ?></p>
	
</section>



<section class="comments" id="comments">
<h2 class="diff">Comentários</h2>
<p class="statusMsg"><?php if (isset($statusMsg)) print $statusMsg; ?></p>
<?php if($comments) {
	foreach ($comments as $key => $comment) {
?>
<article>
	
	<img class="gravatar" src="http://www.gravatar.com/avatar/<?php print md5($comment->author_email)?>?s=100" />
	<h3 class="diff">Publicado às <time datetime="<?php print $comment->date; ?>"><?php print $comment->date; ?></time> por <?php print $comment->author_name; ?>.</h3>
	<p><?php print $comment->content; ?></p>
</article>
<?php 
	}
} ?>

</section>
<div class="clear"></div>
<section class="mycomment" id="mycomment">
	<header>
		<h2 class="diff">Deixe o seu comentário...</h2>
	</header>
		
	<form action="/article/<?php print $article->id ?>" id="comment" method="POST">
		<fieldset>	       
					<div>
						<input placeholder="Nome" type="text" id="name" name="name" />
					</div>
					       
					<div>
						<input placeholder="Email" type="text" id="email" name="email" />
					</div>
					
					<div>
						<textarea placeholder="Conteúdo..." name="content" id="content"></textarea>
					</div>
									
					<button name="comment">Enviar Comentário!</button>
			</fieldset>
	</form>
	
</section>
<br />

</div>