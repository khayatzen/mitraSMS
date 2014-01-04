<link rel="stylesheet" type="text/css" href="<?=$this->config->item('css_path')?>front/blogpost.css"/>

<script type="text/javascript">
$(document).ready(function(){
$.post('<?php echo site_url(); ?>/site/get_archive_blog', {'t' : 't'}, function(data){
	$('#BlogPost').html(data); }); return false;	
});
</script>

<div id="post-archive">
    <div id="BlogPost"></div>
</div>

<?php $this->load->view('front/widget/sidebar');?>