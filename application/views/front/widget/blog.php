<script type="text/javascript">
$(document).ready(function(){
$.post('<?php echo site_url(); ?>/site/ajax_blogpage', {'t' : 't'}, function(data){
	$('#BlogPost').html(data); }); return false;
	if($("div.customScrollBox").size() > 0) mCustomScrollbar();
});
</script>
<div id="BlogPost">
</div>
