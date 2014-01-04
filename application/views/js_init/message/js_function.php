<script type="text/javascript">
var count = 0;

$(document).ready(function() {
	
    var offset = "<?php echo $offset;?>";    
    var folder = "<?php echo $folder;?>";    
    var base_url = "<?php echo site_url();?>";
    var source = "<?php echo $type;?>";
    var delete_url = base_url + '/messages/delete_messages/';
    var move_url = base_url + '/messages/move_message/';
    var publish_url = base_url + '/messages/publish_message/';
    var spam_url = base_url + '/messages/mark_spam/';
    var refresh_url = base_url + '/messages/' + folder + '/' + source;
    var delete_folder_url = base_url + '/kalkun/delete_folder/';
    
    if(folder=='folder')
    {
    	var current_folder = '';
    	var id_folder = '';
    }
    else 
    {	
    	var current_folder = "<?php echo $id_folder;?>";
    	var id_folder = "<?php echo $id_folder;?>";
    	refresh_url = refresh_url + '/' + id_folder;
    }
    
    refresh_url = refresh_url + '/' + offset;
     
	// --------------------------------------------------------------------
	
	/**
	 * Delete conversation
	 *
	 * Delete all messages on selected conversation
	 *
	 */	
	$("a.global_delete").click(function()
	{
		var count = $("input.select_conversation:checkbox:checked").length;
		if(count==0) 
		{ 
			show_notification("<?php echo lang('tni_msg_no_conv_selected')?>");
		}
		else 
		{
			$('.loading_area').fadeIn("slow");
			$("input.select_conversation:checked").each(function() 
			{
				var message_row = $(this).parents('div:eq(2)');
				$.post(delete_url + source, {type: 'conversation', number: $(this).val(), current_folder: current_folder}, function() 
				{
					$(message_row).slideUp("slow");
					$(message_row).remove();
				});
			});
			$('.loading_area').fadeOut("slow");
			show_notification(count + ' conversation deleted'); // translate
		}
	});

	// --------------------------------------------------------------------
	
	/**
	 * Move conversation
	 *
	 * Move all messages on selected conversation from a folder to another folder
	 *
	 */		       
    $(".move_to").click(function() {
		var count = $("input.select_conversation:checkbox:checked").length;
		if(count==0) {
			$("#movetodialog").dialog('close');
			show_notification("<?php echo lang('tni_msg_no_conv_selected')?>");
		}
		else 
		{    	
			var id_folder = $(this).attr('id');	
			$("#movetodialog").dialog('close');
			$('.loading_area').fadeIn("slow");
			$("input.select_conversation:checked").each(function () {
				var message_row = $(this).parents('div:eq(2)');
				$.post(move_url, {type: 'conversation', current_folder: current_folder, folder: source, 
					id_folder: id_folder, number: $(this).val()}, function() {
					$(message_row).slideUp("slow");
				});
			});
			$('.loading_area').fadeOut("slow");
			show_notification(count + ' conversation moved'); // translate
		}
		count=0;
    });
    
    $(".move_to_button").click(function() 
    {
		$("#movetodialog").dialog({
			bgiframe: true,
			autoOpen: false,
			modal: true,
		});    	
    	$('#movetodialog').dialog('open');
    	return false;
    });
    
    // select all
    $("a.select_all_button").click(function()
    {
    	$(".select_conversation").attr('checked', true);
    	$(".messagelist").addClass("messagelist_hover");
    	return false;
    });
    
    // clear all
    $("a.clear_all_button").click(function()
    {
    	$(".select_conversation").attr('checked', false);
    	$(".messagelist").removeClass("messagelist_hover");
    	return false;
    });        
    
    // input checkbox
    $("input.select_conversation").click(function()
    {
    	if($(this).attr('checked')==true) 
    	{
    		$(this).parents('div:eq(2)').addClass("messagelist_hover");
    	}
    	else 
    	{
    		$(this).parents('div:eq(2)').removeClass("messagelist_hover");
    	}
    });
    
    // refresh
    $("a.refresh_button").click(function()
    {  	
    	$('.loading_area').html('Loading...');
        $('.loading_area').fadeIn("slow");
    	$('#message_holder').load(refresh_url, function() {
    	    new_notification();		
            $('.loading_area').fadeOut("slow");
        });  
    	return false;
    });
        	
	// --------------------------------------------------------------------
	
	/**
	 * Rename folder
	 *
	 * Rename custom folder
	 *
	 */	
	$('#renamefolder').click(function() 
	{
		$("#renamefolderdialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 100,
			modal: true,	
			buttons: {
				'<?php echo lang('kalkun_save'); ?>': function() {
					$("form.renamefolderform").submit();
				},
				'<?php echo lang('kalkun_cancel'); ?>': function() {
					$(this).dialog('close');
				}
			}
		});
		var editname = $(this).parents('div').children("span.folder_name").text();
		$("#edit_folder_name").val(editname);
		$('#renamefolderdialog').dialog('open');
	});	    
			
	// --------------------------------------------------------------------
	
	/**
	 * Delete folder
	 *
	 * Delete custom folder
	 *
	 */	
	$('#deletefolder').click(function()
	{
		$("#deletefolderdialog").dialog({
			bgiframe: true,
			autoOpen: false,
			height: 165,
			modal: true,	
			buttons: {
				'<?php echo lang('kalkun_cancel'); ?>': function() {
					$(this).dialog('close');
				},
				'<?php echo lang('tni_delete_folder'); ?>': function() {
					location.href=delete_folder_url + id_folder;
				}
			}
		});			
		$('#deletefolderdialog').dialog('open');
		return false;
	});
        // --------------------------------------------------------------------
	
	/**
	 * Publish Message
	 *
	 * Publish all messages on selected conversation
	 *
	 */	
	$("a.publish_button").click(function()
	{
            
		var count = $("input.select_conversation:checkbox:checked").length;
		if(count==0) 
		{ 
			show_notification("<?php echo lang('tni_msg_no_conv_selected')?>");
		}
		else 
		{
			$('.loading_area').fadeIn("slow");
			$("input.select_conversation:checked").each(function() 
			{
				var message_row = $(this).parents('div:eq(1)');
                                var checkbox = $(this);
				$.post(publish_url, {type: 'inbox', id_message:$(this).attr('id_message'),number: $(this).val()}, function() 
				{
					//alert(message_row.attr('class'));
                                        $(message_row).removeClass("unpublished",1500);
                                        $(message_row).parents('div').removeClass('messagelist_hover');
                                        $(checkbox).attr('checked',false);
				});
			});
			$('.loading_area').fadeOut("slow");
			show_notification(count + ' message(s) published'); // translate
		}
	});
        /**
	 * Mark as Spam
	 *
	 * Mark as spam all messages on selected conversation
	 *
	 */	
	$("a.spam_button").click(function()
	{
		var count = $("input.select_conversation:checkbox:checked").length;
		if(count==0) 
		{ 
			show_notification("<?php echo lang('tni_msg_no_conv_selected')?>");
		}
		else 
		{
			$('.loading_area').fadeIn("slow");
			$("input.select_conversation:checked").each(function() 
			{
				var message_row = $(this).parents('div:eq(2)');
				$.post(spam_url, {id_message:$(this).attr('id_message'),phone_number: $(this).val(), current_folder: current_folder}, function() 
				{
					$(message_row).slideUp("slow");
					$(message_row).remove();
				});
			});
			$('.loading_area').fadeOut("slow");
			show_notification(count + ' conversation mark as spam'); // translate
		}
	});

});    
</script>