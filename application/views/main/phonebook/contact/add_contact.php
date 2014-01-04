<!-- Contact dialog -->

<script src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.min.js"></script>
<script src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.validate.phone.js"></script>
<script src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.tagsinput.js"></script>
<script src="<?php echo $this->config->item('js_path');?>address.map.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_path');?>jquery-plugin/jquery.tagsinput.css" />
<script src="<?php echo $this->config->item('js_path');?>jquery-plugin/jquery.autocomplete.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_path');?>jquery-plugin/jquery.autocomplete.css" />
<?php //$this->load->view('js_init/phonebook/js_add_contact');?>
<script type="text/javascript">
$(document).ready(function() {
    <?php
    $group = $this->Phonebook_model->get_phonebook(array('option' => 'group'));
    $grouptext = '';
    foreach($group->result() as $tmp):
    	$grouptext .= $tmp->GroupName.';';
    endforeach; 
    $grouptext = substr($grouptext,0, strlen($grouptext)-1);
    ?>
    var grp_data = "<?=$grouptext?>".split(";");
    $('#groups').tagsInput({
        'autocomplete_url' : grp_data,
        'autocomplete':{matchContains:false},
        'height':'50px',
        'width':'270px',
       'defaultText':'Select Group'
    });
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
//window.onload(function(){
//    initAlamat();
//});
</script>

<div id="dialog" class="dialog" style="display: block;">
<p id="validateTips"><?php echo lang('tni_form_fields_required'); ?></p>
<?php echo form_open('phonebook/add_contact_process', array('id' => 'addContact'));?>
<fieldset>
<input type="hidden" name="pbk_id_user" id="pbk_id_user" value="<?php echo $this->session->userdata('id_user');?>" />
<label class="bold" for="name"><?php echo lang('tni_contact_name'); ?></label>
<input type="text" name="name" id="name" value="<?php if(isset($contact)) echo $contact->row('Name');?>" class="text ui-widget-content ui-corner-all required" />
<label class="bold"for="number"><?php echo lang('tni_contact_phonenumber'); ?></label>
<input type="text" name="number" id="number" value="<?php if(isset($contact)) echo $contact->row('Number'); else if(isset($number)) echo $number;?>" class="text ui-widget-content ui-corner-all required phone" />

<label class="bold" for="province_id">Provinsi</label>
<?php 
$province_act = (isset($province_id)) ? $province_id : '';
$option = 'class="text ui-widget-content ui-corner-all province" id="province"';
echo form_dropdown('province_id',$province_list,$province_act,$option);
?>

<label class="bold" for="district_id">Kabupaten</label>
<?php 
if($type=='edit'){
    $district_act = (isset($district_id)) ? $district_id : '';
    $option = 'class="text ui-widget-content ui-corner-all district" id="district"';
    echo form_dropdown('district_id',$district_list,$district_act,$option);
}else{
    $district_act = 'default';
    $district_list['default'] = '-- Pilih --';
    $option = 'class="text ui-widget-content ui-corner-all district" id="district"';
    echo form_dropdown('district_id',$district_list,$district_act,$option);
}
?>

<label class="bold" for="subdistrict_id">Kecamatan</label>
<?php 
if($type=='edit'){
    $subdistrict_act = (isset($subdistrict_id)) ? $subdistrict_id : '';
    $option = 'class="text ui-widget-content ui-corner-all subdistrict" id="subdistrict"';
    echo form_dropdown('subdistrict_id',$subdistrict_list,$subdistrict_act,$option);
}else{
    $subdistrict_act = 'default';
    $subdistrict_list['default'] = '-- Pilih --';
    $option = 'class="text ui-widget-content ui-corner-all subdistrict" id="subdistrict" disabled="disabled"';
    echo form_dropdown('subdistrict_id',$subdistrict_list,$district_act,$option);
}
?>

<label class="bold" for="village_id">Desa</label>
<?php 
if($type=='edit'){
    $village_act = (isset($contact)) ? $contact->row('village_id') : '';
    $option = 'class="text ui-widget-content ui-corner-all village" id="village"';
    echo form_dropdown('village_id',$village_list,$village_act,$option);
}else{
    $village_act = 'default';
    $village_list['default'] = '-- Pilih --';
    $option = 'class="text ui-widget-content ui-corner-all village" id="village" disabled="disabled"';
    echo form_dropdown('village_id',$village_list,$village_act,$option);
}
?>
<!-- Alamat -->
<label class="bold" for="alamat">Alamat</label>
<textarea name="alamat" id="alamatLembaga" cols="40" rows="3" ><?php if(isset($contact)) echo $contact->row('alamat');?></textarea><br/>
                        <div class="alamatContainer">
                        <!--
                            <table>
                            <tr>
                                <td class="mapOption">
                                    <input type="radio" id="byAddress" value="byAddress" name="alamatBy" style="float:left;display: inline;"><label style="float:left;display: inline;"for="byAddress">Berdasarkan Alamat di atas</label>
                                    <input type="radio" id="byLatLong" value="byLatLong" name="alamatBy" style="clear:both;float:left;display: inline;"><label style="float:left;display: inline;"for="byLatLong">Berdasarkan Kordinat LatLong</label>
                                    <p style="clear:both;padding:8px 0 0 25px;">
                                      Latitude<br/><input type="text" name="alamatLat" id="alamatLat" value="<?php if(isset($titikpeta[0]))echo $titikpeta[0]; ?>" onfocus="setByLatLongChecked()">
                                      Longitude<br/><input type="text" name="alamatLong" id="alamatLong" value="<?php if(isset($titikpeta[1]))echo $titikpeta[1]; ?>" onfocus="setByLatLongChecked()">
                                    </p>
                                    <input type="button" value="Get Map" onClick="petaAlamat('new')"> <br/>
                                    <input type="hidden" name="alamatLatLong" id="alamatLatLong" value="<?php if(isset($contact)) echo $contact->row('latlong');?>">
                                     <div id="label_titik_alamat"></div>
                                </td>
                            </tr>
                            <tr>
                               <td>
                                    <div id="gMap" style="width:100%;height:200px;">Memuat peta . . . </div>
                                </td>                            
                            </tr>
                                
                        </table>
                        -->
                        </div>
<!-- End of Alamat -->
<br/>
<label class="bold" for="group"><?php echo lang('kalkun_group'); ?></label> 
<?php if(isset($contact)): ?> 
<input name="groups" id="groups" value="<?php echo $this->Phonebook_model->get_groups($contact->row('id_pbk'),$this->session->userdata('id_user'))->GroupNames?>" type="hidden" />
<?php else : ?>
<input name="groups" id="groups" value=""  type="hidden" />
<?php endif;?>
<?php
//$group = $this->Phonebook_model->get_phonebook(array('option' => 'group'));
//foreach($group->result() as $tmp):
//	$groups[$tmp->ID]=$tmp->GroupName;
//endforeach; 
//$group_act = (isset($contact)) ? $contact->row('ID') : '';
//$option = 'class="text ui-widget-content ui-corner-all"';
//echo form_dropdown('groupvalue', $groups, $group_act, $option);
?>
<?php if(isset($contact)): ?> 
<input type="hidden" name="editid_pbk" id="editid_pbk" value="<?php echo $contact->row('id_pbk');?>" />
<?php endif;?>
</fieldset>
<?php echo form_close();?>
</div>
