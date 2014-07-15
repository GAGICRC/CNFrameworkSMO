<?php
/*
	@FileName: footer.php
	@Description: WebFeels Global Footer View
*/

?>

<div class="clear"></div>

<script>
	function relocateFooter() {
		if ( $("#page").height() < $(window).height() ) {
			marginTop = ($(window).height() - ($("#page").height())+20);
			$("#pagefooter").css({"margin-top":marginTop+"px"});
		}	
	}
	
	$(document).ready(function(){ 
		$(window).resize(relocateFooter);
	});
	
	$(window).load(function () {
		relocateFooter();
	});
</script>

<footer class="master onbg" id="pagefooter">
	<p>Copyright © DSAS (WebFeels TaskForce) 2013 All Rights Reserved.</p>
</footer>

</div>