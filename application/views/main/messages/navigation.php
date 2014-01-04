<div class="jquerycssmenu">    
<ul>
	<?php 
	if($this->uri->segment(2)=='conversation'):
		if($this->uri->segment(3)=='folder'): 

			// _tni_ added this for translation on the inbox, outbox etc.
			$theFolder = $this->uri->segment(4);
			$theFname = "inbox";
			if($theFolder == "inbox") {
			$theFname = lang('kalkun_inbox');
			} else if($theFolder == "outbox") {
			$theFname = lang('kalkun_outbox');
			} else if($theFolder == "sentitems") {
			$theFname = lang('kalkun_sentitems');
			} else {
					//$theFname = $this->Kalkun_model->get_folders('name', $this->uri->segment(4))->row('name');
			 $theFname = $this->uri->segment(4);
			}
	?>
		<li><?php echo anchor('messages/folder/'.$this->uri->segment(4),'&lsaquo;&lsaquo; '.lang('tni_back_to').' '.$theFname, array('class' => 'button'));?></li>
		<?php else: ?>
		<li><?php echo anchor('messages/my_folder/'.$this->uri->segment(4).'/'.$this->uri->segment(6),'&lsaquo;&lsaquo; '.lang('tni_back_to').' '.humanize($this->Kalkun_model->get_folders('name', $this->uri->segment(6))->row('name')), array('class' => 'button'));?></li>
		<li>&nbsp;</li>
		<?php endif;?>
	<?php endif;?>
	<li><a href="#" class="select_all_button button"><?php echo lang('kalkun_select_all');?></a></li>
	<li><a href="#" class="clear_all_button button"><?php echo lang('kalkun_clear_all');?></a></li>
        
	<?php 
	if($this->uri->segment(2)=='folder' && $this->uri->segment(3)=='outbox'): 
        elseif($this->uri->segment(2)=='folder' && $this->uri->segment(3)=='inbox' || ($this->uri->segment(2)=='conversation' && $this->uri->segment(4)=='inbox')):?> 
        <li><a class="publish_button button" href="#"><?php echo lang('kalkun_publish_message_button');?></a></li>
        <li><a class="spam_button button" href="#"><?php echo lang('kalkun_spam_button');?></a></li>
        <li style="margin-right:10px;">&nbsp;</li>
	<li><a class="move_to_button button" href="#"><?php echo lang('kalkun_move_to');?></a></li> 
        <?php
	elseif($this->uri->segment(2)=='conversation' && $this->uri->segment(4)=='outbox'):
	else:?>
	<li style="margin-right:10px;">&nbsp;</li>
	<li><a class="move_to_button button" href="#"><?php echo lang('kalkun_move_to');?></a></li>        
	<?php endif; ?>
	<li><a class="global_delete button" href="#">
	<?php echo lang('kalkun_delete'); 
	if($this->uri->segment(4)=='5' or $this->uri->segment(6)=='5') echo " ".lang('kalkun_permanently');?></a></li>	
	
	<li>&nbsp;</li>
	<li style="margin-left:10px;"><a href="#" class="refresh_button button"><?php echo lang('kalkun_refresh');?></a></li>	
    		
	<?php if($this->uri->segment(2) != 'search' && $this->pagination->create_links()!=''): ?>
	<li class="paging"><div id="paging"><?php  echo $this->pagination->create_links();?></div></li>
	<?php endif; ?>
    
</ul>
</div>	