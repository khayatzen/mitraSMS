<?php $this->load->view('front/includes/header');?>
<link href="<?= $this->config->item('css_path') ?>front/auth.css" rel="stylesheet" type="text/css"> 
<div id="auth-container">
        <?php $form_item = $this->config->item($form_name,'tank_auth');?>
        <div class="auth-title"><?=$form_item['title'];?></div>
        <div class="auth-content">
            <div class="auth-description"><?= $form_item['description'];?></div>
            <?php $this->load->view('auth/'.$form_name,$form_vars);?>
        </div>    
</div>

<?php $this->load->view('front/includes/footer');?>
