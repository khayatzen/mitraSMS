<script type="text/javascript">
$(document).ready(function() {

	$('#submit').click(function() {
		tinyMCE.triggerSave();
		if ('<?php echo $type_form; ?>' == 'post') {
			$.ajax({
			url: "<?php echo site_url('pages/ajax_add'); ?>",
			type: 'POST',
			data: $("#BlogPostForm").serialize(),
			success: function(msg) {
				window.location.replace("<?php echo site_url() . '/pages' ?>");
			}
			});
		} else {
			$.ajax({
			url: "<?php echo site_url('pages/ajax_update_page'); ?>",
			type: 'POST',
			data: $("#BlogPostForm").serialize(),
			success: function(msg) {
 				window.location.replace("<?php echo site_url() . '/pages' ?>");
			}
			});
		}
		
		return false;
	});
});
</script>
<!-- Load TinyMCE -->
<script type="text/javascript" src="<?php echo $this->config->item('js_path');?>tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
		elements : "ajaxfilemanager",
		plugins: "emotions,inlinepopups,insertdatetime,preview,searchreplace,contextmenu,paste,fullscreen,noneditable",
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontsizeselect,|,sub,sup,|,bullist,numlist,|,outdent,indent",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,|,search,replace,|,undo,redo,|,link,unlink,image,youtube,code,emotions,|,removeformat,visualaid,|,insertdate,inserttime,preview,fullscreen",
        theme_advanced_buttons3: "",
        theme_advanced_buttons4: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,

			extended_valid_elements : "hr[class|width|size|noshade]",

			file_browser_callback : "ajaxfilemanager",
			paste_use_dialog : false,
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : true,
			apply_source_formatting : true,
			force_br_newlines : true,
			force_p_newlines : false,	
			remove_script_host : false,
			relative_urls : false

});

		function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "<?php echo $this->config->item('js_path');?>tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php";
			var view = 'detail';
			switch (type) {
				case "image":
				view = 'thumbnail';
					break;
				case "media":
					break;
				case "flash": 
					break;
				case "file":
					break;
				default:
					return false;
			}
            tinyMCE.activeEditor.windowManager.open({
                url: "<?php echo $this->config->item('js_path');?>tiny_mce/plugins/ajaxfilemanager/ajaxfilemanager.php?view=" + view,
                width: 782,
                height: 440,
                inline : "yes",
                close_previous : "no"
            },{
                window : win,
                input : field_name
            });
		}
</script>
<!-- /TinyMCE -->

<div class="title_page"><h2>Buat halaman baru</h2></div>
<div class="admin_post_container">	
	<form method="post" id="BlogPostForm">
	<?php if($type_form == 'update') { 
		echo "<input type='hidden' name='postID' value='" . $isi['postID'] . "'>"; 
	} ?>
    <input type="text" name="PostTitle" <?php if($type_form == 'update') { echo 'value=' . '"' . $isi['PostTitle'] . '"'; } ?> style="width: 525px;">
		<br><br>
	<textarea class="tinymce" name="PostContent" width="100%"><?php if($type_form == 'update') { echo $isi['PostContent']; } ?></textarea>
	<br>
	<input type="submit" class="button" value="Publish" id="submit" />
	</form>
<br>
<?php echo form_close();?>
</div>

 
