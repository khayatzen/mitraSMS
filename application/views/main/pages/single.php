<div id="page" class="blog">
	<div class="blogpost">
		<div class="header">
			<h2><?php echo $page['PostTitle']; ?></h2>
		</div>
		<div class="content">
			<?php echo $page['PostContent']; ?>
		</div>
	</div>
</div>
<?php $this->load->view('front/widget/sidebar');?>

 
