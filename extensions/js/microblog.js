jQuery(document).ready(function() {
    jQuery('head').append('<link href="http://smsburuhmigran.infest.or.id/extensions/css/microblog.css" rel="stylesheet" type="text/css"/>');
    microbloging();
});

var microblog_sms_server_URL = 'http://smsburuhmigran.infest.or.id/index.php/microblog/microbloging/callback=?';

function microbloging(){
jQuery.ajax({
      url: microblog_sms_server_URL,
      type: "GET",
      dataType: 'jsonp',  //Required
      success: function(data){
		  jQuery("#sms-tabs").html(data.content);
		  jQuery("#sms-tabs").tabs();
      }
   });

}