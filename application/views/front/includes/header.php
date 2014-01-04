<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html>
 <head>
 <meta http-equiv="Content-Type" content="text/html;charset=us-ascii"/>
 <meta name="description" content=""/>
 <meta name="keywords" content=""/>
 <meta http-equiv="expires" content="0" />
 <meta name="Robots" content="index,follow"/>
 <meta name="revisit-after" content="2 Days"/>
 <meta name="language" content="en-us"/>
 <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
 <link rel="shortcut icon" href="<?php echo  $this->config->item('img_path');?>icon.ico" type="image/x-icon" />
 
<link href="<?= base_url() ?>media/css/front/global.css" rel="stylesheet" type="text/css" media="screen" title="default"/>
<link href="<?= base_url() ?>media/css/front/layout.css" rel="stylesheet" type="text/css"/> 
<link href="<?= base_url() ?>media/css/front/menu.css" rel="stylesheet" type="text/css"/> 
<link href="<?= base_url() ?>media/css/front/map.css" rel="stylesheet" type="text/css"/>  
<link href="<?= base_url() ?>media/css/front/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css" /> 
<link href="<?=$this->config->item('css_path')?>front/jquery-ui/jquery-ui-1.8.16.custom.css" rel="stylesheet" type="text/css"/>

<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/js/front/jquery-ui-1.8.16.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/js/front/jquery.easing.1.3.js"></script>
<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/js/front/jquery.mousewheel.min.js"></script> 
<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/js/front/jquery.mCustomScrollbar.js"></script>


<!-- Bootstrap -->
<link href="<?= base_url() ?>media/bootstrap-3.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script language="javascript" type="text/javascript" src="<?= base_url() ?>media/bootstrap-3.0/js/bootstrap.min.js"></script>
<!-- End of Bootstrap -->

<title>
<?php 
if(isset($title))echo $title.' | '.$this->config->item('site_title'); 
else echo $this->config->item('site_title');
?>
</title>
</head>
 
<body style="padding-top:60px;">
  <?php $this->load->view('front/includes/menu'); ?>       
   
  <div class="container">
    <!--<div id="main">-->
