<?php
/*
	@FileName: page.php
	@Description: WebFeels CMS Module Page View
*/

?>

<div class="content page">

<section>
	<?php 
		if (!$isHome) {
	?>
	<header>
		<h1><?php print $page->title; ?></h1>
		<div class="pageInfo">Publicado a <time datetime="<?php print $page->date; ?>"><?php print $page->date; ?></time>, por <?php print $page->author_name; ?>.</div>
	</header>
	<?php 
	}
	?>
	<p><?php print $page->content; ?></p>
	
</section>

</div>