<link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css_path');?>jquery-plugin/token-input-facebook.css" />
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-ui/ui.datepicker.min.js"></script>
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.min.js"></script>
<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.tokeninput.min.js"></script>
<style type="text/css">
.ui-datepicker {z-index:10100;}
.left_aligned { margin-left:0; padding-left:0;}
.form_option { width: 100px;}
</style>
<?php $this->load->view('js_init/message/js_compose'); ?>

<?php echo form_open('messages/compose', array('id' => 'composeForm', 'class' => 'composeForm'));?>
<table width="100%">
<?php  
$type = array('inbox', 'sentitems');

// Reply to option
if($val_type=='reply'): ?>
<tr>
<td width="100px" align="right" class="form_label label"><?php echo lang('kalkun_send_to'); ?>:</td>
<td>
<?php
$phone = $dest;
$qry = $this->Phonebook_model->get_phonebook(array('option' => 'bynumber', 'number' => $phone));
if($qry->num_rows()!=0): 
echo $qry->row('Name')." <".$phone.">";
else:
echo $phone;
endif;
?>
<input type="hidden" name="sendoption" value="reply" />
<input type="hidden" name="reply_value" value="<?php echo $phone;?>" />
<input type="hidden" name="id_folder" value="<?php echo $id_folder;?>" />
<input type="hidden" name="id_inbox" value="<?php echo $id_inbox;?>" />
</td>
</tr>

<?php /* Member */ elseif($val_type=='member'):?>
<tr>
<td width="100px" align="right" class="form_label label"><?php echo lang('kalkun_send_to'); ?>:</td>
<td>Member<input type="hidden" name="sendoption" value="member" /></td>    
</tr>

<?php /* Phonebook contact */ elseif($val_type=='pbk_contact'):?>
<tr>
<td width="100px" align="right" class="form_label label"><?php echo lang('kalkun_send_to'); ?>:</td>
<td>
<?php
$qry = $this->Phonebook_model->get_phonebook(array('option' => 'bynumber', 'number' => $dest));
if($qry->num_rows()!=0): 
echo $qry->row('Name')." <".$dest.">";
else:
echo $dest;
endif;
?>
<input type="hidden" name="sendoption" value="reply" />
<input type="hidden" name="reply_value" value="<?php echo $dest;?>" />
</td>    
</tr>

<?php /* Phonebook group */ elseif($val_type=='pbk_groups'):?>
<tr>
<td width="100px" align="right" class="form_label label"><?php echo lang('kalkun_send_to'); ?>:</td>
<td>
<?php echo $this->Phonebook_model->get_phonebook(array('option' => 'groupname', 'id' => $dest))->row('GroupName');?>
<input type="hidden" name="sendoption" value="pbk_groups" />
<input type="hidden" name="id_pbk" value="<?php echo $dest;?>" />
</td>    
</tr>
    	
<?php /* Forward to option */ else: ?>
<tr>
<td width="100px" align="right" class="label">
<?php
if($val_type=='forward'){
    echo '<input type="hidden" name="is_forward" value="1" />';
    echo lang('kalkun_forward_to').":";    
} 
else echo lang('kalkun_send_to').":";
?>
</td>
<td>
<span class="form_option">
<input type="radio" id="sendoption1" name="sendoption" value="sendoption1" checked="checked" class="left_aligned" style="border: none;" />
<label for="sendoption1"><?php echo lang('kalkun_contact');?></label>
</span>
<input type="radio" id="sendoption2" name="sendoption" value="sendoption2" style="border: none;" />
<label for="sendoption2"><?php echo lang('kalkun_group');?> </label>
<input type="radio" id="sendoption3" name="sendoption" value="sendoption3" style="border: none;" />
<label for="sendoption3"><?php echo lang('kalkun_input_manually');?> </label>
</td>    
</tr>
    
<tr>
<td>&nbsp;</td>
<td>
<div id="person">
<textarea id="personvalue" style="width: 95%;" name="personvalue" /></textarea>
</div>

<div id="manually" class="hidden">
<input style="width: 95%;" type="text" name="manualvalue" />
</div>

<div id="group" class="hidden">
<select name="groupvalue">
<option value="" selected="selected">-- <?php echo lang('tni_group_select'); ?> --</option>
<?php
foreach($this->Phonebook_model->get_phonebook(array('option' => 'group'))->result() as $tmp):
echo "<option value=\"".$tmp->ID."\">".$tmp->GroupName."</option>";
endforeach; 
?>
</select>
</div>
</td>
</tr>
<?php endif; ?>

<tr>
<td align="right" class="label"><?php echo lang('kalkun_send_date').":";?></td>
<td>
<input class="left_aligned" type="radio" id="option1" name="senddateoption" value="option1" checked="checked" style="border: none;" />
<label for="option1"><?php  echo lang('kalkun_now');?></label>
<input type="radio" id="option2" name="senddateoption" value="option2" style="border: none;"/>
<label for="option2"><?php  echo lang('kalkun_at_date_time');?></label>
<input type="radio" id="option3" name="senddateoption" value="option3" style="border: none;" />
<label for="option3"><?php  echo lang('kalkun_after_a_delay');?></label>
</td>    
</tr>
    
<tr>
<td>&nbsp;</td>
<td>
<div id="nowoption">
</div>
<div id="dateoption" class="hidden">
<input type="text" name="datevalue" id="datevalue" class="datepicker" readonly="readonly" />
<?php echo nbs(2);?>
<select name="hour"><?php echo get_hour();?></select> :
<select name="minute"><?php echo get_minute();?></select> 			
</div>
<div id="delayoption" class="hidden">
<select name="delayhour"><?php echo get_hour();?></select> <?php echo lang('kalkun_compose_hour'); ?>&nbsp;
<select name="delayminute"><?php echo get_minute();?></select> <?php echo lang('kalkun_compose_minutes'); ?> 
</div>
</td>
</tr>    

<tr>
<td align="right" class="label">Validity</td>
<td><select size="1" name="validity">
	<option value="-1">Default</option>
	<option value="0">5 Min</option>
	<option value="1">10 Min</option>
	<option value="5">30 Min</option>
	<option value="11">1 Hour</option>
	<option value="23">2 Hour</option>
	<option value="35">4 Hour</option>
	<option value="143">12 Hour</option>
	<option value="167">1 Day</option>
	<option value="168">2 Day</option>
	<option value="171">5 Day</option>
	<option value="173">1 Week</option>
	<option value="180">2 Week</option>
	<option value="196">4 Week</option>
	<option value="255">Maximum</option>
</select></td>
</tr>

<?php if($this->config->item('sms_bomber')): ?>    
<tr valign="top">
<td align="right" class="label"><?php echo lang('kalkun_compose_amount').":";?></td>
<td><input type="text" style="width: 25px" name="sms_loop" id="sms_loop" value="1" />&nbsp; <?php echo lang('kalkun_compose_times'); ?>
</td>
</tr>
<?php else: ?>
<input type="hidden" name="sms_loop" id="sms_loop" value="1" />
<?php endif;?>

<tr>
<td align="right" class="label"><?php echo lang('kalkun_sms_mode').":";?></td>
<td>
    <input type="checkbox" id="broadcast_mode" name="broadcast_mode" value="1" class="left_aligned" style="border: none;" />
    <label for="broadcast_mode"><?php echo lang('kalkun_broadcast_mode'); ?></label>
    
    <input type="checkbox" id="sms_mode" name="sms_mode" value="1" class="left_aligned" style="border: none;" />
    <label for="sms_mode"><?php echo lang('kalkun_compose_send_as_flash'); ?></label>
    
    <div style="float: right; text-align: right; padding-right: 10px;"><a href="javascript:void(0)"  id="canned_response"> Canned Responses...</a></div>
</td>    
</tr>    
 

<tr valign="top">
<td align="right" class="label"><?php echo lang('kalkun_message').":";?></td>
<td>
<textarea class="word_count" style="width: 400px; line-height: 16px; min-height: 50px;"   id="message" name="message">
<?php 
if($val_type=='forward') echo $message;
list($sig_option, $sig)=explode(';',$this->Kalkun_model->get_setting()->row('signature'));
if($sig_option=='true') echo "\n\n".$sig; ?>
</textarea>
<div>
	<div style="float: left"><span class="counter"></span></div>
	<div style="float: right; padding-right: 5px;"><?php if($this->config->item('ndnc')) { ?><input class="left_aligned" type="checkbox" value="ndnc" id="ndnc" name="ndnc" style="border: none;" /><label for="ndnc">Check NDNC </label>  <?php }?><input class="left_aligned" type="checkbox" value="unicode" id="unicode" name="unicode" style="border: none;" /><label for="unicode"><?php echo lang('kalkun_compose_send_as_unicode'); ?></label></div>
</div>
</td>
</tr>      
</table>
<br />
<?php  echo form_close();?>
<div id="canned_response_container" > </div>
<?php
if($this->config->item('sms_advertise'))
{
	echo "*Ads is active";
}
?>