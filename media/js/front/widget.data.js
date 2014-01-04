var sms_server_URL = 'http://smsburuhmigran.infest.or.id/index.php/microblog/widget/callback=?';
$(document).ready(function(){
    $('head').append('<link rel="stylesheet" href="http://smsburuhmigran.infest.or.id/extensions/css/style.css" type="text/css" />');
    getSMS();
});
function smsTicker(){
	$(".smsticker").jCarouselLite({
		vertical: true,
		hoverPause:true,
		visible: 2,
		auto:500,
		speed:3000
	});
}
function getSMS(){
$.ajax({
      url: sms_server_URL,
      type: "GET",
	dataType: 'jsonp',  //Required
        success: function(data){
		//alert(data);
        $("#loadingSMS").html('');
        var items='';
        $.each(data.messages, function(i, message) {
            items +='<li><div><b><u>'+ message.number +'&nbsp;|&nbsp;'+message.folder+'</u></b><br/>'+ message.TextDecoded +'<br/><small>'+ message.globaldate+'</small></div></li>';
	});
	$('<ul>'+ items +'</ul>').appendTo(".smsticker");
	smsTicker();
      }
   });

}