<script language="javascript" src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
        // validation
        $("#settingsForm").validate();
        //Region action DOM
        $("select.district").change(function(){
              $.getJSON("<?=site_url('phonebook/get_subdistricts')?>",{district_id: $(this).val(), is_ajax: 'true'}, function(j){
                  var options = '';
                  for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].subdistrict_id + '">' + j[i].subdistrict_name + '</option>';
                  }
                  $("select#subdistrict").attr('disabled',false);
                  $("select#subdistrict").html(options);
              })

        });
        $("select.subdistrict").change(function(){
              $.getJSON("<?=site_url('phonebook/get_villages')?>",{subdistrict_id: $(this).val(), is_ajax: 'true'}, function(j){
                  var options = '';
                  for (var i = 0; i < j.length; i++) {
                    options += '<option value="' + j[i].village_id + '">' + j[i].village_name + '</option>';
                  }
                  $("select#village").attr('disabled',false);
                  $("select#village").html(options);
              })

        });

});

</script>
<table width="100%" cellpadding="5">
<tr valign="top">
<td width="175px"><?php echo lang('tni_contact_name'); ?></td>
<td>
<input type="text" name="real_name" value="<?php echo $settings->row('real_name');?>" />
</td>
</tr>

<tr valign="top">
<td>Username</td>
<td>
<input type="text" name="username" value="<?php echo $settings->row('username');?>" />
</td>
</tr>	

<tr valign="top">
<td><?php echo lang('tni_contact_phonenumber'); ?></td>
<td>
<input type="text" name="phone_number" value="<?php echo $settings->row('phone_number');?>" />
</td>
</tr> 

<tr valign="top">
<td><?php echo lang('tni_email_address'); ?></td>
<td>
<input type="text" name="email" class="email" value="<?php echo $settings->row('email');?>" />
</td>
</tr> 
<!-- Address -->
<tr>
    <td>Provinsi</td>
    <td>
        <?php
        $province_act = (isset($province_id)) ? $province_id : '';
        $option = 'class="text ui-widget-content ui-corner-all province" id="province"';
        echo form_dropdown('province_id',$province_list,$province_act,$option);
        ?>
    </td>
</tr>
<tr>
    <td>Kabupaten</td>
    <td>
        <?php
        $district_act = (isset($district_id)) ? $district_id : '';
        $option = 'class="text ui-widget-content ui-corner-all district" id="district"';
        echo form_dropdown('district_id',$district_list,$district_act,$option);
        ?>
    </td>
</tr>
<tr>
    <td>Kecamatan</td>
    <td>
        <?php
        $subdistrict_act = (isset($subdistrict_id)) ? $subdistrict_id : '';
        $option = 'class="text ui-widget-content ui-corner-all subdistrict" id="subdistrict"';
        echo form_dropdown('subdistrict_id',$subdistrict_list,$subdistrict_act,$option);
        ?>
    </td>
</tr>
<tr>
    <td>Desa</td>
    <td>
        <?php
        $village_act = (isset($settings)) ? $settings->row('village_id') : '';
        $option = 'class="text ui-widget-content ui-corner-all village" id="village"';
        echo form_dropdown('village_id',$village_list,$village_act,$option);
        ?>
    </td>
</tr>
<tr>
    <td valign="top">Alamat</td>
    <td>
        <textarea name="address" id="address" cols="40" rows="3" ><?php echo $settings->row('address');?></textarea>
    </td>
</tr>

<tr valign="top">
<td><?php echo lang('tni_signature'); ?><br /><small><?php echo lang('tni_signature_hint'); ?></small></td>
<td>
<?php list($sig_option, $sig) = explode(';',$settings->row('signature'));?>
<input type="radio" id="signature_off" name="signatureoption" value="false" 
<?php if($sig_option=='false') echo "checked=\"checked\""; ?>  /> 
<label for="signature_off"><?php echo lang('tni_signature_off'); ?></label><br />
<input type="radio" id="signature_on" name="signatureoption" value="true"
<?php if($sig_option=='true') echo "checked=\"checked\""; ?> />
<label for="signature_on"><?php echo lang('tni_signature_on'); ?></label><br />
<textarea name="signature" rows="5" cols="40"><?php echo $sig; ?></textarea>
<div class="note"><?php echo lang('tni_signature_hintb'); ?></div>
</td>    
</tr>    
</table>
<input type="hidden" name="option" value="personal" /> 