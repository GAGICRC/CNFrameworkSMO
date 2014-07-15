<?php
/*
	@FileName: home.php
	@Description: WebFeels CMS BackEnd Home View
*/

?>

<div class="content page">

<section>
	<header>
		<h1>Bem-Vindo(a) à Área de Gestão!</h1>
	<header>
	<p>Aqui poderá controlar todas as partes e páginas do seu WebSite, utilizando para tal o menu lateral.</p>
	<p>Para mais informações, consulte a <a href="#">documentação</a></p>
</section>

<section>
	<header>
		<h1>Estatísticas</h1>
	<header>
	
	<?php 
		if ($stats) {
		
			print "<p>Estão publicados:</p>
			<ul>";
			
			foreach ($stats as $stat) {
				print "<li><strong>{$stat->count}</strong> {$stat->type};</li>";
			}
			
			print "</ul>";
		} else {
			print "<p><em>Não existe nenhuma publicação :(. Publique agora <a href='/CMS/CMSBackEnd/pagesNew'>uma página</a>!</em></p>";
		}
		
	 ?>
	 
</section>

<section>
	<header>
		<h1>WebFeels - New feels for a new web.</h1>
	</header>
	<section class="column third">
		<header>
			<h2>Funciona</h2>
		</header>
		<p>O WebFeels é um CMS novo, desenvolvido com as mais recentes técnicas de programação. Rápido, fácil de utilizar, altamente modular, e bonito - Estas são as principais características do WebFeels, que o tornam no software web-based ideal não só para quem quer melhor, mas para quem quer <i>o melhor.</i></p>
	</section>
	<section class="column third">
		<header>
			<h2>A nova web</h2>
		</header>
		<p>Com a Web 2.0, e o crescimento exponencial da Internet, tornou-se claro que existia a falta de um sistema funcional e fácil de utilizar para a gestão de WebSites.</p><br /><p>O WebFeels é tudo isso e muito mais!</p>
	</section>
	<section class="column third last">
		<header>
			<h2>À sua medida</h2>
		</header>
		<p>O WebFeels é altamente personalizavel e pode ser fácilmente alterado para responder às suas necessidades, tanto funcionais como gráficas. Graças à sua componente modular, é sempre possível adicionar mais funcionalidades (ou alterar o comportamento das existentes). Tudo isto da <i>forma WebFeels</i>, a fácil!</p>
	</section>
</section>
</div>