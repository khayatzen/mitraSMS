<!--############## Widget SMS ####################-->
<link rel="stylesheet" href="<?= base_url()?>media/css/front/microblog.widget.css" type="text/css" />
<script type="text/javascript" src="<?= base_url()?>media/js/front/prettydate.js"></script>
<script type="text/javascript">
        var get_broadcast_messages_URL = '<?= base_url()?>index.php/microblog/get_broadcast_messages';
        var get_custom_messages_URL = '<?= base_url()?>index.php/microblog/get_custom_messages';
        var get_incoming_messages_URL = '<?= base_url()?>index.php/microblog/get_incoming_messages';
        var get_microblog_conversation_URL = '<?= base_url()?>index.php/microblog/get_microblog_conversation';
        $(document).ready(function(){     
            //get_incoming_messages('','');
            //get_custom_messages('','');
            get_broadcast_messages('','');
            get_microblog_conversation('','');
            //get_microblog_conversation('','');
            //setTimeout(function(){reload_pretty_date();},1000);
            //reload_pretty_date();
        });

        function get_broadcast_messages(stream_type,stream_date){           
            $.ajax({
                    url: get_broadcast_messages_URL,
                    type: "POST",
                    data: "stream_type="+stream_type+"&stream_date="+stream_date,
                    dataType: "json",  //Required
                    beforeSend: function() {$("#broadcast_message_container .loading-message").show();},
                    complete: function() { $("#broadcast_message_container .loading-message").hide();},
                    success: function(data){                                
                                var items='';
                                $.each(data.messages, function(i, message) {
                                    items +='<div id="stream_item" stream_date="'+message.globaldate+'"><div><strong><u>'+ message.number +'</u></strong><br/>'+ message.TextDecoded +'<br/><div class="stream_date" date="'+message.globaldate+'">'+ message.nice_date+'</div></div></div>';
                                });
                                if(items !== ''){
                                    if(stream_type == 'old_stream'){
                                        $(items).appendTo("#microblog_conversation_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                    }else{
                                        $(items).prependTo("#broadcast_message_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                        if($("div.customScrollBox").size() > 0) mCustomScrollbar();
                                    }
                                } 
                                var newest_date = $("#broadcast_message_stream div:first-child").attr("stream_date");
                                var oldest_date = $("#broadcast_message_stream div:last-child").attr("stream_date");
                                //alert(newest_date);
                                setTimeout(function(){
                                    get_broadcast_messages('new_stream',newest_date);
                                    reload_pretty_date();
                                },30000);
                             },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }         
            });          
                
        }
        function get_custom_messages(stream_type,stream_date){           
            $.ajax({
                    url: get_custom_messages_URL,
                    type: "POST",
                    data: "stream_type="+stream_type+"&stream_date="+stream_date,
                    dataType: 'json',  //Required
                    beforeSend: function() {$("#custom_message_container .loading").show();},
                    complete: function() { $("#custom_message_container .loading").hide();},
                    success: function(data){
                                //alert(data);
                                var items='';
                                $.each(data.messages, function(i, message) {
                                    //items +='<li><div><strong><u>'+ message.number +'&nbsp;|&nbsp;'+message.folder+'</u></strong><br/>'+ message.TextDecoded +'<br/><small>'+ message.globaldate+'</small></div></li>';
                                    items +='<div id="stream_item" stream_date="'+message.globaldate+'"><div><strong><u>'+ message.number +'</u></strong><br/>'+ message.TextDecoded +'<br/><small>'+ message.nice_date+'</small></div></div>';
                                });
                                if(items !== ''){
                                    $(items).prependTo("#custom_message_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                    //$("#custom_message_stream").prepend(items).animate({backgroundColor:"green"},  100).animate({backgroundColor:"white" },  1000);
                                    if($("div.customScrollBox").size() > 0) mCustomScrollbar();
                                } 
                                var newest_date = $("#custom_message_stream div:first-child").attr("stream_date");
                                var oldest_date = $("#custom_message_stream div:last-child").attr("stream_date");
                                //alert(newest_date);
                                setTimeout(function(){
                                    get_custom_messages('new_stream',newest_date);
                                },20000);
                             },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }         
            });          
                
        }
        function get_incoming_messages(stream_type,stream_date){           
            $.ajax({
                    url: get_incoming_messages_URL,
                    type: "POST",
                    data: "stream_type="+stream_type+"&stream_date="+stream_date,
                    dataType: 'json',  //Required
                    beforeSend: function() {$("#incoming_message_container .loading-message").show();},
                    complete: function() {$("#incoming_message_container .loading-message").hide();},
                    success: function(data){
                                //alert(data);
                                var items='';
                                $.each(data.messages, function(i, message) {
                                    //items +='<li><div><strong><u>'+ message.number +'&nbsp;|&nbsp;'+message.folder+'</u></strong><br/>'+ message.TextDecoded +'<br/><small>'+ message.globaldate+'</small></div></li>';
                                    items +='<div id="stream_item" stream_date="'+message.globaldate+'"><div><strong><u>'+ message.number +'</u></strong><br/>'+ message.TextDecoded +'<br/><small>'+ message.nice_date+'</small></div></div>';
                                });
                                if(items !== ''){
                                    $(items).prependTo("#incoming_message_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                    //$("#custom_message_stream").prepend(items).animate({backgroundColor:"green"},  100).animate({backgroundColor:"white" },  1000);
                                    //mCustomScrollbar();
                                } 
                                var newest_date = $("#incoming_message_stream div:first-child").attr("stream_date");
                                var oldest_date = $("#incoming_message_stream div:last-child").attr("stream_date");
                                //alert(newest_date);
                                setTimeout(function(){
                                    get_incoming_messages('new_stream',newest_date);
                                },10000);
                             },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }
            });          
                
        }        
        function get_microblog_conversation(stream_type,stream_date){ 
            $.ajax({
                    url: get_microblog_conversation_URL,
                    type: "POST",
                    data: "stream_type="+stream_type+"&stream_date="+stream_date,
                    dataType: "json",  //Required
                    beforeSend: function() {$("#incoming_message_container .loading-message").show();},
                    complete: function() { $("#incoming_message_container .loading-message").hide();},
                    success: function(data, textStatus, jqXHR) {
                        var items='';
                                $.each(data.messages, function(i, message) {
                                    //items +='<li><div><strong><u>'+ message.number +'&nbsp;|&nbsp;'+message.folder+'</u></strong><br/>'+ message.TextDecoded +'<br/><small>'+ message.globaldate+'</small></div></li>';
                                    items +='<div id="stream_item" class="stream_item" stream_date="'+message.globaldate+'"><div><strong><u>'+ message.number +'</u></strong><br/>'+ message.TextDecoded +'<br/><div class="stream_date" date="'+message.globaldate+'">'+ message.nice_date+'</div></div></div>';
                                });                                
                                if(items !== ''){
                                    if(stream_type == 'old_stream'){
                                        $(items).appendTo("#microblog_conversation_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                    }else{
                                         $(items).prependTo("#microblog_conversation_stream").animate({backgroundColor:"#9BCCF7"},  100).animate({backgroundColor:"#ffffff" },  5000);
                                         //$("#custom_message_stream").prepend(items).animate({backgroundColor:"green"},  100).animate({backgroundColor:"white" },  1000);
                                         //if($("div.customScrollBox").size() > 0) mCustomScrollbar();
                                    }                                   
                                } 
                                var newest_date = $("#microblog_conversation_stream div:first-child").attr("stream_date");
                                var oldest_date = $("#microblog_conversation_stream div:last-child").attr("stream_date");
                                //alert(newest_date);
                                setTimeout(function(){
                                    get_microblog_conversation('new_stream',newest_date);
                                    reload_pretty_date();
                                },10000);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                    }
            });          
                
        }
        /*
         * Reload - Recalculate date to pretty date
         */
         function reload_pretty_date(){                
                $("div.stream_date").each(function(index){                    
                    var stream_date = $(this).attr('date');//alert(stream_date);
                    var prettydate = prettyDate(stream_date);                    
                    $(this).text(prettydate);
                });
         }
</script>    


<!--############## End Widget SMS ####################-->