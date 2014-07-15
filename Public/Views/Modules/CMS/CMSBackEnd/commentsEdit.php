<?php
/*
	@FileName: commentsEdit.php
	@Description: WebFeels CMS BackEnd Edit Comments View
*/

?>

<script src="/Views/js/supa_markdown.js"></script>
<script>
$(document).ready(function(){ 
	$('.editor').MarkdownEditor();
});
</script>

<div class="content page">

<h1>Editar Comentário</h1>

<form method="post" action="" class="newPublication">
	<fieldset>			
		<div class="editor">
		    <div class="controls clear-block hide-if-no-js">
		      <a class="control c-h1" accesskey="H">Título 1</a>
		      <a class="control c-h2" accesskey="h">Título 2</a>
		      <a class="control c-bold" accesskey="b">Bold</a>
		      <a class="control c-italic" accesskey="i">Itálico</a>
		      <a class="control c-link" accesskey="a">Link</a>
		      <a class="control c-image" accesskey="m">Imagem</a>
		      <a class="control c-quote" accesskey="q">Quote</a>
		      <a class="control c-code" accesskey="c">Código</a>
		    </div>
		    <textarea name="content" placeholder="Conteúdo..."><?php print $comment->content; ?></textarea>
		</div>
		 <input type="hidden" name="commentId" value="<?php print $comment->id ?>" /> 		  
		<button>Guardar Alterações</button>
	</fieldset>
</form>

</div>