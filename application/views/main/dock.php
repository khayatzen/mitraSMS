<div id="top_navigation_container">
        <a class="site-title" href="<?= base_url();?>"><?= $this->config->item('site_title');?></a>
	<div id="top_navigation_left">              
              
		<span class="modem_status">
		<?php $this->load->view('main/notification');?>		
		</span>	
	</div>
	
	<div id="top_navigation_center">
		<?php echo date('l M dS, Y, h:i A');?>
	</div>

	<div id="top_navigation_right">            
		<?php echo $this->Kalkun_model->get_setting()->row('real_name');?> | 
		<a href="<?php echo site_url('settings/general');?>" id="setting"><?php echo lang('tni_settings'); ?></a> | 
		<a href="<?php echo site_url('logout');?>" id="logout"><?php echo lang('kalkun_logout');?></a>
	</div>
</div>
