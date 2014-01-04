<?php $this->load->view('front/includes/header');?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css_path');?>front/blogpost.css" />
<?php echo $this->load->view($main);?>
<?php $this->load->view('front/includes/footer');?>