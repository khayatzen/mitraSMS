<script type="text/javascript">
$(document).ready(function() {	    
var base = "<?php echo  site_url();?>/messages/delete_messages/";
var source = "<?php echo $this->uri->segment(4);?>";
var current_folder = "<?php echo $this->uri->segment(6);?>";
var publish_url = '<?php echo  site_url();?>/messages/publish_message/';
//var dest_url = base + source;
     
<?php if($this->config->item('enable_emoticons')) : ?> 
$(".message_preview").emoticons("<?php echo   $this->config->item('img_path').'emoticons/'; ?>");
$(".message_content").emoticons("<?php echo   $this->config->item('img_path').'emoticons/'; ?>");        
<?php endif; ?>

// Delete messages
$("a.global_delete").click(function(){
var count = $("input:checkbox:checked").length;
if(count==0) { 
	$('.notification_area').text("<?php echo lang('tni_msg_no_conv_selected'); ?>");
	$('.notification_area').show();
}
else {
	$("input.select_message:checked").each( function () {
	   var message_row = $(this).parents('div:eq(2)');
         id_access = '#item_source'+$(this).val();
         item_folder =  $(id_access).val();
         dest_url = base + item_folder;
		$.post(dest_url, {type: 'single', id: $(this).val(), current_folder: current_folder}, function() {
			$(message_row).slideUp("slow");
		});
	});
}
});

// Move folder
$(".move_to").click(function() {
var count = $("input:checkbox:checked").length;
if(count==0) { 
	$("#movetodialog").dialog('close');
	$('.notification_area').text("<?php echo lang('tni_msg_no_conv_selected'); ?>");
	$('.notification_area').show();
}
else {    	
var id_folder = $(this).attr('id');
$("#movetodialog").dialog('close');
$('.loading_area').fadeIn("slow");
$("input.select_message:checked").each(function () {
	var message_row = $(this).parents('div:eq(2)');
    id_access = '#item_source'+$(this).val();
    item_folder =  $(id_access).val();
	$.post("<?php echo  site_url('messages/move_message') ?>", {type: 'single', current_folder: current_folder, folder: item_folder, 
		id_folder: id_folder, id_message: $(this).val()}, function() {
		$(message_row).slideUp("slow");
	});
});		
$('.loading_area').fadeOut("slow");
}
});    
    
$(".move_to_button").click(function() {
	$('#movetodialog').dialog('open');
	return false;
});

// Move To dialog
$("#movetodialog").dialog({
	bgiframe: true,
	autoOpen: false,
	modal: true,
});        
    

// message detail
$("span.message_toggle").click(function(){
var row = $(this).parents('div:eq(1)');
$(row).find("div.message_content").toggle();
$(row).find("span.message_preview").toggle();
$(row).find("div.optionmenu").toggle();
	
if($(row).find("div.detail_area").is(":visible"))
{ 
	$(row).find("div.detail_area").toggle(); 
	$(row).find("a.detail_button").html('<?php echo lang('tni_show_details'); ?>'); 
}
return false;
});
    
    
// select all
$("a.select_all_button").click(function(){
	$(".select_message").attr('checked', true);
	$(".messagelist").addClass("messagelist_hover");
	return false;
});

// clear all
$("a.clear_all_button").click(function(){
	$(".select_message").attr('checked', false);
	$(".messagelist").removeClass("messagelist_hover");
	return false;
});        
    
<?php if(!is_ajax()) : ?>
// refresh
$("a.refresh_button").click(function(){
	$('.loading_area').html('Loading...');
    $('.loading_area').fadeIn("slow");
	$('#message_holder').load("<?php echo  site_url('messages/conversation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6,0)) ?>", function() { 
        new_notification();
        $('.loading_area').fadeOut("slow");
    });
});    

<?php endif; ?> 
    
// Reply SMS
$('a.reply_button, a.forward_button').bind('click', function() {
var button = $(this).attr('class');
var url = '<?php echo site_url('messages/compose')?>';

if(button=='reply_button')
{
	var type = 'reply';
	// var param1 = '<?php echo $this->uri->segment(5);?>'; depreciated
    var param1 = $(this).parents('div:eq(1)').children().children('input.item_number').val();  /* phone number */
    var param2 = $(this).parents('div:eq(1)').children().children('input.item_id_folder').val();  /* id folder */
    var param3 = $(this).parents('div:eq(1)').children().children('input.item_id_inbox').val();  /* id inbox */
         
}
else if(button=='forward_button')
{
	var type = 'forward';
	var header = $(this).parents('div:eq(1)');
        var param1 = header.attr('source');
	var param2 = header.children().children('input.select_message').attr('id'); /* message_id */
}
		
$("#compose_sms_container").load(url, { 'type': type, 'param1': param1, 'param2': param2, 'param3': param3}, function() {
  $(this).dialog({
    modal: true,
    draggable : true,     
	width: 550,
	show: 'fade',
	hide: 'fade',
    buttons: {
	'<?php echo lang('tni_send_message'); ?>': function() {
		if($("#composeForm").valid()) {
		$.post("<?php echo site_url('messages/compose_process') ?>", $("#composeForm").serialize(), function(data) {
			$("#compose_sms_container").html(data);
			$("#compose_sms_container").dialog({ buttons: { "Okay": function() { $(this).dialog("close"); } } });
			setTimeout(function() {$("#compose_sms_container").dialog('close')} , 1500);
		});
		}
	},
	'<?php echo lang('kalkun_cancel'); ?>': function() { $(this).dialog('close');}
    }
  });
});
$("#compose_sms_container").dialog('open');
return false;
});    
	
    
// Character counter	
$('.word_count').each(function(){   
var length = $(this).val().length;  
var message = Math.ceil(length/160);
$(this).parent().find('.counter').html( length + ' characters / ' + message + ' message(s)');  
	$(this).keyup(function(){  
		var new_length = $(this).val().length;  
		var message = Math.ceil(new_length/160);
		$(this).parent().find('.counter').html( new_length + ' characters / ' + message + ' message(s)');  
	});  
});    
   		
// Show/hide detail
$('a.detail_button').click(function() {
var row = $(this).parents('div:eq(2)');
$(row).find("div.detail_area").toggle();
	
if($(this).text()=='<?php echo lang('tni_hide_details'); ?>') $(this).html('<?php echo lang('tni_show_details'); ?>');
else $(this).html('<?php echo lang('tni_hide_details'); ?>');
return false;		
});	

// Add contact
$('.add_to_pbk').bind('click', function() {
var param1 = $(this).parents('div:eq(1)').children().children('input.item_number').val();  /* phone number */
$("#contact_container").load('<?php echo site_url('phonebook/add_contact')?>', { 'type': 'message', 'param1': param1}, function() {
$(this).dialog({
	title: 'Add contact',
	modal: true,
	show: 'fade',
	hide: 'fade',
	buttons: {
	'Save': function() {
		//if($("#addContact").valid()) {
		$.post("<?php echo site_url('phonebook/add_contact_process') ?>", $("#addContact").serialize(), function(data) {
		$("#contact_container").html(data);
		$("#contact_container").dialog({ buttons: { "Okay": function() { $(this).dialog("close"); } } });
		setTimeout(function() {$("#contact_container").dialog('close')} , 1500);
	});
	}, Cancel: function() { $(this).dialog('close');} }
	});
});
$("#contact_container").dialog('open');
return false;
});	

/******************************
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

});    
</script>
