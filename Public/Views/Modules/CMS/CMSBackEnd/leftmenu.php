<?php
/*
	@FileName: leftmenu.php
	@Description: WebFeels CMS Module Page View
*/

?>

<script>
$(document).ready(function(){ 
	
	function resizeMenu() {
		if ( $('.leftmenu').height() < ($(window).height()-$('.backendtop.onbg').height()) )
			$('.leftmenu').height($(window).height()-$('.backendtop.onbg').height()-$('footer.master').height());
		
		if ( $('.leftmenu').height() < $('.content.page').height() )
			$('.leftmenu').height($('.content.page').height()+$('footer.master').height());
	}
	
	resizeMenu(); // Redimensionamento incial.
	$(window).resize(resizeMenu); // Chama a função resizeMenu() cada vez que a janela é redimensionada.
	
	$(".leftmenu .onbg a").click(function(){
		resizeMenu(); // Recalcula o tamanho do menu tendo em conta as opções que estão abertas... poderá não funcionar.
	});

	//THIS IS THE MENU ACCORDION JS

    $('div#sideNav li > ul').hide(0, resizeMenu);    //hide all nested ul's
    $('div#sideNav li > ul li a[class=current]').parents('ul').show().prev('a').addClass('accordionExpanded');  //show the ul if it has a current link in it (current page/section should be shown expanded)
    $('div#sideNav li:has(ul)').addClass('accordion');  //so we can style plus/minus icons
    $('div#sideNav li:has(ul) > a').click(function() {
        $(this).toggleClass('accordionExpanded'); //for CSS bgimage, but only on first a (sub li>a's don't need the class)
        $(this).next('ul').slideToggle('fast');
        $(this).parent().siblings('li').children('ul:visible').slideUp('fast')
            .parent('li').find('a').removeClass('accordionExpanded');
        resizeMenu();
        return false;
    });
});
</script>

<nav class="leftmenu onbg">
	<div id="sideNav">
	<ul>
	<?php 
		
		foreach ($menuItems as $menuItem) {
			$theValue = reset($menuItem);
			$firstKey = key($menuItem);
			
			print "<li>";
			print "<a href='{$firstKey}'>{$theValue}</a>";
			
			if (count($menuItem) > 1) {
				print "<ul>";
				foreach ($menuItem as $subMenuKey => $subMenuItem) {
					if ($subMenuKey === $firstKey) continue;
					print "<li><a href='/CMSBackEnd/{$firstKey}{$subMenuKey}'>{$subMenuItem}</a></li>";
				}
				print "</ul>";	
			}
			print "</li>";
			
		}
	?>
	</ul>
</div>
</nav>

