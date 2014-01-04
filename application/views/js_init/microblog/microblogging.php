<script type="text/javascript">
$(function() {
    $("#microblog-tabs").tabs();
    $("#more_broadcast_stream").click(function(){
        var oldest_date = $("#broadcast_message_stream div.stream_item:last-child").attr("stream_date");
        get_broadcast_messages('old_stream',oldest_date);
    });
    $("#more_conversation_stream").click(function(){
        var oldest_date = $("#microblog_conversation_stream div.stream_item:last-child").attr("stream_date");
        get_microblog_conversation('old_stream',oldest_date);
    });
});
</script>
