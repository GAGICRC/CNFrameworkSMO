<?php
/*
	@FileName: articlesEdit.php
	@Description: WebFeels CMS BackEnd Edit Article View
*/

?>

<script src="/Views/js/supa_markdown.js"></script>
<script>
$(document).ready(function(){ 
	$('.editor').MarkdownEditor();
});
</script>

<div class="content page">

<h1>Editar Artigo</h1>

<form method="post" action="" class="newPublication">
	<fieldset>
	
		<input type="text" name="title" id="editor-title" placeholder="Título do Artigo..." value="<?php print $article->title; ?>" />	
			
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
		    <textarea name="content" placeholder="Conteúdo..."><?php print $article->content; ?></textarea>
		</div>
		
		<div>
			<label for="comments_open">Comentários</label>
			<select name="comments_open">
				<?php 
					$discussion = array("Fechados", "Abertos");
					foreach ($discussion as $key => $value) {
						$status = "";
						if ($key == $article->comments_open) $status = "selected";
						print "<option value='{$key}' {$status}>{$value}</option>";
					}
				?>
			</select>
		</div>
		
		 <input type="hidden" name="articleId" value="<?php print $article->id ?>" /> 		  
		<button>Guardar Alterações</button>
	</fieldset>
</form>

</div>