<div class="title_page"><h2>Tambah Kategori</h2></div>
<div class="admin_post_container">    
<?php 
if($type_form == 'update') {
	echo form_open('posts/ajax_update_category');
} else {
	echo form_open('posts/ajax_add_category');
}
?>
	<?php if($type_form == 'update') { 
		echo "<input type='hidden' name='categoryID' value='" . $isi['categoryID'] . "'>"; 
	} ?>
	<label>Nama Kategori</label><br>
    <input type="text" name="CategoryName" <?php if($type_form == 'update') { echo "value = '" . $isi['CategoryName'] . "'"; } ?> style="width: 250px;"><br><br>
	<label>Diskripsi</label><br>
	<textarea name="CategoryDescription"><?php if($type_form == 'update') { echo $isi['CategoryDescription']; } ?></textarea><br>
	<br>
	<input type="submit" class="button" value="Simpan" />
</form>
<br>
</div>

