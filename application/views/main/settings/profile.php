<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
// validation
$("#settingsForm").validate();
});
</script>
<table width="100%" cellpadding="5">
<tr valign="top">
<td width="175px"><label for="address"><?php echo "Avatar"; ?></label></td>
<td>
<img class="avatar" src="<?php if(isset($users)) { echo $users->row('avatar'); } else { echo base_url() . 'media/avatar/burung.jpg'; }; ?>" />
</td>
</tr>

<tr valign="top">
<td width="175px"><label for="address"><?php echo "Address"; ?></label></td>
<td>
<textarea name="address" class="text ui-widget-content ui-corner-all"><?php if(isset($users)) echo $users->row('address'); ?></textarea>
</td>
</tr>

<tr valign="top">
<td><label for="address"><?php echo "Address Location"; ?></label></td>
<td>
<textarea name="address" class="text ui-widget-content ui-corner-all"><?php if(isset($users)) echo $users->row('address'); ?></textarea>
</td>
</tr>	
</table>
<input type="hidden" name="option" value="profile" /> 
