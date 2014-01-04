<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_path')?>front/blogpost.css"/>

<script type="text/javascript">
$(document).ready(function(){
$.post('<?php echo site_url(); ?>/site/ajax_blogpage', {'t' : 't'}, function(data){
	$('#archive').html(data); }); return false;	
});
</script>

<div id="post-archive">
    <div id="archive"></div>
</div>

<?php $this->load->view('front/widget/sidebar');?>